<?php
/*
*	Modification Log:
*	file: admin.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/

require_once('controller/customer.php');
require_once('controller/employee.php');
require_once('database/cafe_db.php');
require_once('view/builders.php');
require_once('controller/stocks.php');
require_once('controller/actions.php');

// start the session to track login information
session_set_cookie_params(0, '/');
session_start();

// get the intended page from the action sent
$action = filter_input(INPUT_POST, "action");
$table = handleActions($action);
$eid = filter_input(INPUT_POST, "eid", FILTER_SANITIZE_NUMBER_INT);

include('view/header.php');
includeScript('../js/admin.js');
?>

<div class="overlay">
	<section class='multiInputs'>
		<?php Builder::buildHeader($action);?>

		<div>
			<?php
			 	echo $table; 
			?>
			<?php
			if ($action === 'view_comments')
			{
				// add an edit comment box if we're viewing a comment
				echo Builder::buildCommentEditBox($eid);
			}
			?>
			
		</div>
	</section>
</div>
<form id='adminForm' action="admin.php" method="POST">
	<input type="hidden" id="adminAction" name="action" />
	<input type="hidden" id="adminID" name="eid" />
	<input type="hidden" id="commentID" name="id" />
	<input type="hidden" id="adminComment" name="message" />
</form>

<?php include('view/footer.php'); ?>