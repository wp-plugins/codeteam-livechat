<?php

require_once('CTLCHelper.class.php');

class ChangesSavedHelper extends CTLCHelper
{
	public function render()
	{
		if (CTLC::get_instance()->changes_saved())
		{
			return '<div id="changes_saved_info" class="updated installed_ok"><p>Advanced settings saved successfully.</p></div>';
		}

		return '';
	}
}