<?php

use yii\db\Migration;

/**
 * Class m181118_230817_add_data_to_categories_table
 */
class m181118_230817_add_data_to_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        for($i=1; $i<=5; $i++){
            $this->insert('{{%categories}}', [
                'title'=>'Category' . $i,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181118_230817_add_data_to_categories_table cannot be reverted.\n";

        return false;
    }
}
