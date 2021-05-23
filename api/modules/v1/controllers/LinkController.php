<?php

namespace app\api\modules\v1\controllers;

use app\api\components\response\Response;
use app\api\core\Controller;
use app\api\modules\v1\models\forms\LinkGetForm;
use app\api\modules\v1\models\forms\LinkSaveForm;

class LinkController extends Controller
{
    protected $noAuthActions = ['create', 'get-link'];

    public function actionCreate()
    {
        $form = new LinkSaveForm();
        if(!$form->load(\Yii::$app->request->post(), '')) {
            return (new Response(400, 'Bad Request'))->send();
        }

        if(!$form->save()) {
            return (new Response(422, 'Invalid params', $form->errors))->send();
        }

        return (new Response(200, 'Success', [
            'hash' => $form->getModel()->hash
        ]))->send();
    }

    public function actionGetLink()
    {
        $form = new LinkGetForm();

        if(!$form->load(\Yii::$app->request->get(), '')) {
            return (new Response(400, 'Bad Request'))->send();
        }

        if(!$form->validate()) {
            return (new Response(422, 'Invalid params', $form->errors))->send();
        }

        $link = $form->get();
        if(!$link) {
            return (new Response(404, 'Not Found'))->send();
        }

        return (new Response(200, 'Success', [
            'link' => $link->source,
            'count_visits' => $link->count_visits
        ]))->send();
    }

    public function verbs()
    {
        return [
            'create' => ['post'],
            'get-link' => ['get']
        ];
    }
}
