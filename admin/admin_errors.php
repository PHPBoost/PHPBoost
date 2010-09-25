<?php
/*##################################################
 *                               admin_errors.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$all = !empty($_GET['all']) ? true : false;

$Template->set_filenames(array(
	'admin_errors_management'=> 'admin/admin_errors_management.tpl'
));

$file_path = '../cache/error.log';

if (!empty($_POST['erase']))
{
	$error_log_file = new File($file_path);
	try
	{
		$error_log_file->delete();
	}
	catch (IOException $exception)
	{
		echo $exception->getMessage();
	}
}

$Template->add_lang(LangLoader::get('admin-errors-Common'));
$Template->assign_vars(array(
	'L_ERRORS' => $LANG['errors'],
	'L_DESC' => $LANG['description'],
	'L_DATE' => $LANG['date'],
	'L_ERASE_RAPPORT' => $LANG['erase_rapport'],
	'L_ERASE_RAPPORT_EXPLAIN' => $LANG['final'],
	'L_ERASE' => $LANG['erase']
));

if (is_file($file_path) && is_readable($file_path)) //Fichier accessible en lecture
{
	$handle = @fopen($file_path, 'r');
	if ($handle) 
	{
		$array_errinfo = array();
		$i = 1;
		while (!feof($handle)) 
		{
			$buffer = fgets($handle);
			switch ($i)
			{
				case 1:
				$errinfo['errdate'] = $buffer;
				break;
				case 2:
				$errinfo['errno'] = $buffer;
				break;
				case 3:
				$errinfo['errmsg'] = $buffer;
				break;
				case 4:
				$errinfo['errstacktrace'] = $buffer;
				$i = 0;	
				$array_errinfo[] = array(
				'errclass' => Errors::get_errno_class($errinfo['errno']), 
				'errmsg' => $errinfo['errmsg'], 
				'errstacktrace'=> $errinfo['errstacktrace'], 
				'errdate' => $errinfo['errdate']
				);
				break;	
			}
			$i++;						
		}
		@fclose($handle);
		
		$images = array(
			'error_unknow' => 'question',
			'error_notice' => 'notice',
			'error_warning' => 'important',
			'error_fatal' => 'stop'
		);
		
		//Tri en sens inverse car enregistrement à la suite dans le fichier de log
		krsort($array_errinfo);
		$i = 0;
		foreach ($array_errinfo as $key => $errinfo)
		{
			$Template->assign_block_vars('errors', array(
				'IMG' => $images[$errinfo['errclass']],
				'DATE' => $errinfo['errdate'],
				'CLASS' => $errinfo['errclass'],
				'ERROR_TYPE' => LangLoader::get_message(str_replace('error_', 'e_', $errinfo['errclass']), 'errors'),
				'ERROR_MESSAGE' => strip_tags($errinfo['errmsg'], '<br>'),
				'ERROR_STACKTRACE' => strip_tags($errinfo['errstacktrace'], '<br>')
			));
			$i++;
			
			if ($i > 15 && !$all)
			{
				break;
			}
		}
	}
	else
	{
		$Template->assign_block_vars('errors', array(
			'TYPE' => '&nbsp;',
			'DESC' => '',
			'FILE' => '',
			'LINE' => '',
			'DATE' => ''
		));
	}
}

$Template->pparse('admin_errors_management');

require_once('../admin/admin_footer.php');

?>