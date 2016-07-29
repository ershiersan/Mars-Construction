<?php

namespace mdm\admin\models;

use Yii;
use mdm\admin\components\Configs;
use yii\helpers\Url;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id Menu id(autoincrement)
 * @property string $name Menu name
 * @property integer $parent Menu parent
 * @property string $route Route for this menu
 * @property integer $order Menu order
 * @property string $data Extra information for this menu
 *
 * @property Menu $menuParent Menu parent
 * @property Menu[] $menus Menu children
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Menu extends \yii\db\ActiveRecord
{
    public $parent_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Configs::instance()->menuTable;
    }

    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        if (Configs::instance()->db !== null) {
            return Configs::instance()->db;
        } else {
            return parent::getDb();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_name'], 'filterParent'],
            [['parent_name'], 'in',
                'range' => static::find()->select(['name'])->column(),
                'message' => 'Menu "{value}" not found.'],
            [['parent', 'route', 'data', 'order'], 'default'],
            [['order'], 'integer'],
            /* [['route'], 'in',
                'range' => static::getSavedRoutes(),
                'message' => 'Route "{value}" not found.'] */
        ];
    }

    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $value = $this->parent_name;
        $parent = self::findOne(['name' => $value]);
        if ($parent) {
            $id = $this->id;
            $parent_id = $parent->id;
            while ($parent) {
                if ($parent->id == $id) {
                    $this->addError('parent_name', 'Loop detected.');

                    return;
                }
                $parent = $parent->menuParent;
            }
            $this->parent = $parent_id;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rbac-admin', 'ID'),
            'name' => Yii::t('rbac-admin', 'Name'),
            'parent' => Yii::t('rbac-admin', 'Parent'),
            'parent_name' => Yii::t('rbac-admin', 'Parent Name'),
            'route' => Yii::t('rbac-admin', 'Route'),
            'order' => Yii::t('rbac-admin', 'Order'),
            'data' => Yii::t('rbac-admin', 'Data'),
        ];
    }

    /**
     * Get menu parent
     * @return \yii\db\ActiveQuery
     */
    public function getMenuParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent']);
    }

    /**
     * Get menu children
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['parent' => 'id']);
    }

    /**
     * Get menu array
     * 获取设置的所有菜单列表数据
     * @return array
     */
    public function getMenusArray() {
        $rsMenus = $this->findBySql(
            'select p.name as parent_name, s.name, s.route, p.order
            from menu as s
            inner join menu as p
            on s.parent = p.id
            order by p.id, s.order'
        )->all();
        $arrayMunus = array();
        if (is_array($rsMenus) && count($rsMenus) > 0) {
            $lastParent = '';
            foreach ($rsMenus as $Menu) {
                if ($lastParent != $Menu->parent_name) {
                    unset($oneMenu);
                    $oneMenu = &$arrayMunus[];
                }
                $lastParent = $oneMenu['text'] = $Menu->parent_name;
                $oneMenu['items'][] = [
                    'text'=>$Menu->name, 
                    'url'=>$Menu->route,
                ];
            }
            unset($oneMenu);
        } 
        return $arrayMunus;
    }

    /**
     * Get usable menu array
     * 根据当前登录用户获取设置的所有菜单列表数据
     * @return array
     */
    public function getUsableMenusArray() {
        $arrayMenus = self::getMenusArray();
        if (is_array($arrayMenus) && count($arrayMenus) > 0) {
            foreach ($arrayMenus as $oneMenuKey => $oneMenu) {
                if (is_array($oneMenu['items']) && count($oneMenu['items']) > 0) {
                    foreach ($oneMenu['items'] as $oneItemKey => $oneItem) {
                        if (!true/* $oneItem['url'] 是否有路由权限 */) {
                            // 移除该链接
                            unset($arrayMenus[$oneMenuKey]['items'][$oneItemKey]);
                        }
                    }
                    if (count($arrayMenus[$oneMenuKey]['items']) == 0) {
                        // 没有链接，移除父链接
                        unset($arrayMenus[$oneMenuKey]);
                    }
                }
            }
        }
        return $arrayMenus;
    }

    /**
     * Get saved routes.
     * @return array
     */
    public static function getSavedRoutes()
    {
        $result = [];
        foreach (Yii::$app->getAuthManager()->getPermissions() as $name => $value) {
            if ($name[0] === '/' && substr($name, -1) != '*') {
                $result[] = $name;
            }
        }

        return $result;
    }
}
