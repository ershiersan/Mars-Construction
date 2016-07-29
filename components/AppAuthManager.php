<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use app\models\AppUser;
use app\models\ProGroupAuth;
use sq\base\CURL;
use Yii;
use yii\db\Connection;
use yii\di\Instance;
use yii\caching\Cache;
use yii\rbac\PhpManager;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the three tables used to store the authorization data by setting [[itemTable]],
 * [[itemChildTable]] and [[assignmentTable]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class AppAuthManager extends PhpManager
{
    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $user = AppUser::findIdentity($userId);
        if($user->isMaster) {
            return true;
        }

        $menuIds = Yii::$app->cache->get("{$userId}_{$user->wx_id}");
        if(!$menuIds) {
            $groupIds = $this->getGroupIds($userId);
            $menuIds = $this->getRels($groupIds, $user->wx_id);
            Yii::$app->cache->set("{$userId}_{$user->wx_id}", $menuIds, 1800);
        }
        if(in_array($permissionName, $menuIds)) {
            return true;
        }
        return false;
    }

    public function getGroupIds($userid) {
        $res = CURL::get(Yii::$app->params['scrm_api'] . "app-role/usergroup/relists?is_del=n&userid=$userid", true);
        if($res && is_array($res['result'])) {
            $groupIds = [];
            foreach($res['result'] as $v) {
                $groupIds[] = $v['groupid'];
            }

            $groupIds = array_unique($groupIds);
            return $groupIds;
        }
        return [];
    }

    public function getRels22($groupIds, $wxId) {
        $uimenuIds = [];
        foreach($groupIds as $groupId) {
            $items = ProGroupAuth::findAll(['is_del' => 'N', 'group_id' => $groupId, 'wx_id' => $wxId]);
            if($items) {
                foreach($items as $item) {
                    $ids= explode(',', $item->role_ids);
                    $uimenuIds = array_merge($uimenuIds, $ids);
                }
            }
        }
        $uimenuIds = array_unique($uimenuIds);
        return $uimenuIds;
    }

    public function getRels($groupIds, $wxId) {
        $uimenuIds = [];
        foreach($groupIds as $groupId) {

            $res = CURL::get(Yii::$app->params['scrm_api'] . "app-role/wechat/relists?is_del=n&wx_id=$wxId&groupid=$groupId", true);
            if($res && is_array($res['result'])) {
                foreach($res['result'] as $v) {
                    $ids= explode(',', trim(trim($v['uimenu_str'], ']'), '['));
                    $uimenuIds = array_merge($uimenuIds, $ids);
                }
            }
        }

        $uimenuIds = array_unique($uimenuIds);
        return $uimenuIds;
    }
}
