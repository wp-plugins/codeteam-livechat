<?php

require_once('CTLCHelper.class.php');

class TrackingCodeInfoHelper extends CTLCHelper
{
	public function render()
	{
		if (CTLC::get_instance()->is_installed())
		{
			return '<div class="updated installed_ok"><p>CodeTeam LiveChat is installed properly. Woohoo!</p></div>';
		}

		return '';
	}
}