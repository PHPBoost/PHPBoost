<?php
/*##################################################
 *                           admin_files_config.php
 *                            -------------------
 *   begin                : July 09, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

if (!empty($_POST['valid']) )
{
	$file_upload_config = FileUploadConfig::load();

	$file_upload_config->set_authorization_enable_interface_files(Authorizations::build_auth_array_from_form(AUTH_FILES));
	$file_upload_config->set_maximum_size_upload(NumberHelper::numeric($_POST['size_limit'], 'float') * 1024);
	$file_upload_config->set_enable_bandwidth_protect(retrieve(POST, 'bandwidth_protect',false));

	$auth_extensions = isset($_POST['auth_extensions']) ? $_POST['auth_extensions'] : array();
	$additional_extension = !empty($_POST['auth_extensions_sup']) ? preg_split('`, ?`', trim($_POST['auth_extensions_sup'])) : '';
	
	if (is_array($additional_extension))
	{	
		foreach ($additional_extension as $extension)
		{
		    //Suppression de tous les caractères interdits dans les extensions
		    $extension = str_replace('-', '', Url::encode_rewrite($extension));
		    
			if ($extension != '' && !isset($auth_extensions[$extension]) && $extension != 'php') 
			{
				array_push($auth_extensions, $extension);
			}
		}
	}
	
	$file_upload_config->set_authorized_extensions($auth_extensions);
	FileUploadConfig::save();
	
	//Régénération du htaccess.
	HtaccessFileCache::regenerate();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$template = new FileTemplate('admin/admin_files_config.tpl');
	
	$file_upload_config = FileUploadConfig::load();
	
	$array_ext_sup = $file_upload_config->get_authorized_extensions();
	$array_extensions_type = array(
		$LANG['files_image'] => array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'tif', 'svg', 'ico'),
		$LANG['files_archives'] => array('rar', 'zip', 'gz'), 
		$LANG['files_text'] => array('txt', 'doc', 'docx', 'pdf', 'ppt', 'xls', 'odt', 'odp', 'ods', 'odg', 'odc', 'odf', 'odb', 'xcf'),
		$LANG['files_media'] => array('flv', 'mp3', 'ogg', 'mpg', 'mov', 'swf', 'wav', 'wmv', 'midi', 'mng', 'qt'), 
		$LANG['files_prog'] => array('c', 'h', 'cpp', 'java', 'py', 'css', 'html'),
		$LANG['files_misc'] => array('ttf', 'tex', 'rtf', 'psd')
	);

	$i = 0;
	$auth_extensions = '';
	foreach ($array_extensions_type as $file_type => $array_extensions)
	{
		$auth_extensions .= '<optgroup label="' . $file_type . '">';
		foreach ($array_extensions as $key => $extension)
		{
			$extension_key = array_search($extension, $file_upload_config->get_authorized_extensions());
			$selected = ($extension_key !== false) ? ' selected="selected"' : '';
			$auth_extensions .= '<option value="' . $extension . '" id="ext' . $i . '"' . $selected . '>' . $extension . '</option>';
			if (isset($array_ext_sup[$extension_key]))
				unset($array_ext_sup[$extension_key]);
			$i++;
		}
		$auth_extensions .= '</optgroup>';
	}

	$template->put_all(array(
		'NBR_EXTENSIONS' => $i,
		'AUTH_FILES' => Authorizations::generate_select(AUTH_FILES, $file_upload_config->get_authorization_enable_interface_files(), array(2 => true)),
		'SIZE_LIMIT' => NumberHelper::round($file_upload_config->get_maximum_size_upload()/1024, 2),
		'BANDWIDTH_PROTECT_ENABLED' => $file_upload_config->get_enable_bandwidth_protect() ? 'checked="checked"' : '',
		'BANDWIDTH_PROTECT_DISABLED' => !$file_upload_config->get_enable_bandwidth_protect() ? 'checked="checked"' : '',
		'AUTH_EXTENSIONS' => $auth_extensions,
		'AUTH_EXTENSIONS_SUP' => implode(', ', $array_ext_sup),
		'L_MB' => $LANG['unit_megabytes'],
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_CONFIG_FILES' => $LANG['files_config'],
		'L_REQUIRE' => $LANG['require'],	
		'L_AUTH_FILES' => $LANG['auth_files'],
		'L_SIZE_LIMIT' => $LANG['size_limit'],	
		'L_BANDWIDTH_PROTECT' => $LANG['bandwidth_protect'],
		'L_BANDWIDTH_PROTECT_EXPLAIN' => $LANG['bandwidth_protect_explain'],
		'L_AUTH_EXTENSIONS' => $LANG['auth_extensions'],
		'L_EXTEND_EXTENSIONS' => $LANG['extend_extensions'],
		'L_EXTEND_EXTENSIONS_EXPLAIN' => $LANG['extend_extensions_explain'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->display(); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>