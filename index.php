<?php
/*
Plugin Name: CodeTeam LiveChat
Plugin URI: http://www.livechat.codeteam.in
Description: LiveChat plugin
Author: CodeTeam
Author URI: http://www.codeteam.in
Version: 1.1
*/

if (is_admin())
{
	require_once(dirname(__FILE__).'/plugin_files/CTLCAdmin.class.php');
	CTLCAdmin::get_instance();
}
else
{
	require_once(dirname(__FILE__).'/plugin_files/CTLC.class.php');
	CTLC::get_instance();
}

