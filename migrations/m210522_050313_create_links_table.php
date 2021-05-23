<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m210522_050313_create_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%links}}', [
            'id' => $this->primaryKey()->unsigned(),
            'hash' => $this->string()->unique()->notNull(),
            'source' => $this->string()->unique()->notNull(),
            'count_visits' => $this->integer()->unsigned()->defaultValue(0)->notNull()
        ]);

        $this->createIndex('idx-count_visits', 'links', 'count_visits');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%links}}');
    }
}
