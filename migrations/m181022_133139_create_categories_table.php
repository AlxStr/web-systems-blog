<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m181022_133139_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);


        //insert categories
        for($i=1; $i<=5; $i++){
            $this->insert('categories', [
                'title'=>'Category' . $i,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('categories');
    }
}
