<?php

use yii\db\Migration;

/**
 * Class m190616_110401_create_table_model_comment
 */
class m190616_110401_create_table_model_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_comment%}}', [
            'id' => $this->primaryKey(),
            'entity' => $this->string()->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'comment' => $this->text()->notNull(),
            'created_at' => $this->timestamp(),
            'created_by' => $this->integer()->notNull(),
        ]);

        /*$this->addForeignKey('fk-user-comments', '{{%model_comment%}}', 'created_by',
            '{{%user%}}', 'id');*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%model_comment%}}');
    }
}
