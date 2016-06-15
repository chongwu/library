<?php

use yii\db\Migration;

class m160614_192818_init_library_db extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'category' => $this->string()->notNull()
        ], $tableOptions);

        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'author' => $this->string()->notNull(),
            'category_id' => $this->integer(11)->notNull(),
            'photo' => $this->string()->notNull(),
            'file' => $this->string()->notNull(),
            'description' => $this->text()->notNull()
        ], $tableOptions);
        $this->addForeignKey('fk_book_category', 'book', 'category_id', 'category', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('ix_book_title', 'book', 'title', true);
    }

    public function safeDown()
    {
        $this->dropTable('book');
        $this->dropTable('category');
    }
}
