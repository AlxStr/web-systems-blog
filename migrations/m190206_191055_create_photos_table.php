<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photos`.
 */
class m190206_191055_create_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photos}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
        ]);

        $this->createIndex('{{%idx-photos-post_id}}', '{{%photos}}', 'post_id');

        $this->addForeignKey('{{%fk-photos-post_id}}', '{{%photos}}', 'post_id', '{{%posts}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%photos}}');
    }
}
