<?php
	/*
	*	Modification Log:
	*	file: actions.php
	*	author : Scott Thomas
	*	last modified: 2019-9-13 10:50 AM
	*/
	/* php file to handle receiving actions and determine the appropriate data to send back */

	// main entry point for the file
	// validates admin credentials before allowing them to view any other data
	function handleActions($action)
	{
		if(!verifyLogin())
		{
			if(Database::getDB() == null)
			{
				return Builder::buildLoginForm(true);
				// alert("There was an error while processing your data, please try again later.");
			}
			else
			{
				return Builder::buildLoginForm();
			}
		}
		else
		{
			// list employees 
			if (($action === 'list') || ($action === 'check_login') || ($action == 'verify_login') || (!isset($action)))
			{
				return listEmployees();
			}
			else if ($action === 'view_comments')
			{
				return viewComments();
			}
			else if ($action === 'delete_comment')
			{
				return deleteComment();
			}
			else if ($action === 'update_comment')
			{
				return updateComment();
			}
		}
	}

	// checks if the session is set and their credentials are valid.
	function verifyLogin()
	{
		if (isset($_SESSION['username']) && isset($_SESSION['password']) && EmployeeDB::validateCredentials($_SESSION['username'], $_SESSION['password']))
		{
			return true;
		}
		// if they aren't valid or aren't set then double check them with the current data.
		else
		{
			$_SESSION['username'] = filter_input(INPUT_POST, 'username');
			$_SESSION['password'] = filter_input(INPUT_POST, 'password');
			if(EmployeeDB::validateCredentials($_SESSION['username'], $_SESSION['password']))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	function listEmployees()
	{
		$emp = EmployeeDB::getAllEmployees();
		return Builder::buildEmployeeTable($emp);
	}

	function viewComments()
	{
		$eid = filter_input(INPUT_POST, "eid", FILTER_SANITIZE_NUMBER_INT);
		if ($eid != null)
		{
			$comments = CommentDB::getCommentsByEID($eid);
			return Builder::buildCommentsTable($comments);
		}
	}

	function deleteComment()
	{
		$eid = filter_input(INPUT_POST, "eid", FILTER_SANITIZE_NUMBER_INT);
		$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
		if ($eid != null && $id != null)
		{
			CommentDB::deleteCommentByID($id);
			return viewComments();
		}
	}
	
	function updateComment()
	{
		$eid = filter_input(INPUT_POST, "eid", FILTER_SANITIZE_NUMBER_INT);
		$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
		$message = filter_input(INPUT_POST, "message");
		if ($eid != null && $id != null)
		{
			CommentDB::updateCommentByID($id, $message);
			return viewComments();
		}
	}
?>