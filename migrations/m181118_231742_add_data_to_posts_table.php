<?php

use yii\db\Migration;

/**
 * Class m181118_231742_add_data_to_posts_table
 */
class m181118_231742_add_data_to_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        for($i=1; $i<=200; $i++){
            $this->insert('{{%posts}}', [
                'category_id' => rand(1, 5),
                'author' => rand(2, 5),
                'logo' => '',
                'title' => "Post - $i",
                'description' => "description to post - $i",
                'body' => "Body text here, post - $i",
                'status' => 0,
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
        echo "m181118_231742_add_data_to_posts_table cannot be reverted.\n";
        for($i=200; $i>=1; $i--){
            $this->delete('{{%posts}}', ['id' => $i]);
        }


        return true;
    }

}
