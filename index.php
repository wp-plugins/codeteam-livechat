<?php

/*
Plugin Name: CodeTeam Livechat
Plugin URI: http://www.livechat.codeteam.in
Description: LiveChat plugin
Version: 1.0
Author: CodeTeam
Author URI: http://www.codeteam.in
License: GPL2
 */


define('CTLC_FILE', __FILE__);
define('CTLC_PATH', plugin_dir_path(__FILE__));

require CTLC_PATH . 'ctlc.php';

new ctlc();
?>
