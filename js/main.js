// JavaScript Document

function setCookie(c_name,value,expiredays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : "; expires="+exdate.toGMTString())+ "; path=/";
}
 
function getCookie(c_name){
if (document.cookie.length>0){
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1){ 
    c_start=c_start + c_name.length+1; 
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    } 
  }
	return false;
}


function swap_img(name,pic) {
		document[name].src=pic;
}

function do_mouse_over(id) {	
	var e = document.getElementById(id);
 	var oClass = e.getAttribute('overClass'); 
	if(oClass){ 
       	e.setAttribute('origClass', e.className); 
    	e.className = oClass;
	}
}

function do_mouse_out(id) {	
//alert('mouse_out');
	var e = document.getElementById(id);
 	var oClass = e.getAttribute('origClass'); 
	if(oClass){ 
    	e.className = oClass;
	}
}

function call_url(url) {
	window.location=url;
}



function activeer_input(name, val) {
	var loginform = document.getElementById('aform');
	var i=loginform.elements[name].value;
	if (i==val)
	  {
		loginform.elements[name].value='';		  
		loginform.elements[name].className = 'input_active';
	  }
	loginform.elements[name].focus();  
}

function activeer_ww(name, val) {
	var loginform = document.getElementById('aform');
	var i=loginform.elements[name].value;
	if (i==val)
	  {
		loginform.elements[name].value='';		  
		loginform.elements[name].className = 'input_active';
		document.getElementById(name).type="password";
	  }
	loginform.elements[name].focus();  
}

function deactiveer_input(name, val) {
	var loginform = document.getElementById('aform');
	var i=loginform.elements[name].value;
	if (i=='')
	  { 
		loginform.elements[name].value=val;		  
		loginform.elements[name].className = 'input_disabled';
		document.getElementById(name).type="text";	
	  } 
}


function activeer_login() {
	var loginform = document.getElementById('loginform');
	var login=loginform.elements['login'].value;
	if (login=='Inlognaam')
	  {
		loginform.elements['login'].value='';		  
		loginform.elements['login'].className = 'login_input';
	  }
	loginform.elements['login'].focus();  
}

function deactiveer_login() {
	var loginform = document.getElementById('loginform');
	var login=loginform.elements['login'].value;
	if (login=='')
	  { 
		loginform.elements['login'].value='Inlognaam';		  
		loginform.elements['login'].className = 'login_input_disabled';	
	  } 
}

function activeer_password() {
	var loginform = document.getElementById('loginform');
	var pass=loginform.elements['password'].value;
	if (pass=='Wachtwoord')
	  { 
		//innerHTML='<input type="password" name="password" value="" class="login_input" onFocus="activeer_password();" onBlur="deactiveer_password();">';
		loginform.elements['password'].type='password';		  
		loginform.elements['password'].value='';		  
		loginform.elements['password'].className = 'login_input';
	  }
	loginform.elements['password'].focus();  
}

function deactiveer_password() {
	var loginform = document.getElementById('loginform');
	var pass=loginform.elements['password'].value;
	if (pass=='')
	  {
		document.getElementById('login_password').value='Wachtwoord';
		document.getElementById('login_password').type='password';	
		//innerHTML='<input type="text" name="password" value="Wachtwoord" class="login_input" onFocus="activeer_password();" onBlur="deactiveer_password();">'; 
		loginform.elements['password'].type='text';		  
		loginform.elements['password'].value='Wachtwoord';			 		  
		loginform.elements['password'].className = 'login_input_disabled';	
	  } 
}


function activeer_bedrag() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['bedrag'].value;
	if (value=='Bedrag')
	  {
		sform.elements['bedrag'].value='';		  
		sform.elements['bedrag'].className = 'login_input';
	  }
	sform.elements['bedrag'].focus();  
}

function deactiveer_bedrag() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['bedrag'].value;
	if (value=='')
	  { 
		sform.elements['bedrag'].value='Bedrag';		  
		sform.elements['bedrag'].className = 'login_input_disabled';	
	  } 
}

function activeer_naam() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['naam'].value;
	if (value=='Naam')
	  {
		sform.elements['naam'].value='';		  
		sform.elements['naam'].className = 'login_input';
	  }
	sform.elements['naam'].focus();  
}

function deactiveer_naam() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['naam'].value;
	if (value=='')
	  { 
		sform.elements['naam'].value='Naam';		  
		sform.elements['naam'].className = 'login_input_disabled';	
	  } 
}

function activeer_email() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['email'].value;
	if (value=='Email')
	  {
		sform.elements['email'].value='';		  
		sform.elements['email'].className = 'login_input';
	  }
	sform.elements['email'].focus();  
}

function deactiveer_email() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['email'].value;
	if (value=='')
	  { 
		sform.elements['email'].value='Email';		  
		sform.elements['email'].className = 'login_input_disabled';	
	  } 
}

function activeer_adres() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['adres'].value;
	if (value=='Adres')
	  {
		sform.elements['adres'].value='';		  
		sform.elements['adres'].className = 'login_input';
	  }
	sform.elements['adres'].focus();  
}

function deactiveer_adres() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['adres'].value;
	if (value=='')
	  { 
		sform.elements['adres'].value='Adres';		  
		sform.elements['adres'].className = 'login_input_disabled';	
	  } 
}
function activeer_plaats() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['plaats'].value;
	if (value=='Plaats')
	  {
		sform.elements['plaats'].value='';		  
		sform.elements['plaats'].className = 'login_input';
	  }
	sform.elements['plaats'].focus();  
}

