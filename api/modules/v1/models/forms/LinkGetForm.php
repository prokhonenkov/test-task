<?php


namespace app\api\modules\v1\models\forms;


use app\api\modules\v1\models\Link;
use yii\base\Model;
use yii\db\ActiveRecordInterface;

class LinkGetForm extends Model implements GetFormInterface
{
    public string $hash;

    public function rules()
    {
        return [
            [['hash'], 'required'],
            [['hash'], 'string', 'max' => 6, 'min' => 4],
        ];
    }

    public function get(): ?Link
    {
        $link = Link::find()->byHash($this->hash)->one();

        return $link ?? null;
    }
}