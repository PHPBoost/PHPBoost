<?php
/*##################################################
 *                                module_mini.php
 *                            -------------------
 *   begin                : April 06, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHPBOOST') !== true)	exit;

if( @!include('../cache/modules_mini.php') )
{
	//Régénération du fichier
	$modules_mini = array();
	$result = $Sql->Query_while("SELECT name, contents, location, secure, added, use_tpl
	FROM ".PREFIX."modules_mini 
	WHERE activ = 1
	ORDER BY location, class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$modules_mini[$row['location']][] = array('name' => $row['name'], 'contents' => $row['contents'], 'secure' => $row['secure'], 'added' => $row['added'], 'use_tpl' => $row['use_tpl']);
	}
	$Sql->Close($result);

	$code = '';
	$array_seek = array('subheader', 'left', 'right', 'topcentral', 'bottomcentral');
	foreach($array_seek as $location)
	{
		$code .= 'if( isset($MODULES_MINI[\'' . $location . '\']) && $MODULES_MINI[\'' . $location . '\'] ){' . "\n";
		foreach($modules_mini[$location] as $location_key => $info)
		{
			if( $info['added'] == '0' )
				$code .= 'if( $Member->Check_level(' . $info['secure'] . ') ){' . $info['contents'] . '}' . "\n";
			else
			{
				if( $info['use_tpl'] == '0' )
					$code .= 'echo ' . var_export($info['contents'], true) . ';' . "\n";
				else
				{
					switch($location)
					{
						case 'left':
						case 'right':
						$code .= 'if( $Member->Check_level(' . $info['secure'] . ') ){' . 
						"\$Template->Set_filenames(array('modules_mini' => '../templates/' . \$CONFIG['theme'] . '/modules_mini.tpl'));\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\$Template->Pparse('modules_mini');" . '}' . "\n";
						break;
						case 'subheader':
						case 'topcentral':
						case 'bottomcentral':
						$code .= 'if( $Member->Check_level(' . $info['secure'] . ') ){' . 
						"\$Template->Set_filenames(array('modules_mini_horizontal' => '../templates/' . \$CONFIG['theme'] . '/modules_mini_horizontal.tpl'));\$Template->Assign_vars(array('MODULE_MINI_NAME' => " . var_export($info['name'], true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($info['contents'], true) . "));\$Template->Pparse('modules_mini_horizontal');" . '}' . "\n";
						break;					
					}						
				}
			}
		}
		$code .= '}' . "\n";
	}
	
	$file_path = '../cache/modules_mini.php';
	if( is_file($file_path) )
		@delete_file($file_path); //Supprime le fichier s'il existe déjà
	$handle = @fopen($file_path, 'w+'); //On crée le fichier avec droit d'écriture et lecture.
	@fwrite($handle, "<?php\n" . $code . "\n?>");
	@fclose($handle);
	//Il est l'heure de vérifier si la génération a fonctionnée.
	if( !is_file($file_path) || filesize($file_path) == '0' )
		$Errorh->Error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
		
	//On inclue une nouvelle fois
	if( @!include('../cache/modules_mini.php') )
		$Errorh->Error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
}

?>