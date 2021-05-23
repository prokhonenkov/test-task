<?php


namespace app\api\modules\v1\models\forms;


use yii\db\ActiveRecordInterface;

/**
 * Interface SaveFormInterface
 * @package app\api\modules\v1\models\forms
 */
interface SaveFormInterface
{
    /**
     * @return bool
     */
    public function save(): bool;

    /**
     * @return ActiveRecordInterface
     */
    public function getModel(): ActiveRecordInterface;
}