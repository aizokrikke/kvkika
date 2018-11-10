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

function encode_utf8( s )
{
  return unescape( encodeURIComponent( s ) );
}

function decode_utf8( s )
{
  return decodeURIComponent( escape( s ) );
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

function do_flipRecht(user,recht) {
	
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
			document.getElementById('beheerderslijst').innerHTML=xmlhttp.responseText;
			}
		  }
		  
		callurl="workers/flip_recht.php?user="+user+"&recht="+recht;  	
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();
		}

function datum_select(div,stamp) {
			
		//AJAX afhandeling van de metadata van het artikelformulier	
		var vars="?div="+div+"&datum_select="+stamp;
			
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
			document.getElementById(div).innerHTML=xmlhttp.responseText;
			}
		  } 
		callurl="beheer/workers/datum_invoer.php"+vars; 
//alert(callurl);
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();
		}
		

	
function cms_editor (id,veld) {
//alert(id+' | '+veld);	

		var e=document.getElementById('cms_txt_editor');
		
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
			e.innerHTML=xmlhttp.responseText;
			}
		  } 
		callurl='beheer/workers/cms_txt_editor_prep.php?id='+id+'&veld='+veld; 
//alert(callurl);
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();
		setTimeout("start_cms_txt_editor()",300);	

		}
function proc_cms_txt_editor (id,veld) {
		var tekst=encodeURIComponent(CKEDITOR.instances.txt_editor.getData());
		
		close_cms_txt_editor();
		
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
			document.getElementById('nieuws'+id).innerHTML=xmlhttp.responseText;
			}
		  } 
		callurl='beheer/workers/cms_txt_editor_result.php?id='+id+'&veld='+veld+'&tekst='+tekst; 
//alert(callurl);
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();

		}
function proc_cms_datum (id) {
	
		var form=document.getElementById('dform');
		var datum=form.elements['cms_datum'].value;
		
		close_cms_datum();
		
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
			document.getElementById('nieuws'+id).innerHTML=xmlhttp.responseText;
			}
		  } 
		callurl='beheer/workers/cms_datum_editor_result.php?id='+id+'&datum='+datum; 
//alert(callurl);
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();

		}
		

function cms_datum(id) {
		var e=document.getElementById('cms_datum_editor');
		e.style.display='block';
		
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
			e.innerHTML=xmlhttp.responseText;
			}
		  } 
		callurl='beheer/workers/cms_datum_prep.php?id='+id; 
//alert(callurl);
		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();	

		}		
		
function doFlipNieuwsStatus(id) {

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
			document.getElementById('nieuws'+id).innerHTML=xmlhttp.responseText;
			}
		  }
		  
		callurl="beheer/workers/flip_nieuws_status.php?id="+id;  	

		xmlhttp.open("GET",callurl,true);
		xmlhttp.send();
		}
		
function proc_doc(act,doc) {	
		//AJAX afhandeling van de gekoppelde documenten
		var aform = document.getElementById('aform');
		var id = aform.elements['id'].value;
		if (doc==null)
		  {
			var s=aform.elements['doc'].selectedIndex;
			var doc=aform.elements['doc'].options[s].value;
		  }
		  
		  
		if (doc=='new')
		  {
			  document.getElementById('db_upload').innerHTML = '<iframe width="300" height="150" frameborder="0" scrolling="no" src="beheer/upload.php?id='+id+'"></iframe><div id="db_upload_close"><a href="javascript:void();" onclick="close_upload();"><img src="beheer/img/close_red.png" alt="sluiten" title="sluiten" /></a>';
			  var e=document.getElementById('db_mask');
			  e.style.display='block';
			  var e=document.getElementById('db_upload');
			  e.style.display='block';
		  }
		  else
		  {
			  
			var vars = '?doc='+doc+'&act='+act+'&id='+id;
	
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
				document.getElementById('docs').innerHTML=xmlhttp.responseText;
				}
			  }
			  
			callurl="beheer/workers/db_docs_prep.php"+vars;
			//alert(callurl);  	
			xmlhttp.open("GET",callurl,true);
			xmlhttp.send();
		  }
		}

function close_upload() {	
		//AJAX afhandeling na sluiten van upload pop up
		var aform = document.getElementById('aform');
		var id = aform.elements['id'].value;	
		var vars = '?id='+id;
		
			  var e=document.getElementById('db_upload');
			  e.style.display='none';	
			  var e=document.getElementById('db_mask');
			  e.style.display='none';
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
				document.getElementById('docs').innerHTML=xmlhttp.responseText;
				}
			  }
			  
			callurl="beheer/workers/db_docs_prep.php"+vars;
			//alert(callurl);  	
			xmlhttp.open("GET",callurl,true);
			xmlhttp.send();
		}
