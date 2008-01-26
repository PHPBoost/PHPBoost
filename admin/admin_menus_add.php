<?php
/*##################################################
 *                               admin_menus_add.php
 *                            -------------------
 *   begin                : March 06, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id = ( !empty($_GET['id'])) ? numeric($_GET['id']) : '' ;
$edit = !empty($_GET['edit']) ? true : false;
$id_post = ( !empty($_POST['id'])) ? numeric($_POST['id']) : '' ;
$action = !empty($_POST['action']) ? trim($_POST['action']) : '';
$del = !empty($_GET['del']) ? true : false;
$pos = isset($_GET['pos']) ? numeric($_GET['pos']) : 0;

//Si c'est confirmé on execute
if( $action == 'edit' && !empty($id_post) ) //Modification d'un menu déjà existant.
{	
	$name = securit($_POST['name']);
	$activ = numeric($_POST['activ']);  
	$secure = numeric($_POST['secure']); 
	$contents = parse($_POST['contents'], array(), HTML_UNPROTECT);
	
	$code = "\$template->set_filenames(array(\'modules_mini\' => \'../templates/\' . \$CONFIG[\'theme\'] . \'/modules_mini.tpl\'));
\$template->assign_vars(array(\'MODULE_MINI_NAME\' => \'" . $name . "\', \'MODULE_MINI_CONTENTS\' => \'" . $contents . "\'));
\$template->pparse(\'modules_mini\');";

	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET name = '" . $name . "', code = '" . $code . "', contents = '" . $contents ."', activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id_post);	
}
elseif( $action == 'add' ) //Ajout d'un menu.
{		
	$name = securit($_POST['name']);
	$activ = numeric($_POST['activ']);  
	$secure = numeric($_POST['secure']); 
	$contents = parse($_POST['contents'], array(), HTML_UNPROTECT);	
	$code = "\$template->set_filenames(array(\'modules_mini\' => \'../templates/\' . \$CONFIG[\'theme\'] . \'/modules_mini.tpl\'));
\$template->assign_vars(array(\'MODULE_MINI_NAME\' => \'" . $name . "\', \'MODULE_MINI_CONTENTS\' => \'" . $contents . "\'));
\$template->pparse(\'modules_mini\');";
	$class = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 0", __LINE__, __FILE__);
	
	$sql->query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, code, contents, side, secure, activ, added) VALUES 
	('" . ($class + 1) . "', '" . $name . "', '" . $code . "', '" . $contents ."', 0, '" . $secure . "', '" . $activ . "', 1)", __LINE__, __FILE__);
	
	$cache->generate_file('modules_mini');		
	
	$last_menu_id = $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."modules_mini");
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $last_menu_id);	
}
elseif( !empty($del) && isset($pos) && !empty($id) ) //Suppression du menu.
{
	$previous_class = $sql->query("SELECT class FROM ".PREFIX."modules_mini WHERE id = " . $id, __LINE__, __FILE__);
	$sql->query_inject("DELETE FROM ".PREFIX."modules_mini WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	$result = $sql->query_while("SELECT id
	FROM ".PREFIX."modules_mini	
	WHERE side = " . $pos . " AND activ = 1 AND class > " . $previous_class . "
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php');	
}
else	
{		
	$template->set_filenames(array(
		'admin_menus_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_menus_add.tpl'
	));
	
	$template->assign_vars(array(
		'IDMENU' => $id,
		'ACTION' => ($edit) ? 'edit' : 'add',
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
		'L_ADD_MENUS' => $LANG['menus_add'],
		'L_EXPLAIN_MENUS' => $LANG['menus_explain'],
		'L_ACTION_MENUS' => ($edit) ? $LANG['menus_edit'] : $LANG['menus_add'],
		'L_ACTION' => ($edit) ? $LANG['update'] : $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	if( $edit )
	{
		$menu = $sql->query_array('modules_mini', 'id', 'name', 'contents', 'activ', 'secure', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Affichage réduit des différents modules.
		$template->assign_block_vars('edit', array(
			'NAME' => $menu['name'],
			'ACTIV_ENABLED' => ($menu['activ'] == '1') ? 'selected="selected"' : '',
			'ACTIV_DISABLED' => ($menu['activ'] == '0') ? 'selected="selected"' : '',
			'CONTENTS' => !empty($menu['contents']) ? unparse($menu['contents']) : ''
		));

		//Rang d'autorisation.
		for($i = -1 ; $i <= 2 ; $i++)
		{
			switch($i) 
			{	
				case -1:
					$rank = $LANG['guest'];
				break;					
				case 0:
					$rank = $LANG['member'];
				break;					
				case 1: 
					$rank = $LANG['modo'];
				break;			
				case 2:
					$rank = $LANG['admin'];
				break;						
				default: -1;
			}
			
			$selected = ( $menu['secure'] == $i ) ? 'selected="selected"' : '';
			$template->assign_block_vars('edit.select', array(	
				'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
			));
		}
	}
	else
	{
		//Affichage réduit des différents modules.
		$template->assign_block_vars('add', array(
		));

		//Rang d'autorisation.
		for($i = -1 ; $i <= 2 ; $i++)
		{
			switch($i) 
			{	
				case -1:
					$rank = $LANG['guest'];
				break;					
				case 0:
					$rank = $LANG['member'];
				break;					
				case 1: 
					$rank = $LANG['modo'];
				break;			
				case 2:
					$rank = $LANG['admin'];
				break;						
				default: -1;
			}
			
			$template->assign_block_vars('add.select', array(	
				'RANK' => '<option value="' . $i . '">' . $rank . '</option>'
			));
		}
	}
	
	include_once('../includes/bbcode.php');
	
	$template->pparse('admin_menus_add');
}

require_once('../includes/admin_footer.php');

?>