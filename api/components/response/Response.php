<?php
/**
 * Created by PhpStorm.
 * User: prokhonenkov
 * Date: 04.03.19
 * Time: 9:39
 */

namespace app\api\components\response;

use phpDocumentor\Reflection\Types\Integer;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class Response
 * @package app\components\response
 */
class Response implements ResponseInterface
{
    /**
     * @var bool
     */
	private bool $status = false;
    /**
     * @var string|null
     */
	private ?string $message = null;
	/**
	 * @var bool
	 */
	private bool $debug = false;

    /**
     * @var array|null
     */
	private ?array $data = null;

	/**
	 * Response constructor.
	 * @param int $code
	 * @param string $message
	 * @param array $data
	 */
	public function __construct(int $code = 200, string $message = '', ?array $data = null)
	{
		$this->status = $code == 200 ? true : false;
		$this->message = $message;
		$this->data = $data;

		\Yii::$app->response->setStatusCode($code);
	}

	/**
	 * @param string $msg
	 */
	public function setMessage(string $msg): ResponseInterface
	{
		$this->message = $msg;
		return $this;
	}

	/**
	 * @param bool $status
	 */
	public function setStatus(bool $status): ResponseInterface
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}

	/**
	 * @return bool
	 */
	public function getStatus(): bool
	{
		return $this->status;
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData(array $data): ResponseInterface
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @return array
	 */
	private function makeResponse()
	{
		$response = [
			'success' => $this->status,
			'message' => $this->message,
		];

		if($this->data !== null) {
		    $response['data'] = $this->data;
        }

		if($this->debug) {
			$response['debug'] = $this->debug();
		}
		return $response;
	}

	public function send()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		\Yii::$app->response->data = $this->makeResponse();
		\Yii::$app->response->send();
		\Yii::$app->end();
	}

	/**
	 * @return array
	 */
	public function __toString(): string
	{
		return $this->send();
	}

	/**
	 * Need to add follow code to your config
	 *	'debug' => [
	 *		'class' => \yii\debug\Module::class, 'allowedIPs' => ['*'],
	 *		'dataPath' => "@app/runtime/debug",
	 *	]
	 *
	 * @return array
	 */
	private function debug()
	{
		$files = scandir(\Yii::getAlias('@app/runtime/debug'));

		$array = [];
		for($i=2;$i<count($files);$i++) {
			$file = \Yii::getAlias('@app/runtime/debug/') . $files[$i];
			$array[$file] = filemtime($file);
		}
		asort($array);
		$array = array_keys($array);

		$file = $array[count($array)-2];
		$cnt = unserialize(file_get_contents($file));

		foreach ($cnt as $key => $item) {
			try {
				$cnt[$key] = unserialize($item);
			} catch (\Exception $e) {}
		}

		$logs = [];
		foreach($cnt['log']['messages'] as $log) {
			if($log[2] == 'yii\db\Command::query' && strpos($log[0], 'kcu.') === false && strpos($log[0], 'SHOW FULL COLUMNS') === false) {
				$logs[] = $log;
			}
		}

		return [
			'summary' => ArrayHelper::merge($cnt['summary'], [
				'customSqlCount' => count($logs)
			]),
			'queries' => $logs
		];
	}

	/**
	 * @param bool $debug
	 */
	public function enableDebug(): self
	{
		$this->debug = true;

		return $this;
	}
}