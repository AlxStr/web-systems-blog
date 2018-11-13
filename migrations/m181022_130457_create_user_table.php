<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181022_130457_create_user_table extends Migration
{

    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'role' => $this->string(16),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);


        //add admin
        $user = new User();
        $user->generateAuthKey();
        $user->setPassword(123456);
        $this->insert('user', [
            'username'=>'admin',
            'auth_key' => $user->auth_key,
            'password_hash' => $user->password_hash,
            'email' => "admin@email.com",
            'status' => 10,
            'role' => 'admin',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        unset($user);

        //add authors
        for($i=1; $i<5; $i++){
            $user = new User();
            $user->generateAuthKey();
            $user->setPassword(123456);

            $this->insert('user', [
                'username'=>'User' . $i,
                'auth_key' => $user->auth_key,
                'password_hash' => $user->password_hash,
                'email' => "user$i@email.com",
                'status' => 10,
                'role' => 'author',
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }

    public function down()
    {
        $this->dropTable('user');
    }

}
