<?php

class ctlc {

    protected $option_name = 'ctlc';
	
    protected $data = array();

    public function __construct() {
		
		// Website head
        add_action('wp_head', array($this, 'init'));

        // Admin sub-menu
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'add_page'));

        // Listen for the activate event
        register_activation_hook(CTLC_FILE, array($this, 'activate'));

        // Deactivation plugin
        register_deactivation_hook(CTLC_FILE, array($this, 'deactivate'));
    }

    public function activate() {
        update_option($this->option_name, $this->data);
    }

    public function deactivate() {
        delete_option($this->option_name);
    }
	
    public function init() {
		
		$options = get_option($this->option_name);
		
		if($options['status'] == 'Active')
		{
			if(!empty($options['client_id']))
			{
				echo("<script type='text/javascript'>\n");
				echo("var _chat = _chat || [];\n");
				echo("_chat.push(['_setAccountID', '".$options['client_id']."']);\n");
				echo("_chat.push(['_setParameter', 'Position', '".$options['position']."']);\n");
				echo("_chat.push(['_setParameter', 'Size', '".$options['size']."']);\n");
				echo("_chat.push(['_setParameter', 'TextColor', '".$options['foreground_color']."']);\n");
				echo("_chat.push(['_setParameter', 'BackgroundColor', '".$options['background_color']."']);\n");
				echo("(function(){\n");
				echo("var scn = document.createElement('script');\n");
				echo("scn.type = 'text/javascript';\n");
				echo("scn.async = true;\n");
				echo("scn.src = 'http://www.livechat.codeteam.in/js/plugin.js';\n");
				echo("var s = document.getElementsByTagName('script')[0];\n");
				echo("s.parentNode.insertBefore(scn, s);\n");
				echo("})();\n");
				echo("</script>");
			}
		}
    }
	
    public function admin_init() {
        register_setting('ctlc_options', $this->option_name, array($this, 'validate'));
    }

    // Update plugin setting in admin panel
    public function add_page() {
        add_options_page('CodeTeam LiveChat', 'CodeTeam LiveChat', 'manage_options', 'ctlc_options', array($this, 'options_do_page'));
    }

    public function options_do_page() {
		$size_list = array('Cozy', 'Comfortable', 'Compact');
		$position_list = array('Right', 'Left');
		$status_list = array('Inactive', 'Active');
		
        $options = get_option($this->option_name);
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function()
		{
			jQuery(".color").click(function()
			{
				jQuery("input[rel="+jQuery(this).attr("id")+"]").click();
			});
			
			jQuery("input[type=color]").change(function()
			{
				jQuery("#"+jQuery(this).attr("rel")).val(jQuery(this).val());
			});
		});
        </script>
        <div class="wrap">
            <h2>CodeTeam LiveChat</h2>
            <form method="post" action="options.php">
                <?php settings_fields('ctlc_options'); ?>
                <table class="form-table">
                    <tr valign="top"><th scope="row">Client Id:</th>
                        <td>
                        	<textarea name="<?php echo $this->option_name?>[client_id]" style="resize:none;width:250px;height:70px;"><?php echo $options['client_id']; ?></textarea>
                            <div style="font-style:italic;">Get your Client Id by registring on <a href="http://www.livechat.codeteam.in/" target="_blank">CodeTeam LiveChat</a></div>
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row">Size:</th>
                        <td>
							<select name="<?php echo $this->option_name?>[size]" style="width:150px;">
                            	<?php
									foreach($size_list as $value)
									{
										if($options['size'] == $value)
										{
											echo('<option value="'.$value.'" selected="selected">'.$value.'</option>');
										}
										else
										{
											echo('<option value="'.$value.'">'.$value.'</option>');
										}
									}
								?>
							</select>
						</td>
                    </tr>
					<tr valign="top"><th scope="row">Position:</th>
                        <td>
							<select name="<?php echo $this->option_name?>[position]" style="width:150px;">
                            	<?php
									foreach($position_list as $value)
									{
										if($options['position'] == $value)
										{
											echo('<option value="'.$value.'" selected="selected">'.$value.'</option>');
										}
										else
										{
											echo('<option value="'.$value.'">'.$value.'</option>');
										}
									}
								?>
							</select>
						</td>
                    </tr>
					<tr valign="top"><th scope="row">Foreground Color:</th>
                        <td>
                        	<input type="text" id="txt_foregroundColor" class="color" name="<?php echo $this->option_name?>[foreground_color]" value="<?php echo $options['foreground_color']; ?>" style="width:150px;" />
                            <input type="color" rel="txt_foregroundColor" style="visibility:hidden;" />
                        </td>
                    </tr>
					<tr valign="top"><th scope="row">Background Color:</th>
                        <td>
                        	<input type="text" id="txt_backgroundColor" class="color" name="<?php echo $this->option_name?>[background_color]" value="<?php echo $options['background_color']; ?>" style="width:150px;" />
                            <input type="color" rel="txt_backgroundColor" style="visibility:hidden;" />
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row">Status:</th>
                    	<td>
                        	<select name="<?php echo $this->option_name?>[status]" style="width:150px;">
                            	<?php
									foreach($status_list as $value)
									{
										if($options['status'] == $value)
										{
											echo('<option value="'.$value.'" selected="selected">'.$value.'</option>');
										}
										else
										{
											echo('<option value="'.$value.'">'.$value.'</option>');
										}
									}
								?>
							</select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>
        </div>
        <?php
    }

    public function validate($input) {
        $valid = array();
        $valid['client_id'] = sanitize_text_field($input['client_id']);
		$valid['size'] = $input['size'];
		$valid['position'] = $input['position'];
		$valid['foreground_color'] = sanitize_text_field($input['foreground_color']);
		$valid['background_color'] = sanitize_text_field($input['background_color']);
		$valid['status'] = $input['status'];

        if (strlen($valid['client_id']) == 0) {
            add_settings_error(
                    'client_id', 					// setting title
                    'userkey_texterror',			// error ID
                    'Please enter User Key',		// error message
                    'error'							// type of message
            );
			
			# Set it to the default value
			$valid['client_id'] = $this->data['client_id'];
        }
		
		if (strlen($valid['size']) == 0) {
            add_settings_error(
                    'size', 						// setting title
                    'size_texterror',				// error ID
                    'Please select Plugin Size',	// error message
                    'error'							// type of message
            );
			
			# Set it to the default value
			$valid['size'] = $this->data['size'];
        }
		
		if (strlen($valid['position']) == 0) {
            add_settings_error(
                    'position', 						// setting title
                    'position_texterror',				// error ID
                    'Please select Plugin Position',	// error message
                    'error'								// type of message
            );
			
			# Set it to the default value
			$valid['position'] = $this->data['position'];
        }
		
		if (strlen($valid['foreground_color']) == 0) {
            add_settings_error(
                    'foreground_color', 				// setting title
                    'foregroundcolor_texterror',		// error ID
                    'Please enter Foreground Color',	// error message
                    'error'								// type of message
            );
			
			# Set it to the default value
			$valid['foreground_color'] = $this->data['foreground_color'];
        }
		
		if (strlen($valid['background_color']) == 0) {
            add_settings_error(
                    'background_color', 				// setting title
                    'background_texterror',				// error ID
                    'Please enter Background Color',	// error message
                    'error'								// type of message
            );
			
			# Set it to the default value
			$valid['background_color'] = $this->data['background_color'];
        }
		
		if (strlen($valid['status']) == 0) {
            add_settings_error(
                    'status', 							// setting title
                    'status_texterror',					// error ID
                    'Please select Plugin Status',		// error message
                    'error'								// type of message
            );
			
			# Set it to the default value
			$valid['status'] = $this->data['status'];
        }
		
        return $valid;
    }
}

