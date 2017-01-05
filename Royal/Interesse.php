<?php
/**
 * Created by PhpStorm.
 * User: belinde
 * Date: 04/01/17
 * Time: 19.45
 */

namespace Royal;

/**
 * Class Interesse
 * @package Royal
 */
class Interesse {
	/**
	 * @var string
	 */
	private $mail;
	/**
	 * @var string
	 */
	private $name;
	/**
	 * @var string
	 */
	private $phone;
	/**
	 * @var \DateTime
	 */
	private $since;

	/**
	 * Interesse constructor.
	 *
	 * @param $name
	 * @param $mail
	 */
	public function __construct($name=null, $mail=null, $phone=null) {
		$this->name = trim($name);
		$this->phone = trim($phone);
		$this->mail = filter_var($mail, FILTER_VALIDATE_EMAIL);
		$this->since = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @return \DateTime
	 */
	public function getSince() {
		return $this->since;
	}

}