<?php
/*##################################################
 *                               admin_menus.php
 *                            -------------------
 *   begin                : March, 05 2007
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
$id_post = ( !empty($_POST['id'])) ? numeric($_POST['id']) : '' ;

$top = !empty($_GET['top']) ? securit($_GET['top']) : '' ;
$bottom = !empty($_GET['bot']) ? securit($_GET['bot']) : '' ;
$move = isset($_GET['move']) ? securit($_GET['move']) : '';

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{	
	$result = $Sql->Query_while("SELECT id, activ, secure
	FROM ".PREFIX."modules_mini", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$activ = isset($_POST[$row['id'] . 'activ']) ? numeric($_POST[$row['id'] . 'activ']) : '0';  
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '-1'; 
		
		if( $row['secure'] != $secure || $row['activ'] != $activ )
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->Close($result);
	
	$Cache->Generate_file('modules_mini');
	
	redirect(HOST . SCRIPT);	
}
elseif( isset($_GET['activ']) && !empty($id) ) //Gestion de l'activation pour un module donné.
{
	$previous_location = $Sql->Query("SELECT location FROM ".PREFIX."modules_mini WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$max_class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE location = '" . $previous_location . "' AND activ = 1", __LINE__, __FILE__);
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . ($max_class + 1) . "', activ = 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$Cache->Generate_file('modules_mini');		
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( isset($_GET['unactiv']) && !empty($id) ) //Gestion de l'inactivation pour un module donné.
{
	$info_menu = $Sql->Query_array("modules_mini", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = 0, activ = 0 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . $info_menu['location'] . "'", __LINE__, __FILE__);

	$Cache->Generate_file('modules_mini');		
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( isset($_GET['secure']) && !empty($id) ) //Gestion de la sécurité pour un module donné.
{
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( !empty($move) && !empty($id) ) //Gestion de la sécurité pour un module donné.
{
	$info_menu = $Sql->Query_array("modules_mini", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	$max_class = $Sql->Query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE location = '" . $move . "' AND activ = 1", __LINE__, __FILE__);
	
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE class > '" . $info_menu['class'] . "' AND location = '" . $info_menu['location'] . "' AND activ = 1", __LINE__, __FILE__);	
	$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . ($max_class + 1) . "', location = '" . $move . "', activ = 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	
	$Cache->Generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( ($top || $bottom) && !empty($id) ) //Monter/descendre.
{
	if( $top )
	{	
		$info_menu = $Sql->Query_array("modules_mini", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$top = $Sql->Query("SELECT id FROM ".PREFIX."modules_mini WHERE location = '" . $info_menu['location'] . "' AND class = '" . ($info_menu['class'] - 1) . "' AND activ = 1", __LINE__, __FILE__);
		if( !empty($top) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class + 1 WHERE id = '" . $top . "'", __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		$Cache->Generate_file('modules_mini');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
	elseif( $bottom )
	{
		$info_menu = $Sql->Query_array("modules_mini", "class", "location", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$bottom = $Sql->Query("SELECT id FROM ".PREFIX."modules_mini WHERE location = '" . $info_menu['location'] . "' AND class = '" . ($info_menu['class'] + 1) . "' AND activ = 1", __LINE__, __FILE__);
		if( !empty($bottom) )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $bottom . "'", __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."modules_mini SET class = class + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		
		$Cache->Generate_file('modules_mini');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
}
else	
{		
	$Template->Set_filenames(array(
		'admin_menus_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_menus_management.tpl'
	));
	
	//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
	$info_theme = load_ini_file('../templates/' . $CONFIG['theme'] . '/config/', $CONFIG['lang']);
	
	$array_max = array();
	$result = $Sql->Query_while("SELECT MAX(class) AS max, location
	FROM ".PREFIX."modules_mini
	GROUP BY location
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$array_max[$row['location']] = $row['max'];
	}
	$Sql->Close($result);
	
	$i = 0;
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $Sql->Query_while("SELECT id, class, name, contents, location, activ, secure, added
	FROM ".PREFIX."modules_mini
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$config = load_ini_file('../' . $row['name'] . '/lang/', $CONFIG['lang']);
		if( is_array($config) )
			$row['name'] = !empty($config['name']) ? $config['name'] : '';		
		
		//Rangs d'autorisation.
		$ranks = '';
		foreach($array_auth_ranks as $rank => $name)
		{
			$selected = ($row['secure'] == $rank) ? 'selected="selected"' : '';
			$ranks .= '<option value="' . $rank . '" ' . $selected . '>' . $name . '</option>';
		}
		
		$block_position = $row['location'];		
		if( ($row['location'] == 'left' || !$info_theme['right_column']) && $info_theme['left_column'] )
			$block_position = 'left'; //Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
		elseif( ($row['location'] == 'right' || !$info_theme['left_column']) && $info_theme['right_column'] )
			$block_position = 'right';
				
		if( $row['activ'] == 1 && !empty($block_position) )
		{
			//Affichage réduit des différents modules.
			$Template->Assign_block_vars('mod_' . $block_position, array(
				'IDMENU' => $row['id'],
				'NAME' => !empty($admin_link) ? '<a href="' . $admin_link . '">' . $row['name'] . '</a>' : ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'RANK' => $ranks,
				'UP' => ($row['class'] > 1) ? '<a href="admin_menus.php?top=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
				'DOWN' => ($array_max[$row['location']] != $row['class']) ? '<a href="admin_menus.php?bot=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" /></a>' : '<div style="float:left;width:32px;">&nbsp;</div>',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;unactiv=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
			));
		}	
		else //Affichage des menus désactivés
		{
			$Template->Assign_block_vars('mod_main', array(
				'IDMENU' => $row['id'],
				'NAME' => ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['location'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'RANK' => $ranks,
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['location'] . "&amp;activ=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
			));
		}
		$i++;
	}
	$Sql->Close($result);
	
	$colspan = 1;
	$colspan += (int)$info_theme['right_column'];
	$colspan += (int)$info_theme['left_column'];
	
	$Template->Assign_vars(array(
		'COLSPAN' => $colspan,
		'LEFT_COLUMN' => $info_theme['left_column'],
		'RIGHT_COLUMN' => $info_theme['right_column'],
		'L_INDEX' => $LANG['index'],
		'L_CONFIRM_DEL_MENU' => $LANG['confirm_del_menu'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ACTIVATION' => $LANG['activation'],
		'L_MOVETO' => $LANG['moveto'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
		'L_ADD_MENUS' => $LANG['menus_add'],
		'L_SUB_HEADER' => $LANG['menu_subheader'],
		'L_LEFT_MENU' => $LANG['menu_left'],
		'L_RIGHT_MENU' => $LANG['menu_right'],
		'L_TOP_CENTRAL_MENU' => $LANG['menu_top_central'],
		'L_BOTTOM_CENTRAL_MENU' => $LANG['menu_bottom_central'],
		'L_MENUS_AVAILABLE' => ($i > 0) ? $LANG['available_menus'] : $LANG['no_available_menus'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->Pparse('admin_menus_management');
}

require_once('../includes/admin_footer.php');

?>
