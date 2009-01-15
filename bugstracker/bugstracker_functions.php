<?php
/**
 *  bugstracker_functions.php
 * 
 * @package     Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *   
 */

defined('PHPBOOST') or die('PHPBoost non installé'.__FILE__);
 
/**
*
*/
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

function clean_superglobales()
{
	global $GLOBALS;
	
	if (get_magic_quotes_runtime())
	{
		set_magic_quotes_runtime(0);
	}
	if (ini_get('register_globals'))
	{
		if (isset($_REQUEST['GLOBALS']))
		{
			die('Global variable overload attack line :' . __LINE__ . ' in file :'. __FILE__);
		}

		$_REQUEST = array();

		$preserve = array('GLOBALS', '_REQUEST', '_GET', '_POST', '_FILES', '_COOKIE', '_SERVER', '_ENV', '_SESSION');

		foreach ($GLOBALS as $key => $val)
		{
			if ( ! in_array($key, $preserve))
			{
				global $$key;
				$$key = NULL;
				unset($GLOBALS[$key], $$key);
			}
		}
	}
	
	if (is_array($_POST))
	{
		foreach ($_POST as $key => $val)
		{
			$_POST[_clean_keys($key)] = _clean_data($val);
		}
	}
	else
	{
		$_POST = array();
	}
	
	if (is_array($_GET))
	{
		foreach ($_GET as $key => $val)
		{
			$_GET[_clean_keys($key)] = _clean_data($val);
		}
	}
	else
	{
		$_GET = array();
	}
	
	if (is_array($_COOKIE))
	{
		foreach ($_COOKIE as $key => $val)
		{
			if ($key == '$Version' OR $key == '$Path' OR $key == '$Domain')
				continue;
			$_COOKIE[_clean_keys($key)] = _clean_data($val);
		}
	}
	else
	{
		$_COOKIE = array();
	}
}

class Bugstracker
{
	function get_label($label_id)
	{
		global $Sql;
		
		$tmp = $Sql->query_array(PREFIX.'bugstracker_parameters', 'label', "WHERE id='" . intval($label_id) . "'", __LINE__, __FILE__);
		
		$label = empty($tmp['label']) ? 'Aucun' : $tmp['label'];
		return $label;
	}
	
	function get_member_login($member_id)
	{
		global $Sql;
		
		if ( !empty($member_id) ) {
			$tmp_bdd = $Sql->query_array(PREFIX.'member', 'login', "WHERE user_id = " . $member_id, __LINE__, __FILE__);
			$tmp = empty($tmp_bdd['login'])?'Visiteur':$tmp_bdd['login'];
		} else {
			$tmp = 'Aucun';
		}
		return $tmp;
	}
	
