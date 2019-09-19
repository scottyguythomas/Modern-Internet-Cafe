<?php
	/*
	*	Modification Log:
	*	file: forms.php
	*	author : Scott Thomas
	*	last modified: 2019-9-13 10:50 AM
	*/
	/* page that handles any form data to be sent to the database */
	require_once('database/cafe_db.php');
	require_once('controller/customer.php');
	require_once('controller/employee.php');
	require_once('controller/stocks.php');

	// determine which form sent the data
	$contact = filter_input(INPUT_POST, "contact_submit");
	$newsubmit = filter_input(INPUT_POST, "news_submit");
	
	if($contact !== null)
	{
		// get any variables that we might need
		$firstName = filter_input(INPUT_POST, "contact_first_name");
		$lastName = filter_input(INPUT_POST, "contact_last_name");
		$email = filter_input(INPUT_POST, "contact_email");
		$phoneNumber = filter_input(INPUT_POST, "contact_phone");
		$reason = filter_input(INPUT_POST, "contact_reason");
		$satisfaction = filter_input(INPUT_POST, "contact_sat");
		$experience = filter_input(INPUT_POST, "cust_exp");

		// This is more of a safety check. We validate their inputs with javascript but they can still get around that if they want.
		if($firstName == null || $lastName  == null || $email == null || $reason == null)
		{
			alert("Please ensure that your name, email and reason are filled and valid");
			script("document.location.replace('../contact.html')");
			exit();
		}

		// get a customer object from their name and email
		$cid = CustomerDB::getCustomer($firstName, $lastName, $email);

		if($cid == null)
		{
			// add the customer to the database if they aren't there, and make sure they don't get newsletters.
			$cid = CustomerDB::setCustomer($firstName, $lastName, $email, "N", $phoneNumber);
		}

		// assign a random employee to that customer
		$employee = EmployeeDB::getRandomEmployee();

		if($employee !== null)
		{
			// attempt to save the information to the database
			CommentDB::addComment($cid->getID(), $employee->getID(), $reason, $satisfaction, $experience);

			alert("Thank you for your feedback! " . $employee->getFirstName() . " " . $employee->getLastName() ." will contact you shortly.");
		}
		else
		{
			alert("There was an error while processing your data, please try again later.");
		}
		// return to the contact page
		script("document.location.replace('../contact.html')");
	}
	else if($newsubmit !== null)
	{
		// get any variables that we might need
		$firstName = filter_input(INPUT_POST, "news_first_name");
		$lastName = filter_input(INPUT_POST, "news_last_name");
		$email = filter_input(INPUT_POST, "news_email");
		$phoneNumber = filter_input(INPUT_POST, "contact_phone");
		$mobile = filter_input(INPUT_POST, "news_mobile");
		// This is more of a safety check. We validate their inputs with javascript but they can still get around that if they want.
		if($firstName == null || $lastName  == null || $email == null)
		{
			alert("Please ensure that your name and email are filled and valid");
			script("document.location.replace('../newsletter.html')");
			exit();
		}
		if(isset($mobile))
		{
			// newsletter is saved as an enum in the database.
			// "N", "Y", "YM" for no, yes, yes + mobile
			$mobile = "YM";
		}
		else
		{
			// They've subscribed to the newsletter at this point so we just set it to yes for them.
			$mobile = "Y";
		}
		// attempt to add the customer to the database
		if(CustomerDB::setCustomer($firstName, $lastName, $email, $mobile, $phoneNumber) !== null)
		{
  			alert("Thanks for subscribing to our newsletter!");
		}
		else
		{
  			alert("There was an error while processing your data, please try again later.");
		}
		// return to the original page
		script("document.location.replace('../newsletter.html')");
	}
?>