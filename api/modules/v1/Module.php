<?php

namespace app\api\modules\v1;

/**
 * Module module definition class
 */
class Module extends \yii\base\Module
{
    const VERSION = 'v1';
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
