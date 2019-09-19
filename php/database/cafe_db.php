<?php

/*
*	Modification Log:
*	file: cafe_db.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/
// static class to handle connecting to the database.
class Database
{
	private static $_dsn = 'mysql:host=localhost;dbname=cafe2';
	private static $_username = '';
	// private static $_username = 'root';
	// private static $_password = 'Pa$$w0rd';
	private static $_password = '';
	private static $_db;

	// optional constructor to create a new custom database connection
	public function __construct($dsn = 'mysql:host=localhost;dbname=cafe2', $username = 'root', $password = 'Pa$$w0rd')
	{
		self::$_dsn =$dsn;
		self::$_username = $username;
		self::$_password = $password;
		if (!isset(self::$db))
		{
			try
			{
				self::$_db = new PDO(self::$_dsn, self::$_username, self::$_password);
			}
			catch (PDOException $e)
			{
				self::$_db = null;
				/* $error_message = $e->getMessage();
				echo $error_message;
				exit(); */
			}
		}
	}
	// returns the database handle if there is one, and creates one if there isn't. 
	public static function getDB()
	{
		if (!isset(self::$db))
		{
			try
			{
				self::$_db = new PDO(self::$_dsn, self::$_username, self::$_password);
			}
			catch (PDOException $e)
			{
				/* $error_message = $e->getMessage();
				echo $error_message;
				exit(); */
				self::$_db = null;
			}
		}
		return self::$_db;
	}
}

// static class to hold any database calls for employees
class EmployeeDB
{
	private function __construct(){}
	public static function getAllEmployees()
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT * FROM employees
				  ORDER BY EID';
			$result = $db->query($query);
			$emp = array();
			foreach ($result as $row)
			{
				$emp = new Employee($row['EID'], $row['firstName'], $row['lastName'],
				$row['email'], $row['phone'], $row['pass']);
				$employees[] = $emp;
			}
			return $employees;
		}
		return null;
	}

	public static function getEmployee($fName, $lName, $email)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT EID, pass, phone'
					.' FROM employees'
					.' WHERE firstName = :fName AND lastName = :lName AND email = :email'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->bindValue(':fName', $fName);
			$statement->bindValue(':lName', $lName);
			$statement->bindValue(':email', $email);
			$statement->execute();    
			$row = $statement->fetch();
			$statement->closeCursor();

			if($row == null)
			{
				return null;
			}
			
			return new Employee($row['EID'], $fName, $lName, $email, $row['phone'], $row['pass']);
		}
		return null;
	}

	public static function getEmployeeByEID($eid)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT *'
					.' FROM employees'
					.' WHERE EID = :eid'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->bindValue(':eid', $eid);
			$statement->execute();    
			$row = $statement->fetch();
			$statement->closeCursor();

			if($row == null)
			{
				return null;
			}
			
			return new Employee($row['EID'], $row['firstName'], $row['lastName'],
				$row['email'], $row['phone'], $row['pass']);
		}
		return null;
	}
	
	// check for the existence of a employee by searching for their name and email.
	// If EID is found then the new values are passed through and an employee object is returned
	// otherwise it will attempt to add it and return an employee object.
	public static function setEmployee($fName, $lName, $email, $phone = null)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$emp = get_employee($fName, $lName, $email);
			if($emp == null)
			{
				$query = 'INSERT INTO employees'
						.' (firstName, lastName, email, phone)'
						.' VALUES (:fName, :lName, :email, :phone)';
				$statement = $db->prepare($query);
				$statement->bindValue(':fName', $fName);
				$statement->bindValue(':lName', $lName);
				$statement->bindValue(':email', $email);
				$statement->bindValue(':phone', $phone);
				$statement->execute();
				$statement->closeCursor();

				$emp = get_employee($fName, $lName, $email);
			}
			else
			{
				$query = 'UPDATE employees'
						.' SET firstName = :fName, lastName = :lName, email = :email, phone = :phone'
						.' WHERE EID = :eid'
						.' LIMIT 1';
				$statement = $db->prepare($query);
				$statement->bindValue(':fName', $fName);
				$statement->bindValue(':lName', $lName);
				$statement->bindValue(':email', $email);
				$statement->bindValue(':phone', $phone);
				$statement->bindValue(':eid', $emp->getID());
				$statement->execute();
				$statement->closeCursor();
			}
			return $emp;
		}
		return null;
	}
	
	// removes an employee from the employees table
	// returns whether the statement was executed successfully
	public static function removeEmployee($eid)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'DELETE FROM employees'
					.' WHERE EID = :eid'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->bindValue(':eid', $eid);
			$ret = $statement->execute();
			$statement->closeCursor();
			return $ret;
		}
		return null;
	}
	
	// function to return the data for a random employee from the employees database
	// returns an employee object using data from the first row.
	public static function getRandomEmployee()
	{

		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT *'
					.' FROM employees'
					.' ORDER BY RAND()'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->execute();    
			$row = $statement->fetch();
			$statement->closeCursor();

			return new Employee($row['EID'], $row['firstName'], $row['lastName'],
			$row['email'], $row['phone'], $row['pass']);
		}
		return null;
	}

	// checks the employee id and guid against the database. returns whether there is an employee with that information or not.
	public static function validateCredentials($eid, $guid)
	{
		$db = Database::getDB();
		if($db !== null)
		{

			$query = 'SELECT COUNT(*)'
			.' FROM employees'
			.' WHERE EID = :eid'
			.' AND pass = :guid';
			$statement = $db->prepare($query);
			$statement->bindValue(':eid', $eid);
			$statement->bindValue(':guid', $guid);
			$statement->execute();    
			$row = $statement->fetch();
			$statement->closeCursor();
			
			$intVal = intval($row[0]);
			return $intVal !== 0;
		}
		return null;
	}
}

