<?php

class CTLC
{
	// singleton pattern
	protected static $instance;

	/**
	 * Absolute path to plugin files
	 */
	protected $plugin_url = null;

	/**
	 * LiveChat license parameters
	 */
	protected $login = null;
	protected $account_key = null;
	protected $skill = null;

	/**
	 * Remembers if CodeTeam LiveChat account key is set
	 */
	protected static $account_key_installed = false;

	/**
	 * Starts the plugin
	 */
	public function __construct()
	{
		add_action ('wp_head', array($this, 'tracking_code'));
	}

	public static function get_instance()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	/** 
	 * Returns plugin files absolute path
	 *
	 * @return string
	 */
	public function get_plugin_url()
	{
		if (is_null($this->plugin_url))
		{
			$this->plugin_url = WP_PLUGIN_URL.'/codeteam-livechat/plugin_files';
		}

		return $this->plugin_url;
	}

	/**
	 * Returns true if LiveChat license is set properly,
	 * false otherwise
	 *
	 * @return bool
	 */
	public function is_installed()
	{
		return ($this->get_account_key() != NULL);
	}

	/**
	 * Returns LiveChat license number
	 *
	 * @return int
	 */
	public function get_account_key()
	{
		if (is_null($this->account_key))
		{
			$this->account_key = get_option('ctlc_account_key');
		}

		return $this->account_key;
	}

	/**
	 * Returns LiveChat login
	 */
	public function get_login()
	{
		if (is_null($this->login))
		{
			$this->login = get_option('login');
		}

		return $this->login;
	}

	/**
	 * Injects tracking code
	 */
	public function tracking_code()
	{
		$this->get_helper('TrackingCode');
	}

	/**
	 * Echoes given helper
	 */
	public static function get_helper($class, $echo=true)
	{
		$class .= 'Helper';

		if (class_exists($class) == false)
		{
			$path = dirname(__FILE__).'/helpers/'.$class.'.class.php';
			if (file_exists($path) !== true)
			{
				return false;
			}

			require_once($path);
		}

		$c = new $class;

		if ($echo)
		{
			echo $c->render();
			return true;
		}
		else
		{
			return $c->render();
		}
	}
}