<?php

namespace app\api\modules\v1\controllers;


use app\api\components\response\Response;
use app\api\core\Controller;

/**
 * Default controller for the `Module` module
 */
class DefaultController extends Controller
{
    protected $noAuthActions = ['index'];

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return (new Response(200, '"MediaLine" Api v1', [
            'developed by' => 'Vitaliy Prokhonenkov <prokhonenkov@gmail.com>'
        ]))->send();
    }
}
