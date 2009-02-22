<?php
/**
 *                              modules.inc.php
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

class module_controller {

	function module_controller() {}
	
	function run()
	{
		global $Template;
		
		if( retrieve(POST, 'valid', false) ) { //Enregistrement du formulaire
			trigger_error_if_no_access(CREATE_ACCESS|UPDATE_ACCESS);
			$this->do_save();
			redirect(HOST . SCRIPT . SID2);
			exit;
			
		} elseif ( $id_get = retrieve(GET, 'delete', 0, TINTEGER) ) {
			trigger_error_if_no_access(DELETE_ACCESS);
			$this->do_delete($id_get);
			redirect(HOST . SCRIPT . SID2);
			exit;
			
		} elseif ( $id_get = retrieve(GET, 'edit', 0, TINTEGER) ) {
			trigger_error_if_no_access(UPDATE_ACCESS);
			$Template->set_filenames(array(
				'samples' => 'samples/samples.tpl'
			));
			$this->do_edit($id_get);
			$Template->pparse('samples'); 
			
		} else { //Affichage.
			trigger_error_if_no_access(CREATE_ACCESS|LIST_ACCESS);
			$Template->set_filenames(array(
				'samples' => 'samples/samples.tpl'
			));
			$this->do_list();
			$Template->pparse('samples'); 
			
		}
	}
	
	function do_save() {}	
	function do_delete($id) {}
	function do_edit($id) {}
	function do_list() {}
}
?>