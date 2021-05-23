<?php
/**
 * Created by PhpStorm.
 * User: prokhonenkov
 * Date: 04.03.19
 * Time: 9:34
 */

namespace app\api\components\response;

/**
 * Interface ResponseInterface
 * @package app\components\response
 */
interface ResponseInterface
{
	/**
	 * @param string $msg
	 * @return ResponseInterface
	 */
	public function setMessage(string $msg): self ;

	/**
	 * @return string
	 */
	public function getMessage(): string ;

	/**
	 * @param bool $status
	 * @return ResponseInterface
	 */
	public function setStatus(bool $status): self ;

	/**
	 * @return bool
	 */
	public function getStatus(): bool ;

	/**
	 * @return array
	 */
	public function getData(): array ;

	/**
	 * @param array $data
	 * @return ResponseInterface
	 */
	public function setData(array $data): self ;
}