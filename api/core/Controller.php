<?php
/**
 * Created by Vitaliy Prokhonenkov <prokhonenkov@gmail.com>
 * Date 19.10.2019
 * Time 16:28
 */

namespace app\api\core;


use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\rest\OptionsAction;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class Controller extends \yii\rest\Controller
{
	/**
	 * Action к которым разрешено гостевой доступ
	 * @var array
	 */
	protected $noAuthActions = [];

	/**
	 * ID action'ов, к которым доступ не обязателен.
	 * @see AuthMethod::$optional
	 *
	 * @var array
	 */
	protected $optionalAuthActions = [];

	/**
	 * @var User авторизованный пользователь
	 */
	protected $user;

	public function init()
	{
		$this->noAuthActions = ArrayHelper::merge($this->noAuthActions, ['options']);

		parent::init();
	}

	/**
	 * @inheritdoc
	 *
	 * Авторизация по заголовку Authentication: Bearer USER_AUTH_TOKEN
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		unset($behaviors['rateLimiter']);

		$behaviors['authenticator'] = [
			'class' => CompositeAuth::className(),
			'except' => $this->noAuthActions,
			'optional' => $this->optionalAuthActions,
			'authMethods' => [
				HttpBearerAuth::className(),
			],
		];

		$behaviors['corsFilter'] = [
			'class' => \yii\filters\Cors::className(),
			'cors' => [
				'Access-Control-Max-Age' => 3600,
				//TODO: удалить потом 4 строки ниже
				'Origin' => ['*'],
				'Access-Control-Allow-Origin' => '*',
				'Access-Control-Allow-Credentials' => false,
				'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
				//--------------
				//'Origin' => \Yii::$app->params['cors_origin_urls'],
				//'Access-Control-Allow-Credentials' => true,
				'Access-Control-Request-Method' => ['POST', 'PUT', 'GET', 'OPTIONS'],
				'Access-Control-Request-Headers' => [
					'Accept',
					'Authorization',
					'X-Requested-With',
					'Content-Type',
					'mobile_app'
				],
			]
		];

		$behaviors['httpCache'] = [
			'class' => 'yii\filters\HttpCache',
			'only' => ['index'],
			'cacheControlHeader' => 'no-cache',
			'sessionCacheLimiter' => 'no-cache',
			'lastModified' => function () {
				return time();
			}
		];

		$behaviors['contentNegotiator'] = [
			'class' => ContentNegotiator::className(),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
				'application/xml' => Response::FORMAT_XML,
				'text/xml' => Response::FORMAT_XML,
			],
		];

		$behaviors['accesscontrol'] = [
			'class' => AccessControl::className(),
			'except' => ArrayHelper::merge(['error', 'login'], $this->noAuthActions),
			'rules' => [
				[
					'allow' => true,
					'roles' => ['@'],
				],
				[
					'allow' => true,
					'roles' => ['?'],
					'actions' => $this->optionalAuthActions
				]
			]
		];

		$behaviors['verbs'] = [
			'class' => \yii\filters\VerbFilter::className(),
			'actions' => [
				'index'  => ['GET'],
				'view'   => ['GET'],
				'create' => ['GET', 'POST'],
				'update' => ['GET', 'PUT', 'POST'],
				'delete' => ['POST', 'DELETE'],
			],
		];

		return $behaviors;
	}

	public function actions()
	{
		return [
			'options' => OptionsAction::class,
		];
	}
}