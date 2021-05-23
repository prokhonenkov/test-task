<?php


namespace app\api\modules\v1\models\forms;


use yii\db\ActiveRecordInterface;

interface GetFormInterface
{
    /**
     * @return ActiveRecordInterface|null
     */
    public function get(): ?ActiveRecordInterface;
}