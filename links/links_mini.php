<?php
/*##################################################
 *                                links_mini.php
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

$Template->Set_filenames(array(
	'links_mini'=> 'links/links_mini.tpl'
));

//Inclusion du cache des liens pour éviter une requête inutile.
$Cache->Load_file('links');

//Chargement de la langue du module.
load_module_lang('links');
	
$Template->Assign_vars(array(
	'MODULE_DATA_PATH' => $Template->Module_data_path('links'),
	'L_MENU' => $LANG['menu']
));
	
$i = 0;	
if( isset($_array_link) )
{					
	foreach($_array_link as $array_links)
	{
		if( $Member->Get_attribute('level') >= $array_links['secure'] )
		{
			$Template->Assign_block_vars('links', array(
			));
			
			if( $array_links['sep'] == '1' )
			{
				$Template->Assign_block_vars('links.title', array(
					'NAME' => $array_links['name']
				));
				if( $i > 0 )
				{
					$Template->Assign_block_vars('links.title.bottom', array(					
					));	
				}
			}
			else 
			{
				$Template->Assign_block_vars('links.subtitle', array(
					'NAME' => $array_links['name'],
					'URL' => strpos($array_links['url'], '//') !== false ? $array_links['url'] : $array_links['url'] . SID
				));
			}
			$i++;
		}
	}
}

?>