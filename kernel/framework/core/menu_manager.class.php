<?php
/*##################################################
 *                             menu_manager.class.php
 *                            -------------------
*   begin                : July 08, 2008
 *   copyright          : (C) 2008 Viarre Régis
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('MENU_MODULE', 0x00); //Menu de type module.
define('MENU_LINKS', 0x01); //Menu de type liens.
define('MENU_PERSONNAL', 0x02); //Menu de type menu personnel.
define('MENU_CONTENTS', 0x03); //Menu de type contenu.
define('MENU_STRING_MODE', 0x04); //Menu de type contenu.

class MenuManager
{
	## Public Methods ##
	//Constructeur.
	function MenuManager($type)
	{
		$this->type = $type;
	}
	
	//Retourne le code a mettre en cache.
	function get_cache($name, $contents, $location, $auth, $use_tpl = '0')
	{
		switch($this->type)
		{
			case MENU_MODULE:
				return 'if( $User->check_auth(' . var_export(unserialize($auth), true) . ', AUTH_MENUS) ){' . "\n"
				. "\t" . 'include_once(PATH_TO_ROOT . \'/' . $name . '/' . $contents . "');\n"
				. "\t" . '$MENUS[\'' . $location . '\'] .= $Template->pparse(\'' . str_replace('.php', '', $contents) . '\', TEMPLATE_STRING_MODE);'
				. "\n" . '}';
			
			case MENU_LINKS:
				return var_export($this->display(MENU_STRING_MODE), true);
			
			case MENU_PERSONNAL:
				return 'if( $User->check_auth(' . var_export(unserialize($auth), true) . ', AUTH_MENUS) ){' . "\n"
				. "\t" . 'include_once(PATH_TO_ROOT . \'/menus/' . $contents . "');\n"
				. "\t" . '$MENUS[\'' . $location . '\'] .= $Template->pparse(\'' . str_replace('.php', '', $contents) . '\', TEMPLATE_STRING_MODE);'
				. "\n" . '}';
				
			case MENU_CONTENTS:
				$code = 'if( $User->check_auth(' . var_export(unserialize($auth), true) . ', AUTH_MENUS) ){' . "\n";
				if( $use_tpl == '0' )
					$code .= '$MENUS[\'' . $location . '\'] .= ' . var_export($contents, true) . ';' . "\n";
				else
				{
					switch($location)
					{
						case 'left':
						case 'right':
							$code .= "\$tpl_menus = new Template('/framework/menus/modules_mini/modules_mini.tpl');\n"
							. "\$tpl_menus->assign_vars(array('MODULE_MINI_NAME' => " . var_export($name, true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($contents, true) . "));\n"
							. '$MENUS[\'' . $location . '\'] .= $tpl_menus->parse(TEMPLATE_STRING_MODE);';
						break;
						case 'header':
						case 'subheader':
						case 'topcentral':
						case 'bottomcentral':
						case 'topfooter':
						case 'footer':
							$code .= "\$tpl_menus = new Template('/framework/menus/modules_mini/modules_mini_horizontal.tpl')"
							. "\t\$tpl_menus->assign_vars(array('MODULE_MINI_NAME' => " . var_export($name, true) . ", 'MODULE_MINI_CONTENTS' => " . var_export($contents, true) . "));\n"
							. '$MENUS[\'' . $location . '\'] .= $tpl_menus->parse(TEMPLATE_STRING_MODE);';
							
						break;
					}
				}
				$code .=  "\n"
				. '}';
				return $code;
		}
	}
	
	## Private Methods ##
	
	## Private attributes ##
	var $type = MENU_MODULE; //Type de menu.
}

?>
