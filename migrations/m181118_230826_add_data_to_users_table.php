<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m181118_230826_add_data_to_users_table
 */
class m181118_230826_add_data_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->generateAuthKey();
        $user->setPassword(123456);
        $this->insert('{{%user}}', [
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

            $this->insert('{{%user}}', [
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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181118_230826_add_data_to_users_table cannot be reverted.\n";

        return false;
    }

}
