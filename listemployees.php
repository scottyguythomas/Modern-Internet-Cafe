<?php

class Database
{
	private static $dsn = 'mysql:host=localhost;dbname=cafe2';
	private static $username = 'root';
	private static $password = 'Pa$$w0rd';
	private static $db;

	private function __construct()
	{ }

	public static function getDB()
	{
		if (!isset(self::$db)) {
			try {
				self::$db = new PDO(
					self::$dsn,
					self::$username,
					self::$password
				);
			} catch (PDOException $e) {
				$error_message = $e->getMessage();
				// include('../errors/database_error.php');
				exit();
			}
		}
		return self::$db;
	}
}

class Employee
{
	private $id;
	private $firstName;
	private $lastName;
	private $number;

	public function __construct($id, $firstName, $lastName)
	{
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->number = null;
	}

	public function getID()
	{
		return $this->id;
	}

	public function setID($value)
	{
		$this->id = $value;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setFirstName($value)
	{
		$this->firstName = $value;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function setLastName($value)
	{
		$this->lastName = $value;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function setNumber($value)
	{
		$this->number = $value;
	}
}

class EmployeeDB
{
	public function getEmployees()
	{
		$db = Database::getDB();
		$query = 'SELECT * FROM employees
                  ORDER BY EID';
		$result = $db->query($query);
		$emp = array();
		foreach ($result as $row) {
			$emp = new Employee($row['EID'], $row['firstName'], $row['lastName']);
			$emp->setNumber($row['phone']);
			$employees[] = $emp;
		}
		return $employees;
	}

	public function buildTable()
	{
		$db = Database::getDB();
		$emp = SELF::getEmployees();

		$table = "<table class='container'>";
		$table .= "<tr class='hvr-fade'>";
		foreach ($emp as $e) {
			$row = "";
			// sets the row to be a clickable object that fires a js function to tell the server to display the comments for that employee.
			$row .= "<tr class='hvr-fade'>";

			$row .=  "<th>";
			$row .=  htmlspecialchars($e->getID());
			$row .=  "</th>";

			$row .=  "<td>";
			$row .=  htmlspecialchars($e->getFirstName()) . ' ' . htmlspecialchars($e->getLastName());
			$row .=  "</td>";

			$row .=  "<td>";
			$row .=  htmlspecialchars($e->getNumber());
			$row .=  "</td>";
			$row .=  "</tr>";
			$table .= $row . "\n";
		}
		$table .= "</table>";

		return $table;
	}
}

?>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Modern Internet Café</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/base.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/admin.css" />
	<link href="https://fonts.googleapis.com/css?family=Raleway|Roboto" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Lato:300i,400" rel="stylesheet">
	<meta name="description" content="Boises best coffee shop" />
	<script src="js/admin.js"></script>
</head>

<body>
	<!-- div is needed so that mobile devices respect the overflow-x -->
	<div class="wrapper">
		<nav class="horizontalnav">
			<ul>
				<a href="index.html">
					<li>Home</li>
				</a>
				<a href="newsletter.html">
					<li>Newsletter</li>
				</a>
				<a href="contact.html">
					<li>Contact</li>
				</a>
				<a href="admin.php">
					<li>Login</li>
				</a>
			</ul>
			<div class="rainbow"></div>
		</nav>
		<div class="overlay">
			<section>
				<h2>Employee List</h2>
					<?php
					echo EmployeeDB::buildTable();
					?>
			</section>
			<footer>
				<div class="row">
					<a href="tel:+12085554567">
						<img src="images/phone.png" alt="phone" />
					</a>
				</div>
				<div class="row">
					<a href="mailto:website@example.com">
						<img src="images/letter.png" alt="mail" />
					</a>
				</div>
				<div class="row">
					<a href="https://wwww.facebook.com/" target="_blank">
						<img src="images/facebook.png" alt="facebook" />
					</a>
				</div>
				<div class="row">
					<a href="https://www.twitter.com/" target="_blank">
						<img src="images/twitter.png" alt="twitter" />
					</a>
				</div>
				<br />
				<a id="icon-pack" href="https://icons8.com" target="_blank">Icon pack by Icons8</a>
			</footer>
		</div>
	</div>
</body>

</html>