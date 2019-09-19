<?php
/*
*	Modification Log:
*	file: customer.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/
require_once ('person.php');

class Customer extends Person
{
	private $newsLetter;

	public function __construct($id, $firstName, $lastName, $email, $number = null, $newsLetter = null)
	{
		parent::__construct($id, $firstName, $lastName, $email, $number);
		$this->newsLetter = $newsLetter;
	}

// #region newsletter
	public function getNewsLetter()
	{
		return $this->number;
	}

	public function setNewsLetter($value)
	{
		$this->newsLetter = $value;
	}
}
?>