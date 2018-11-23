<?php

use yii\db\Migration;

/**
 * Handles the creation of table `posts`.
 */
class m181022_133201_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'author' => $this->integer(),
            'logo' => $this->string(),
            'title' => $this->string(),
            'description' => $this->text(),
            'body' => $this->text(),
            'status' => $this->smallInteger(2)->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-posts-categories_id',
            '{{%posts}}',
            'category_id'
        );

        $this->addForeignKey(
            'fk-posts-categories_id',
            '{{%posts}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-posts-author',
            '{{%posts}}',
            'author'
        );

        $this->addForeignKey(
            'fk-posts-author',
            '{{%posts}}',
            'author',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-posts-category_id',
            '{{%posts}}'
        );

        $this->dropIndex(
            'idx-posts-category_id',
            '{{%posts}}'
        );

        $this->dropForeignKey(
            'fk-posts-author',
            '{{%posts}}'
        );

        $this->dropIndex(
            'idx-posts-author',
            '{{%posts}}'
        );

        $this->dropTable('{{%posts}}');
    }
}
