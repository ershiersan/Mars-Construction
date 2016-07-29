<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
class m150923_023831_init extends Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }
    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child' => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id' => Schema::TYPE_STRING . '(64) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);


        $tables = Yii::$app->db->schema->getTableNames();
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        /* MYSQL */
        if (!in_array('admin_user', $tables))  {
            $this->createTable('{{%admin_user}}', [
                'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'username' => 'VARCHAR(128) NOT NULL',
                'password' => 'VARCHAR(128) NOT NULL',
                'salt' => 'VARCHAR(128) NOT NULL',
                'email' => 'VARCHAR(128) NOT NULL',
                'profile' => 'TEXT NULL',
            ], $tableOptions_mysql);
        }
        
        /* MYSQL */
        if (!in_array('menu', $tables))  {
            $this->createTable('{{%menu}}', [
                'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'name' => 'VARCHAR(128) NOT NULL',
                'parent' => 'INT(11) NULL',
                'route' => 'VARCHAR(256) NULL',
                'order' => 'INT(11) NULL',
                'data' => 'TEXT NULL',
            ], $tableOptions_mysql);
        }
        
        /* MYSQL */
        if (!in_array('settings', $tables))  {
            $this->createTable('{{%settings}}', [
                'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'category' => 'VARCHAR(64) NOT NULL DEFAULT \'system\'',
                'key' => 'VARCHAR(255) NOT NULL',
                'value' => 'TEXT NOT NULL',
            ], $tableOptions_mysql);
        }
        
        /* MYSQL */
        if (!in_array('wc_user', $tables))  {
            $this->createTable('{{%wc_user}}', [
                'openid' => 'VARCHAR(50) NOT NULL',
                0 => 'PRIMARY KEY (`openid`)',
                'nickname' => 'VARCHAR(80) COLLATE utf8mb4_bin COLLATE utf8mb4_bin DEFAULT NULL',
                'sex' => 'TINYINT(1) NULL',
                'province' => 'VARCHAR(20) NULL',
                'city' => 'VARCHAR(20) NULL',
                'country' => 'VARCHAR(50) NULL',
                'headimgurl' => 'VARCHAR(255) NULL',
                'language' => 'VARCHAR(15) NULL',
                'privilege' => 'TEXT NULL',
                'is_op' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'is_del' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'creation_date' => 'INT(10) NOT NULL',
                'gopenid' => 'VARCHAR(55) NULL',
            ], "DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        }

        if (!in_array('code', $tables)) {
            $this->createTable('{{%code}}', [
                'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'code' => 'VARCHAR(11) NULL',
                'openid' => 'VARCHAR(255) NULL',
                'unionid' => 'VARCHAR(55) NULL',
                'is_act' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'is_cost' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'headimgurl' => 'VARCHAR(255) NULL',
                'nickname' => 'VARCHAR(80) NULL COLLATE utf8mb4_bin',
                'user_tel' => 'VARCHAR(11) NULL',
                'q1_job' => 'VARCHAR(55) NULL',
                'q2_hotel' => 'VARCHAR(55) NULL',
                'integral' => 'VARCHAR(4) NULL',
                'act_date' => 'INT(11) NULL',
                'cost_date' => 'INT(11) NULL',
                'get_date' => 'INT(11) NULL',
                'is_del' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'creation_date' => 'INT(11) NOT NULL',
                'store_num' => 'VARCHAR(11) NOT NULL',
                'is_get' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'is_add_card' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'is_trans' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'gopenid' => 'VARCHAR(55) NULL',
                'ip' => 'VARCHAR(20) NULL',
                'from' => 'VARCHAR(55) NULL',
            ], "DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        }

        if (!in_array('store', $tables))  {
            $this->createTable('{{%store}}', [
                'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                0 => 'PRIMARY KEY (`id`)',
                'store_num' => 'VARCHAR(11) NULL',
                'memberid' => 'VARCHAR(11) NULL',
                'store_name' => 'VARCHAR(255) NULL',
                'store_addr' => 'VARCHAR(255) NULL',
                'store_tel' => 'VARCHAR(50) NULL',
                'contact' => 'VARCHAR(50) NULL',
                'username' => 'VARCHAR(255) NULL',
                'password' => 'VARCHAR(255) NULL',
                'url' => 'VARCHAR(255) NULL',
                'cid' => 'VARCHAR(20) NULL',
                'major' => 'VARCHAR(20) NULL',
                'minor' => 'VARCHAR(20) NULL',
                'uuid' => 'VARCHAR(100) NULL',
                'iBeacon_num' => 'VARCHAR(255) NULL',
                'is_del' => 'ENUM(\'N\',\'Y\') NULL DEFAULT \'N\'',
                'creation_date' => 'INT(11) NOT NULL',
            ], $tableOptions_mysql);
        }
        
        /* MYSQL */
        if (!in_array('cache', $tables))  {
            $this->createTable('{{%cache}}', [
                'id' => 'CHAR(128) NOT NULL',
                0 => 'PRIMARY KEY (`id`)',
                'expire' => 'INT(11) NULL',
                'data' => 'BLOB NULL',
            ], $tableOptions_mysql);
        }

	/* MYSQL */
	if (!in_array('app_role_user', $tables))  { 
		$this->createTable('{{%app_role_user}}', [
			'userid' => 'INT(11) NOT NULL AUTO_INCREMENT',
			0 => 'PRIMARY KEY (`userid`)',
			'email' => 'VARCHAR(255) NOT NULL',
			'password' => 'VARCHAR(255) NOT NULL',
			'realname' => 'VARCHAR(255) NULL',
			'jobtitle' => 'VARCHAR(255) NULL',
			'icon' => 'VARCHAR(255) NULL',
			'nickname' => 'VARCHAR(255) NULL',
			'session' => 'VARCHAR(255) NULL',
			'biz_id' => 'INT(11) NULL',
			'ctime' => 'INT(11) NULL',
			'utime' => 'INT(11) NULL',
			'ltime' => 'INT(11) NULL',
			'is_top' => 'ENUM(\'n\',\'y\') NULL DEFAULT \'n\'',
			'is_ban' => 'ENUM(\'n\',\'y\') NULL DEFAULT \'n\'',
			'is_del' => 'ENUM(\'n\',\'y\') NULL DEFAULT \'n\'',
		], $tableOptions_mysql);
	}
        
        
        $this->createIndex('idx_parent_6504_03','{{%menu}}','parent',0);
        $this->createIndex('idx_category_6517_04','{{%settings}}','category',0);
        
        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_menu_6501_04','{{%menu}}', 'parent', '{{%menu}}', 'id', 'CASCADE', 'NO ACTION' );    
	$this->insert('{{%menu}}',['id'=>'1','name'=>'系统管理','parent'=>'','route'=>'/admin/system','order'=>'','data'=>null]);
	$this->insert('{{%menu}}',['id'=>'3','name'=>'应用管理','parent'=>'','route'=>'/admin/','order'=>'3','data'=>null]);
	$this->insert('{{%menu}}',['id'=>'5','name'=>'权限管理','parent'=>'1','route'=>'/admin/mdm/assignment','order'=>'','data'=>null]);
	$this->insert('{{%menu}}',['id'=>'7','name'=>'设置','parent'=>'3','route'=>'/admin/settings/index','order'=>'','data'=>null]);
	$this->insert('{{%auth_item}}',['name'=>'/admin/*','type'=>'2','description'=>'','rule_name'=>null,'data'=>null,'created_at'=>'1452908366','updated_at'=>'1452908366']);
	$this->insert('{{%auth_item}}',['name'=>'/admin/mdm/*','type'=>'2','description'=>'','rule_name'=>null,'data'=>null,'created_at'=>'1452843164','updated_at'=>'1452843164']);
	$this->insert('{{%auth_item}}',['name'=>'admin','type'=>'1','description'=>'管理员','rule_name'=>null,'data'=>null,'created_at'=>'1452834145','updated_at'=>'1452845456']);
	$this->insert('{{%auth_item}}',['name'=>'permission','type'=>'2','description'=>'','rule_name'=>null,'data'=>null,'created_at'=>'1452834781','updated_at'=>'1452834781']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'admin','user_id'=>'1','created_at'=>'1441869434']);
	$this->insert('{{%auth_assignment}}',['item_name'=>'admin','user_id'=>'2','created_at'=>'1452843024']);
        $this->insert('{{%auth_assignment}}',['item_name'=>'backend','user_id'=>'3','created_at'=>'1441938087']);
	$this->insert('{{%auth_item_child}}',['parent'=>'admin','child'=>'/admin/*']);
	$this->insert('{{%auth_item_child}}',['parent'=>'admin','child'=>'permission']);
        $this->insert('{{%admin_user}}',['id'=>'1','username'=>'admin','password'=>'f59844e3b6b4763be58e3e4372242c58','salt'=>'DFSAIV13GO','email'=>'appadmin@social-touch.com','profile'=>'']);
	$this->insert('{{%app_role_user}}',['userid'=>'3','email'=>'admin@st.com','password'=>'6e9ee23bd11f039a1b5306a972cc5730','realname'=>'admin','jobtitle'=>'管理','icon'=>'','nickname'=>'','session'=>'65d80bde96b9ed313894c17801f9119f','biz_id'=>'1','ctime'=>'1422969432','utime'=>'1425347797','ltime'=>'1425347797','is_top'=>'n','is_ban'=>'n','is_del'=>'n']);
	$this->insert('{{%app_role_user}}',['userid'=>'1','email'=>'project@st.com','password'=>'e10adc3949ba59abbe56e057f20f883e','realname'=>'123456','jobtitle'=>'','icon'=>'','nickname'=>'','session'=>'','biz_id'=>'997','ctime'=>'1451309535','utime'=>'0','ltime'=>'0','is_top'=>'n','is_ban'=>'n','is_del'=>'y']);

        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        echo "m150923_023831_init cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
