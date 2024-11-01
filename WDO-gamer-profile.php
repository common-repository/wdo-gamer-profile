<?php
/*
Plugin Name: WDO Gamer Profile
Plugin URI: http://www.webdevsonline.com
Description: Displays users Steam ID, Xbox gamer tag and Playstation Network name using the [WDO-gamer-profile] shortcode. Users can opt out of displaying their details and links to their profiles in the user list if they wish to. For more information, or if you need help with the plugin, or to request an update, email us at contact@webdevsonline.com. Visit http://www.webdesonline.com/store for more plugins and themes.
Version: 1.0.2
Author: Web Devs Online
Author URI: http://www.webdevsonline.com

For more information, email us at contact@webdevsonline.com.

Copyright 2012 Web Devs Online

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

function wdo_gamer_profile(){

	global $profileuser;
	$user_id = $profileuser->ID;
	global $wpdb;
	$prefix = $wpdb->prefix;
	
?>
<br />
   <h3><?php _e('Gamer Details', 'your_textdomain'); ?></h3>
    <table class="form-table">
    <tr>
    <th>
    <label for="address"><?php _e('Steam ID', 'your_textdomain'); ?>
    </label>
	</th>
    <td>
	<?php
	$sql1 = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'steam_ID' AND user_id =".$user_id."");
	$steam = mysql_fetch_array($sql1);
	?>
	<input type="text" name="steam_ID" value="<?php echo $steam[0]; ?>"/>
	</td>
	</tr>
	<tr>
	<th>
    <label for="address"><?php _e('Xbox Gamer Tag', 'your_textdomain'); ?>
    </label>
	</th>
    <td>
	<?php
	$sql2 = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'xbox_tag' AND user_id =".$user_id."");
	$xbox = mysql_fetch_array($sql2);
	?>
	<input type="text" name="xbox_tag" value="<?php echo $xbox[0]; ?>" />
	</td>
	</tr>
	<tr>
	<th>
    <label for="address"><?php _e('Playstation Network Name', 'your_textdomain'); ?>
    </label>
	</th>
    <td>
	<?php
	$sql3 = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'psn_name' AND user_id =".$user_id."");
	$psn = mysql_fetch_array($sql3);
	?>
	<input type="text" name="psn_name" value="<?php echo $psn[0]; ?>" />
	</td>
	</tr>
	<tr>
	<th>
    <label for="address"><?php _e('Display details', 'your_textdomain'); ?>
    </label>
	</th>
    <td>
	<?php 
	$sql2 = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'gamer_display' AND user_id ='.$user_id.'");
	$test2 = mysql_num_rows($sql2);
	if (empty($test2))
	{
	$auto = 'checked="checked"';
	}
	if (!empty($sql2))
	{
	$test = mysql_fetch_array($sql2);
	}
	if ($test[0] == 'y'){
	$y = 'checked="checked"';
	}
	if ($test[0] == 'n'){
	$n = 'checked="checked"';
	}
	?>
	<input type="radio" value="y" name="gamer_display" <?php echo $auto; echo $y; ?>/> Yes 
	<input type="radio" value="n" name="gamer_display" <?php echo $n; ?>/> No 
	</td>
	</tr>
	</table>
	<br />
<?php
}		

	function fb_save_custom_user_gamer_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
    return FALSE;
    update_user_meta( $user_id, 'steam_ID', $_POST['steam_ID'] );
	update_user_meta( $user_id, 'xbox_tag', $_POST['xbox_tag'] );
	update_user_meta( $user_id, 'psn_name', $_POST['psn_name'] );
	update_user_meta( $user_id, 'gamer_display', $_POST['gamer_display'] );
    }

	    add_action( 'show_user_profile', 'wdo_gamer_profile' );
    	add_action( 'edit_user_profile', 'wdo_gamer_profile' );
    	add_action( 'personal_options_update', 'fb_save_custom_user_gamer_fields' );
    	add_action( 'edit_user_profile_update', 'fb_save_custom_user_gamer_fields' );
		
function display_gamer_profile(){

?>
	<script>function ReverseDisplay(d) {
if(document.getElementById(d).style.display === "none") { document.getElementById(d).style.display = "block"; }
else { document.getElementById(d).style.display = "none"; }
}</script>
<?php

	global $profileuser;
	$user_id = $profileuser->ID;
	global $wpdb;
	$prefix = $wpdb->prefix;

	$sql_dis = mysql_query("select user_id, meta_value from ".$prefix."usermeta where meta_key = 'gamer_display'");
	while ($display = mysql_fetch_array($sql_dis)){
	if ($display[1] == 'y'){
	$sql_user = mysql_query("select user_nicename from ".$prefix."users where ID = ".$display[0]."");
	while ($dis_user = mysql_fetch_array($sql_user)){
	echo '<a href="javascript:ReverseDisplay(\''.$display[0].'\')">' .$dis_user[0]. '</a><br />';
	echo '<div id="'.$display[0].'" style="display:none;">';
	$steam_details = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'steam_ID' AND user_id =".$display[0]."");
	$dis_steam = mysql_fetch_array($steam_details);
	$xbox_details = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'xbox_tag' AND user_id =".$display[0]."");
	$dis_xbox = mysql_fetch_array($xbox_details);
	$psn_details = mysql_query("select meta_value from ".$prefix."usermeta where meta_key = 'psn_name' AND user_id =".$display[0]."");
	$dis_psn = mysql_fetch_array($psn_details);
	echo 'Steam ID: <strong>'.$dis_steam[0].'</strong> <br />
		  &nbsp;&nbsp; <a href="http://steamcommunity.com/id/'.$dis_steam[0].'">Steam Profile</a><br />
		  Xbox Gamer Tag: <strong>'.$dis_xbox[0].'</strong> <br />
		  &nbsp;&nbsp; <a href="https://live.xbox.com/en-GB/Profile?gamertag='.$dis_xbox[0].'">Xbox Profile</a><br />
		  PlayStation Network Name: <strong>'.$dis_psn[0].'</strong><br />
		  &nbsp;&nbsp; <a href="http://us.playstation.com/profile/?onlinename='.$dis_psn[0].'">PlayStation Profile</a>';
	echo '</div>';
	}
	}
	}
}
		
 function gamer_profile_shortcode($atts) 
{ 
	  echo display_gamer_profile();
}
add_shortcode("WDO-gamer-profile","gamer_profile_shortcode");
?>