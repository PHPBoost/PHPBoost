<?php
/**
 *                              panel.inc.php
 *                            -------------------
 *   @author            alain91
 *   @license           GPL
 */

if( defined('PHPBOOST') !== true) exit;

define('ITEMS_PER_PAGE',	10);
define('MAX_LINKS',			3);

define('CREATE_ACCESS',		0x01);
define('UPDATE_ACCESS',		0x02);
define('DELETE_ACCESS',		0x04);
define('LIST_ACCESS',		0x08);
define('CONTRIB_ACCESS',	0x10);

class Lang
{
	function get($value)
	{
		global $LANG;
		
		if (is_string($value)) {
			if (!empty($LANG[$value]))
				return $LANG[$value];
			return $value;
		}
		return 'invalid_value';
	}
}

function access_ok($mask, $config_auth)
{
	global $User;
	
	return $User->check_auth($config_auth, $mask);
}

function trigger_error_if_no_access($mask, $config_auth)
{
	global $Errorh;
	
	if (!access_ok($mask, $config_auth))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}

function _clean_keys($key)
{
	if ( ! preg_match('/^[a-zA-Z0-9:_.-]+$/', $key))
	{
		die('Disallowed key characters in global data line :'. __LINE__ .' in file :'.__FILE__);
	}
	
	return $key;
}

function _clean_data($data)
{
	if (is_array($data))
	{
		$new_data = array();
		foreach ($data as $key => $val)
		{
			$new_data[_clean_keys($key)] = _clean_data($val);
		}
		return $new_data;
	}

	$data = trim($data);
	
	if (get_magic_quotes_gpc() === TRUE)
	{
		$data = stripslashes($data);
	}

	if (strpos($data, "\r") !== FALSE)
	{
		$data = str_replace(array("\r\n", "\r"), "\n", $data);
	}

	return $data;
}