function deactiveer_plaats() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['plaats'].value;
	if (value=='')
	  { 
		sform.elements['plaats'].value='Plaats';		  
		sform.elements['plaats'].className = 'login_input_disabled';	
	  } 
}


function activeer_rekening() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['bankrekening'].value;
	if (value=='IBAN nummer')
	  {
		sform.elements['bankrekening'].value='';		  
		sform.elements['bbankrekening'].className = 'login_input';
	  }
	sform.elements['bankrekening'].focus();  
}

function deactiveer_rekening() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['bankrekening'].value;
	if (value=='')
	  { 
		sform.elements['bankrekening'].value='IBAN nummer';		  
		sform.elements['bankrekening'].className = 'login_input_disabled';	
	  } 
}

function activeer_onderwerp() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['onderwerp'].value;
	if (value=='Onderwerp')
	  {
		sform.elements['onderwerp'].value='';		  
		sform.elements['onderwerp'].className = 'login_input';
	  }
	sform.elements['onderwerp'].focus();  
}

function deactiveer_onderwerp() {
	var sform = document.getElementById('sponsorform');
	var value=sform.elements['onderwerp'].value;
	if (value=='')
	  { 
		sform.elements['onderwerp'].value='Onderwerp';		  
		sform.elements['onderwerp'].className = 'login_input_disabled';	
	  } 
}


function do_newbox() {
	var bform=document.getElementById('bform');
	bform.elements['boxact'].value='newbox';
	bform.submit();
}

function delete_box(id) {
	var bform=document.getElementById('bform');
	bform.elements['boxact'].value='delbox';
	bform.elements['boxid'].value=id;
	bform.submit();
}

function cms_foto_change() {
	var bform=document.getElementById('bform');
	var selectedindex=bform.elements['foto'].selectedIndex;
	var foto=bform.elements['foto'][selectedindex].value;
	document.getElementById('cms_foto').innerHTML='<img src="img/foto.php?id='+foto+'" />';
}

function start_cms_txt_editor() {
		if (CKEDITOR.instances['txt_editor'])  { delete CKEDITOR.instances['txt_editor']; }		
		var ed = CKEDITOR.replace( 'txt_editor', { toolbar : [
						{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] }
					], uiColor : '#560666',  width : 580 , height : 80
				});			
		show_cms_txt_editor();		
}

function show_cms_txt_editor() {
	var e=document.getElementById('cms_txt_editor');
		e.style.display='block';
}	
function close_cms_txt_editor() {
	var e=document.getElementById('cms_txt_editor');
		e.style.display='none';
}	
function close_cms_datum() {
	var e=document.getElementById('cms_datum_editor');
		e.style.display='none';
}


var sc=parseInt(getCookie('kvkikacount'));
if (!sc) { sc=0; }
//alert(sc);

var slide_step=7;
var slide_pos=150;
var slide_status='start';
var slide_op=100;
	
function slide_in() {
	var div=document.getElementById('logobox');
	
	if (slide_status=='ready') { slide_status='fade'; }
	
	if (slide_status=='fade')
	  {
		 slide_op=slide_op-slide_step; 
		 div.style.opacity=(slide_op/100);
		 div.style.filter='alpha(opacity=' + slide_op + ')';
		 //alert(slide_op);
		 if (slide_op<=0)
		   { 
		   		slide_status='slide'; 
		   }
		 setTimeout('slide_in()',40);
	  }
	  else
	  {
		  if (slide_pos==150)
		    {
				div.style.marginLeft='150px';
				div.style.opacity=1;
				div.style.filter='alpha(opacity=100)';
				var logo='<img src="https://'+basis_url+'/img/logos/'+logos[sc]+'">';
				div.innerHTML=logo;
			}
		slide_pos=slide_pos-slide_step;
		div.style.marginLeft=slide_pos+'px';
		if (slide_pos<=1)
		  { div.style.marginLeft=0+'px';
			slide_pos=150;
			slide_op=100; 
			slide_status='ready'}
		  else
		  { setTimeout('slide_in()',20); }
	  }
}
	
function sponsor_cycle() {;
	if ((slide_status=='ready') || (slide_status=='start'))
	  { 
		//alert(sc);
		slide_in();
		sc=sc+1;
		if (sc>aantal_logos) { sc=0; }
		setCookie('kvkikacount',sc,1);
	  }
	slide_timer = setTimeout('sponsor_cycle()',5000);  
}


function flip_draaiboek(st) {
	s=document.getElementById('db_start_tijd');
	e=document.getElementById('db_eind_tijd');
	
	if (st=='dag')
	  {
		s.style.display='block';
		e.style.display='block';  
	  }
	  else
	  {
		s.style.display='none';
		e.style.display='none';  
	  }

}

function menu_flip_actie() {
	form=document.getElementById('menu_form');
	veld=document.getElementById('actie_veld');
	lijst=document.getElementById('actie_lijst');
	if (form.elements['extern'].checked)
	  {
		veld.style.display='table-row';
		lijst.style.display='none';
	  }
	  else
	  {
		veld.style.display='none';
		lijst.style.display='table-row';
	  }	  
}