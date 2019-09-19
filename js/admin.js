/*
*	Modification Log:
*	file: admin.js
*	author : Scott Thomas
*	last modified: 2019-9-6 8:20:58
*/


"use strict";

function viewComments(eid = 1, action = 'view_comments')
{
	document.forms['adminForm']['adminID'].value = eid;
	document.forms['adminForm']["adminAction"].value = action;
	document.forms['adminForm'].submit();
}

function deleteComment(id = 1, eid = 1, action = 'delete_comment')
{
	document.forms['adminForm']['adminID'].value = eid;
	document.forms['adminForm']['commentID'].value = id;
	document.forms['adminForm']["adminAction"].value = action;
	document.forms['adminForm'].submit();
}

function updateComment(eid)
{
	var row =  document.getElementsByClassName("hvr-fade-active");
	var messageBox = document.getElementById("customerExp");

	document.forms['adminForm']['adminID'].value = eid;
	document.forms['adminForm']['commentID'].value = row[0].id;
	document.forms['adminForm']["adminComment"].value = messageBox.value;
	document.forms['adminForm']["adminAction"].value = "update_comment";
	document.forms['adminForm'].submit();
}

function setActive(caller, id, eid)
{
	var elements = document.getElementsByClassName("hvr-fade-active");
	for(var i = 0; i < elements.length; ++i)
	{
		if(elements[i] == caller)
		{
			continue;
		}
		elements[i].classList.remove("hvr-fade-active");
	}
	
	if(!caller.classList.contains("hvr-fade-active"))
	{
		caller.classList.add("hvr-fade-active");
	}
	else
	{
		caller.classList.remove("hvr-fade-active");
	}

	document.forms['adminForm']['adminID'].value = eid;
	document.forms['adminForm']['commentID'].value = id;
}