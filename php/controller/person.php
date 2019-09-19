<?php
/*
*	Modification Log:
*	file: person.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/

// generic person class that employee and customer extend from. id is their database id. 
class Person
{
	protected $id;
	protected $firstName;
	protected $lastName;
	protected $number;
	protected $email;

	public function __construct($id, $firstName, $lastName, $email = null, $number = null, $pass = null)
	{
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->email = $email;
		$this->number = $number;
	}
// #region id
	public function getID()
	{
		return $this->id;
	}

	public function setID($value)
	{
		$this->id = $value;
	}

// #region fName
	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setFirstName($value)
	{
		$this->firstName = $value;
	}

// #region lName
	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName($value)
	{
		$this->lastName = $value;
	}

// #region phone
	public function getNumber()
	{
		return $this->number;
	}

	public function setNumber($value)
	{
		$this->number = $value;
	}
// #region email
	public function getEmail()
	{
		return $this->number;
	}

	public function setEmail($value)
	{
		$this->number = $value;
	}

// #region toString
	public function toString()
	{
		return "$this->id\n"
				."$this->firstName\n"
				."$this->lastName\n"
				."$this->email\n"
				."$this->number\n";
	}	
}

?>