// static class to hold any functions relating to the comments table of the database
class CommentDB
{
	private function __construct(){}

	// returns all comments for the given employee id
	public static function getCommentsByEID($eid)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT DISTINCT'
			.' contactform.ID,'
			.' contactform.CID,'
			.' contactform.EID,'
			.' contactform.reason,'
			.' contactform.rating,'
			.' contactform.comment,'
			.' customers.firstName,'
			.' customers.lastName'
			.' FROM contactform'
			.' JOIN customers ON customers.CID = contactform.CID '
			.' WHERE EID = :eid';
			$statement = $db->prepare($query);
			$statement->bindValue(':eid', $eid);
			$statement->execute();    
			$comments = $statement->fetchall();
			
			$statement->closeCursor();

			return $comments;
		}
		return null;
	}

	// Deletes a comment by it's row id
	// returns the success of the statement
	public static function deleteCommentByID($id)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 
			"DELETE FROM
			contactform
			where ID = :id
			LIMIT 1";
			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$ret = $statement->execute();    
			
			$statement->closeCursor();

			return $ret;
		}
		return null;
	}

	// Updates a customer comment by the comment id.
	// returns the success of the statement
	public static function updateCommentByID($id, $message)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 
			"UPDATE
			contactform
			SET comment = :message
			where ID = :id
			LIMIT 1";
			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->bindValue(':message', $message);
			$ret = $statement->execute();    
			
			$statement->closeCursor();
			return $ret;
		}
		return null;
	}
	
	// add a customer comment and the employee assigned to that customer.
	public static function addComment($cid, $eid, $reason, $rating = 3, $comment = "")
	{
		$db = Database::getDB();
		if($db !== null)
		{

			$query = 'INSERT INTO contactform'
					.' (CID, EID, reason, rating, comment)'
					.' VALUES'
					.' (:cid, :eid, :reason, :rating, :comment)';
			$statement = $db->prepare($query);
			$statement->bindValue(':cid', $cid);
			$statement->bindValue(':eid', $eid);
			$statement->bindValue(':reason', $reason);;
			$statement->bindValue(':rating', $rating);;
			$statement->bindValue(':comment', $comment);;
			$statement->execute();    
			$statement->closeCursor();
		}
	}
}

// static class for any functions relating to the customer database
class CustomerDB
{
	private function __construct(){}
	// returns a customer object from the database
	public static function getCustomer($fName, $lName, $email)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'SELECT *'
					.' FROM customers'
					.' WHERE firstName = :fName AND lastName = :lName AND email = :email'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->bindValue(':fName', $fName);
			$statement->bindValue(':lName', $lName);
			$statement->bindValue(':email', $email);;
			$statement->execute();    
			$row = $statement->fetch();
			$statement->closeCursor();
			
			if($row == null)
			{
				return null;
			}
			return new Customer($row['CID'], $row['firstName'], 
				$row['lastName'], $row['email'], $row['phone'], $row['newsletter']);
		}
		return null;
	}

	// checks if the customer exists and updates their info if it does. otherwise adds a new customer to the database.
	// returns customer object.
	public static function setCustomer($fName, $lName, $email, $newsletter = "N", $phone = null)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$customer = self::getCustomer($fName, $lName, $email);
			if($customer == null)
			{
				$query = 'INSERT INTO customers'
						.' (firstName, lastName, email, newsletter, phone)'
						.' VALUES (:fName, :lName, :email, :newsletter, :phone)';
				$statement = $db->prepare($query);
				$statement->bindValue(':fName', $fName);
				$statement->bindValue(':lName', $lName);
				$statement->bindValue(':email', $email);
				$statement->bindValue(':newsletter', $newsletter);
				$statement->bindValue(':phone', $phone);
				$statement->execute();
				$statement->closeCursor();

				$customer = self::getCustomer($fName, $lName, $email);
			}
			else
			{
				$query = 'UPDATE customers'
						.' SET firstName = :fName, lastName = :lName, email = :email, newsletter = :newsletter, phone = :phone'
						.' WHERE CID = :cid'
						.' LIMIT 1';
				$statement = $db->prepare($query);
				$statement->bindValue(':fName', $fName);
				$statement->bindValue(':lName', $lName);
				$statement->bindValue(':email', $email);
				$statement->bindValue(':newsletter', $newsletter);
				$statement->bindValue(':phone', $phone);
				$statement->bindValue(':cid', $customer.getID());
				$statement->execute();
				$statement->closeCursor();
			}
			return $customer;
		}
		return null;
	}

	// removes a customer from the customers table
	public static function removeCustomer($cid)
	{
		$db = Database::getDB();
		if($db !== null)
		{
			$query = 'DELETE FROM customers'
					.' WHERE CID = :cid'
					.' LIMIT 1';
			$statement = $db->prepare($query);
			$statement->bindValue(':cid', $cid);
			$ret = $statement->execute();
			$statement->closeCursor();
			return $ret;
		}
		return null;
	}
}

?>