	function get_labels($nature)
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT id,label
			FROM ".PREFIX."bugstracker_parameters
			WHERE nature=".$nature."
			ORDER BY weight ASC",
			__LINE__, __FILE__);
		return $result;
	}
	function make_options($param, $select_var, $tpl_var='')
	{
		global $Sql, $Template;
		
		if (is_array($select_var)) {
			foreach ($select_var as $item) {
				if ( empty($item['value']) ) $select = 'selected="selected"'; else $select = '';
				$Template->assign_block_vars($item['tpl'], array(
					'VALUE'=> '', 'TEXT' => 'Aucun', 'SELECT' => $select
					));
			}
		} else {
			if ( empty($select_var) ) $select = 'selected="selected"'; else $select = '';
			$Template->assign_block_vars($tpl_var, array(
				'VALUE'=> '', 'TEXT' => 'Aucun', 'SELECT' => $select
				));
		}
		
		$result = $this->get_labels($param);
		while( $row = $Sql->fetch_assoc($result) )
		{
			if (is_array($select_var) ) {
				foreach( $select_var as $item) {
					if ($row['id']==$item['value']) $select = 'selected="selected"'; else $select = '';
					$Template->assign_block_vars($item['tpl'], array(
						'VALUE'=> $row['id'], 'TEXT' => $row['label'], 'SELECT' => $select
						));
				}
			} else {
				if ($row['id']==$select_var) $select = 'selected="selected"'; else $select = '';
				$Template->assign_block_vars($tpl_var, array(
					'VALUE'=> $row['id'], 'TEXT' => $row['label'], 'SELECT' => $select
					));
			}
		}
		$Sql->query_close($result);
		unset($result);
	}
	
	function make_member_options($member_id, $member_group, $tpl_var)
	{
		global $Sql, $Template;
		
		$requete = array();
		$requete[] = '(level=2)';
		if (!empty($member_group)) $requete[] = "(user_groups LIKE '%".$member_group."|%')";
		
		$result = $Sql->query_while("SELECT user_id,login
			FROM ".PREFIX."member
			WHERE ".implode(' OR ',$requete),
			__LINE__, __FILE__);
			
		if ( empty($member_id) ) $select = 'selected="selected"'; else $select = '';
		
		$Template->assign_block_vars($tpl_var, array(
				'VALUE'=> '', 'TEXT' => 'Aucun', 'SELECT' => $select
			));
		while( $row = $Sql->fetch_assoc($result) )
		{
			if ( $row['user_id']==$member_id ) $select = 'selected="selected"'; else $select = '';
			$Template->assign_block_vars($tpl_var, array(
				'VALUE'=> $row['user_id'], 'TEXT' => $row['login'], 'SELECT' => $select
			));
		}
		unset($row);
	}
	
	function get_bug($id, $line, $file)
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT  *, UNIX_TIMESTAMP(updated_date) AS updated_date_unix
									FROM ".PREFIX."bugstracker
									WHERE (id = " . intval($id) .")",
									$line, $file);
		$bug = $Sql->fetch_assoc($result);
		return $bug;
	}
	
	function insert_bug($data, $line, $file)
	{
		global $Sql;
		
		if (!is_array($data)) die('Erreur arg#1 is not an array in : '. __FILE__ . '-' . __LINE__ );
		$params = array();
		foreach ($data as $k => $v) {
			$params[] = $k."='".$v."'";
		}
		$result = $Sql->query_inject("INSERT INTO ".PREFIX."bugstracker SET ".
										implode(', ', $params),
										$line, $file);
		return $result;
	}
	
	function update_bug($id, $data, $line, $file)
	{
		global $Sql;
		
		if (!is_array($data)) die('Erreur data#2 is not an array in : '. __FILE__ . '-' . __LINE__ );
		$params = array();
		foreach ($data as $k => $v) {
			$params[] = $k."='".$v."'";
		}
		$result = $Sql->query_inject("UPDATE ".PREFIX."bugstracker SET ".
										implode(', ', $params).
										" WHERE (id = '".$id."')",
										$line, $file);
		return $result;
	}
	
	function insert_history($data, $line, $file)
	{
		global $Sql;
		
		if (!is_array($data)) die('Erreur arg#1 is not an array line in : '. __FILE__ . '-' . __LINE__);
		$params = array();
		foreach ($data as $k => $v) {
			$params[] = $k."='".$v."'";
		}
		$result = $Sql->query_inject("INSERT INTO ".PREFIX."bugstracker_history SET ".
										implode(', ', $params),
										$line, $file);
		return $result;
	}
	
	function get_components_summary($line, $file)
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT component, status, COUNT(1) AS NB 
							FROM ".PREFIX."bugstracker
							GROUP BY component, status",
							$line, $file);
		$tableau = array();
		while( $row = $Sql->fetch_assoc($result) )
		{
			$tableau[$row['component'].'§'.$this->get_label($row['component'])][$this->get_label($row['status'])] = $row['NB'];
		}
		return $tableau;
	}
	
	function get_all_history($id, $pagination, $pages, $line, $file)
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT *,UNIX_TIMESTAMP(updated_date) AS updated_date_unix
									FROM ".PREFIX."bugstracker_history 
									WHERE (bug_id='".intval($id)."')
									ORDER BY updated_date DESC ". 
									$Sql->limit($pagination->get_first_msg(ITEMS_PER_PAGE, 'p'), ITEMS_PER_PAGE),
									$line, $file);
		$rows = array();
		while ($r = $Sql->fetch_assoc($result))
		{
			$rows[] = $r;
		}
		return $rows;
	}
	
	function get_history_count($id, $line, $file)
	{
		global $Sql;
		
		$nb = $Sql->query("SELECT COUNT(1) AS NB
								FROM ".PREFIX."bugstracker_history
								WHERE (bug_id='".intval($id)."')",
								$line, $file);
		return $nb;
	}
		
}
?>
