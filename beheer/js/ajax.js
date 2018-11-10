// javascript file
// ajax.js

//hulp functies

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}


// workers functies
		
function process_mailform() {	
		//AJAX afhandeling van de poll
		var rform = document.getElementById('mailform');	
		
		var vars='?doe=return';
		if (rform.elements["voornaam"]) { vars=vars+"&voornaam="+rform.elements["voornaam"].value }
		if (rform.elements["achternaam"]) { vars=vars+"&achternaam="+rform.elements["achternaam"].value }
		if (rform.elements["email"]) { vars=vars+"&email="+rform.elements["email"].value }

		// do AJAX
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			document.getElementById('ajax_mailform').innerHTML=xmlhttp.responseText;
			}
		  }
		  
		callurl="workers/uc_mailform.php"+vars;  	
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();
		}
