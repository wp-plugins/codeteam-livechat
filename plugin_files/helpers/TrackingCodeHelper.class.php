<?php

require_once('CTLCHelper.class.php');

class TrackingCodeHelper extends CTLCHelper
{
	public function render()
	{
		if (CTLC::get_instance()->is_installed())
		{
			$account_key = CTLC::get_instance()->get_account_key();

			return <<<HTML
<script type="text/javascript">
var _chat = _chat || [];
_chat.push(["_setAccountID", "{$account_key}"]);
(function()
{
	var scn = document.createElement("script");
	scn.type = "text/javascript";
	scn.async = true;
	scn.src = "http://www.livechat.codeteam.in/js/plugin.js";
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(scn, s);
})();
</script>
HTML;
		}

		return '';
	}
}