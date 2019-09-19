<?php
/*
*	Modification Log:
*	file: employee.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/
require_once ('person.php');

class Employee extends Person
{
	private $pass;

	public function __construct($id, $firstName, $lastName, $email, $number = null, $pass = null)
	{
		parent::__construct($id, $firstName, $lastName, $email, $number);
		$this->pass = $pass;
	}

// #region pass
	public function getPassword()
	{
		return $this->pass;
	}

	public function setPassword($value)
	{
		$this->pass = $value;
	}

	public function toString()
	{
		return parent::toString() . "\n$this->pass\n";
	}
}

?>