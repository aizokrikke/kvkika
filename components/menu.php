    <div id="menu">
<?php

	$mres = db_query("select id, tekst, actie, extern from menu where site = '$subdomein' and verwijderd != 'j' order by id asc");
	$i = 1;
	while ($mr = db_row($mres))
	  { 
		if ($mr[2] == '?state=' . $state) {
?>
		<div class="menuitem_selected">
<?php 	  } else {
?>		
		<div id="menu<?php echo $mr[0];?>" class="menuitem" overClass="menuitem_over" onmouseover="do_mouse_over('menu<?php echo $mr[0];?>');" onmouseout="do_mouse_out('menu<?php echo $mr[0];?>');" onclick="call_url('<?php
            if ($mr[3] != 'j') {
                echo $protocol.$domein."/";
            }
            echo $mr[2]; ?>');">
<?php		
		  }
?>
		<?php echo $mr[1];?></div>
<?php		  
		$i++;
		if ($i > 5) {
?>
		<div class="menu_closer"></div>
        <div style="clear: both;"></div>
<?php
			$i = 1;
		  }
	  }
?>    
    </div>


    <div id="menu_mobile">
      <form action="<?php echo $protocol.$domein;?>" id="mobile_menu">
        <img src="../img/mobile_menu_icon.png" align="absmiddle" width="32" height="32"> <select name="mobile_menu_id" onChange="document.getElementById('mobile_menu').submit();" class="input">
          <option value="">MENU</option>
<?php

	$mres=db_query("select id,tekst,actie, extern from menu where site = '$subdomein' and verwijderd != 'j' order by id asc");
	while ($mr = db_row($mres))  {
?>		
		  <option value="<?php echo $mr[0];?>" <?php
          if ($mr[0] == $menu_id) {
              ?>selected<?php
          } ?>><?php echo $mr[1]; ?></option>
<?php		  
	  }
?>    
	  	</select>
      </form>
    </div>
