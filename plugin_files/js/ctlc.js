(function($)
{
var CTLC =
{
	init: function()
	{
		this.externalLinks();
		this.resetLink();
		this.toggleForms();
		this.alreadyHaveAccountForm();
		this.newLicenseForm();
		this.controlPanelIframe();
		this.fadeChangesSaved();
	},

	externalLinks: function()
	{
		$('a.help').attr('target', '_blank');
	},

	resetLink: function()
	{
		$('#reset_settings a').click(function()
		{
			return confirm('This will reset your CodeTeam LiveChat plugin settings. Continue?');
		})
	},

	toggleForms: function()
	{
		var toggleForms = function()
		{
			// display account details page if license number is already known
			if ($('#choice_account').length == 0 || $('#choice_account_1').is(':checked'))
			{
				$('#ctlc_new_account').hide();
				$('#ctlc_already_have').show();
				$('#ctlc_email').focus();
			}
			else if ($('#choice_account_0').is(':checked'))
			{
				$('#ctlc_already_have').hide();
				$('#ctlc_new_account').show();

				if ($.trim($('#name').val()).length == 0)
				{
					$('#name').focus();
				}
				else
				{			
					$('#password').focus();
				}
			}
		};

		toggleForms();
		$('#choice_account input').click(toggleForms);
	},

	alreadyHaveAccountForm: function()
	{
		$('#ctlc_already_have form').submit(function()
		{
			if ($('#account_key').val() == "")
			{
				var email = $.trim($('#ctlc_email').val());
				if (!email.length)
				{
					$('#ctlc_email').focus();
					return(false);
				}
				
				var password = $.trim($('#ctlc_password').val());
				if (!password.length)
				{
					$('#ctlc_password').focus();
					return(false);
				}
				
				$('#ctlc_already_have .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');
				$.getJSON('http://www.mylivechat.codeteam.in/Login/login?email='+email+'&password='+password+'&jsoncallback=?', function(Response)
				{
					if(Response.ReturnStatus == 1)
					{
						$('#account_key').val(Response.ReturnData);
						$('#ctlc_already_have form').submit();
					}
					else
					{
						$('#ctlc_already_have .ajax_message').removeClass('wait').addClass('message').html(Response.ReturnMsg);
						$('#ctlc_email').focus();
						return(false);
					}
				});
				return(false);
			}
		});		
	},

	newLicenseForm: function()
	{
		$('#ctlc_new_account form').submit(function()
		{
			if($('#new_account_key').val() != "")
			{
				return(true);
			}

			if (CTLC.validateNewLicenseForm())
			{
				$('#ctlc_new_account .ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

				// Check if email address is available
				$.getJSON('http://www.livechat.codeteam.in/Signup/checkDuplicationEmail?email='+$('#email').val()+'&jsoncallback=?',
				function(Response)
				{
					if(Response.ReturnStatus == 1)
					{
						CTLC.createAccount();
					}
					else if(Response.ReturnStatus == 0)
					{
						$('#ctlc_new_account .ajax_message').removeClass('wait').addClass('message').html(Response.ReturnMsg);
					}
					else
					{
						$('#ctlc_new_account .ajax_message').removeClass('wait').addClass('message').html('Could not create account. Please try again later.');
					}
				});
			}
			return(false);
		});
	},

	createAccount: function()
	{
		var url;

		$('#ctlc_new_account .ajax_message').removeClass('message').addClass('wait').html('Creating new account&hellip;');

		url = 'http://www.livechat.codeteam.in/signup/register';
		url += '?name='+encodeURIComponent($('#name').val());
		url += '&email='+encodeURIComponent($('#email').val());
		url += '&password='+encodeURIComponent($('#password').val());
		url += '&website='+encodeURIComponent($('#website').val());
		url += '&timezone_gmt='+encodeURIComponent(this.calculateGMT());
		url += '&source=wordpress';
		url += '&jsoncallback=?';

		$.getJSON(url, function(Response)
		{
			if(Response.ReturnStatus == 0)
			{
				$('#ctlc_new_account .ajax_message').html('Could not create account. Please try again later.').addClass('message').removeClass('wait');
				return(false);
			}

			// save new account key
			$('#new_account_key').val(Response.ReturnData);
			$('#save_new_account_key').submit();
		});
	},

	validateNewLicenseForm: function()
	{
		if ($('#name').val().length < 1)
		{
			alert('Please enter your name.');
			$('#name').focus();
			return(false);
		}

		if (/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i.test($('#email').val()) == false)
		{
			alert('Please enter a valid email address.');
			$('#email').focus();
			return(false);
		}

		if ($.trim($('#password').val()).length < 1)
		{
			alert('Please enter password.');
			$('#password').focus();
			return(false);
		}

		if ($('#password').val() !== $('#password_retype').val())
		{
			alert('Both passwords do not match.');
			$('#password').val('');
			$('#password_retype').val('');
			$('#password').focus();
			return(false);
		}

		return(true);
	},

	calculateGMT: function()
	{
		var date, dateGMTString, date2, gmt;

		date = new Date((new Date()).getFullYear(), 0, 1, 0, 0, 0, 0);
		dateGMTString = date.toGMTString();
		date2 = new Date(dateGMTString.substring(0, dateGMTString.lastIndexOf(" ")-1));
		gmt = ((date - date2) / (1000 * 60 * 60)).toString();

		return gmt;
	},

	controlPanelIframe: function()
	{
		var cp = $('#control_panel');
		if (cp.length)
		{
			var cp_resize = function()
			{
				var cp_height = window.innerHeight ? window.innerHeight : $(window).height();
				cp_height -= $('#wphead').height();
				cp_height -= $('#updated-nag').height();
				cp_height -= $('#control_panel + p').height();
				cp_height -= $('#footer').height();
				cp_height -= 70;

				cp.attr('height', cp_height);
			}
			cp_resize();
			$(window).resize(cp_resize);
		}
	},

	fadeChangesSaved: function()
	{
		$cs = $('#changes_saved_info');

		if ($cs.length)
		{
			setTimeout(function()
			{
				$cs.slideUp();
			}, 1000);
		}
	}
};

$(document).ready(function()
{
	CTLC.init();
});
})(jQuery);