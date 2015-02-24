
function myFunctionFeedback(concert)
{
		$("#feedback-title").text("Feedback");
		var date = $("#"+concert).attr("date");
		//alert(date+" "+concert);
		$("#feedback-body").load("process_feedback.php?concert="+concert);
		//$("#feedback-body").text("Working"+concert);
		return true;
}

function myFunctionReco(concert)
{
		$("#recommend-title").text("Creating a recommendation");
		var date = $("#"+concert).attr("date");
		//alert(date+" "+concert);
		$("#recommend-body").load("addReco.php?concert_id="+concert + "&date=" + date);
		return true;
}

function newfunction(concert) 
	{
													
		$("#myModalLabel").text("Deleting Record");
		$("#modal-body").load("modal.php?concert_id="+concert);
		return true;
	}
	
function myFunction() {
    $("#h01").attr("style","color:red")
	
}
$(document).ready(myFunction);

$('#myTab a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

$('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
})

function validateReco()
{
	var reco_name = document.getElementById("reco-name");
	if(reco_name.value == "" || reco_name.value == null)
	{
		reco_name.style.border= "2px solid red";
			setTimeout(function(){
			reco_name.style.border= "1px solid silver";
			},2000);
			return false;

	}
		
return true;
}

function check()
{
	var username = document.getElementById("login_username").value;
	var password = document.getElementById("login_password").value;
	
	if(username == "" || username == null)
	{
		alert("Please provide your username");
		return false;
	}
	
	if(password == "" || password == null)
	{
		alert("Please provide your password");
		return false;
	}
	return true;
}

function validate()
{
	var fname = document.getElementById("fname").value;
	var lname = document.getElementById("lname").value;
	var dob = document.getElementById("dob").value;
	var uid = document.getElementById("uid").value;
	var password = document.getElementById("reg_password").value;
	//alert(fname+lname+dob+uid+password);
		if(fname=="" || fname == null)
		{
			var temp = document.getElementById("fname");
			
			temp.style.border= "2px solid red";
			
			setTimeout(function(){
			temp.style.border= "1px solid silver";
			},2000);
			return false;
		}
		
		if(lname=="" || lname == null)
		{
			var temp = document.getElementById("lname");
			temp.style.border= "2px solid red";
			setTimeout(function(){
			temp.style.border= "1px solid silver";
			},2000);
			//alert("Please enter your Last Name");
			return false;
		}
		
		if(dob=="" || dob == null)
		{
			var temp = document.getElementById("dob");
			temp.style.border= "2px solid red";
			setTimeout(function(){
			temp.style.border= "1px solid silver";
			},2000);
			//alert("Please enter your Date of Birth");
			return false;
		}
		
		if(uid=="" || uid == null)
		{
			var temp = document.getElementById("uid");
			temp.style.border= "2px solid red";
			setTimeout(function(){
			temp.style.border= "1px solid silver";
			},2000);
			//alert("Please enter your Username");
			return false;
		}

		if(password=="" || password == null)
		{
			var temp = document.getElementById("reg_password");
			temp.style.border= "2px solid red";
			setTimeout(function(){
			temp.style.border= "1px solid silver";
			},2000);
			//alert("Please enter your your password");
			return false;
		}
	return true;
}

