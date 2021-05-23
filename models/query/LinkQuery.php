<?php

namespace app\models\query;


use yii\db\ActiveQuery;

class LinkQuery extends ActiveQuery
{
    public function byHash(string $hash)
    {
        return $this->andWhere([
            'hash' => $hash
        ]);
    }
}