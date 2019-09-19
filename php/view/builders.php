<?php
/*
*	Modification Log:
*	file: builders.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/

// class for functions that 'build' html based on parameters
Class Builder
{
	// no constructor, static class
	private function __construct(){}

	// builds the list of employees and returns the table as a string
	public static function buildEmployeeTable()
	{
		$emp = EmployeeDB::getAllEmployees();

		$table = "<table class='container'>";
		// $table .= "<tr class='hvr-fade'>";
		if($emp !== null)
		{

			foreach ($emp as $e) {
				$row = "";
				// sets the row to be a clickable object that fires a js function to tell the server to display the comments for that employee.
				$row .= "<tr onclick=viewComments(" . htmlspecialchars($e->getID()) . ") class='hvr-fade'>";
				
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
		}
		$table .= "</table>";

		return $table;
	}
		
	// formats a table from the comments database results
	// returns a string containing the formatted table for customer comments
	public static function buildCommentsTable($comments)
	{
		// css doesn't fully accept our padding for these cells. so we have to inline it.
		
		$table = "<table class='container'>";
		$table .= "<tr class='hvr-fade'>";
		$table .= "<tbody>";
		
		$table .= "<th >";
		$table .= "Name";
		$table .= "</th>";
		
		$cell = "<td style='padding:5px' class='' >";
		$table .= $cell;
		$table .= "Contact Reason";
		$table .= "</td>";

		$table .= $cell;
		$table .= "Rating";
		$table .= "</td>";

		$table .= $cell;
		$table .= "Comments";
		$table .= "</td>";

		$table .= $cell;
		$table .= "Delete Comment";
		$table .= "</td>";
		$table .= "</tr>";

		foreach ($comments as $c) {
			$row = "";
			// add a js click function to add a css class so that it displays as 'selected' once clicked
			$row .= "<tr onclick='setActive(this, " . htmlspecialchars($c['ID']) . ", " . htmlspecialchars($c['EID']) . ")' class='hvr-fade' id='" . htmlspecialchars($c['ID']) . "'>";

			$row .= "<th>";
			$row .= htmlspecialchars($c['firstName']) . ' ' . htmlspecialchars($c['lastName']);
			$row .= "</th>";

			$row .= $cell;
			$row .= htmlspecialchars($c['reason']);
			$row .= "</td>";

			$row .= $cell;
			$row .= htmlspecialchars($c['rating']);
			$row .= "</td>";

			$row .= $cell;
			$row .= htmlspecialchars($c['comment']);
			$row .= "</td>";

			// creates double click to delete functionality to the delete cell.
			$row .= "<td class='hvr-fade-red' ondblclick='deleteComment(" . htmlspecialchars($c['ID']) . ", " . htmlspecialchars($c['EID']) . ")'>";
			$row .= "Delete Me!";
			$row .= "</td>";

			$row .= "</tr>";
			$table .= $row;
		}
		$table .= "</table>";
		return $table;
	}

	// Returns a dummy form that will let admins update comments.
	public static function buildCommentEditBox($eid)
	{
		return "<form>
					<fieldset style='margin: 5%'>
						<legend style='margin-bottom: 5%'>Edit Comment</legend>
						<label for='customerExp'>Update Comment:</label>
						<textarea name='adminExp' id='customerExp' rows='4' cols='50'></textarea>
					</fieldset>
					<div>
					<input id='updateCommentButton' class='submitButton' onclick='updateComment(" . htmlspecialchars($eid) . ")' type='button' value='Submit' name='contact_submit' />
					</div>
				</form>";
	}

	// builds a form to display if the user hasn't validated their credentials
	public static function buildLoginForm($disabled = false)
	{
		$ret =  "<form id='loginForm' action='admin.php' method='POST'>
					<fieldset style='margin: 5%'>
						<legend style='margin-bottom: 5%'>Enter your login credentials</legend>
						<div>
							<label for='username'>EMPLOYEE ID:</label>
							<br />
							<input type='text' id='loginUsername' name='username' rows='1' cols='64'></textarea>
						</div>
						<div>
							<label for='password'>PASSWORD:</label>
							<br />
							<input type='text' id='loginPassword' name='password' rows='1' cols='64'></textarea>
						</div>

						<input type='hidden' id='loginAction' name='action' value='verify_login' />
					</fieldset>
					<div>";
					if($disabled)
					{
						$ret .= "<input type='submit' class='hvr-fade-red' value='Cannot verify login credentials' name='loginSubmit' disabled/>";
					}
					else
					{
						$ret .= "<input type='submit' value='Submit' name='loginSubmit' />";
					}
					$ret.="</div>
				</form>";
		return $ret;
	}

	// sets the h2 tag depending on the action being taken
	public static function buildHeader($action)
	{
		if(!verifyLogin())
		{
			echo "<h2>Admin Login Page</h2>";
		}
		else if (($action === 'list') || ($action === 'check_login') || ($action == 'verify_login') || (!isset($action)))
		{
			echo "<h2>Select Employee</h2>";
		}
		else
		{
			echo "<h2>Select Comment</h2>";
		}
	}
}

?>