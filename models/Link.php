<?php

namespace app\models;

use app\models\query\LinkQuery;
use Yii;

/**
 * This is the model class for table "links".
 *
 * @property int $id
 * @property string $hash
 * @property string $source
 * @property int $count_visits
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hash', 'source'], 'required'],
            [['count_visits'], 'integer'],
            [['hash', 'source'], 'string', 'max' => 255],
            [['hash'], 'unique'],
            [['source'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash',
            'source' => 'Source',
            'count_visits' => 'Count Visits',
        ];
    }

    public static function find()
    {
        return new LinkQuery(get_called_class());
    }
}
