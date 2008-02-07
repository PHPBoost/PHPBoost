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
$pos = isset($_GET['pos']) ? numeric($_GET['pos']) : 0;

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{	
	$result = $sql->query_while("SELECT id, activ, secure
	FROM ".PREFIX."modules_mini
	ORDER BY side, class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$activ = isset($_POST[$row['id'] . 'activ']) ? numeric($_POST[$row['id'] . 'activ']) : '0';  
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '-1'; 
		
		if( $row['secure'] != $secure || $row['activ'] != $activ )
			$sql->query_inject("UPDATE ".PREFIX."modules_mini SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$sql->close($result);
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . SCRIPT);	
}
elseif( (isset($_GET['unactiv']) || isset($_GET['activ'])) && isset($_GET['pos']) && !empty($id) ) //Gestion de l'activation pour un module donné.
{
	if( numeric($_GET['pos']) == '0' )
		$class = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE side = 0 AND activ = 1", __LINE__, __FILE__);
	else
		$class = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE side = 1 AND activ = 1", __LINE__, __FILE__);
	
	$class = isset($_GET['activ']) ? ($class + 1) : $class;
	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = " . $class . ", activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	if( isset($_GET['activ']) )
	{
		$previous_class = $sql->query("SELECT class FROM ".PREFIX."modules_mini WHERE id = " . $id, __LINE__, __FILE__);
		
		$result = $sql->query_while("SELECT id
		FROM ".PREFIX."modules_mini
		WHERE side = " . numeric($_GET['pos']) . " AND activ = 1 AND class > " . $previous_class . "
		ORDER BY class", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
			$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( isset($_GET['secure']) && !empty($id) ) //Gestion de la sécurité pour un module donné.
{
	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( isset($_GET['left']) && !empty($id) ) //Gestion du placement pour un module donné.
{
	$previous_class = $sql->query("SELECT class FROM ".PREFIX."modules_mini WHERE id = " . $id, __LINE__, __FILE__);
	$max_idmod_left = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE side = 0 AND activ = 1", __LINE__, __FILE__);
	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET side = 0, class = " . ($max_idmod_left + 1) . " WHERE id = " . $id, __LINE__, __FILE__);		
	
	//Réordonnement du classement.
	$result = $sql->query_while("SELECT id
	FROM ".PREFIX."modules_mini
	WHERE side = 1 AND activ = 1 AND class > " . $previous_class . "
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( isset($_GET['right']) && !empty($id) ) //Gestion du placement pour un module donné.
{
	$previous_class = $sql->query("SELECT class FROM ".PREFIX."modules_mini WHERE id = " . $id, __LINE__, __FILE__);
	$max_idmod_right = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 1", __LINE__, __FILE__);
	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET side = 1, class = " . $max_idmod_right . " + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	//Réordonnement du classement.
	$result = $sql->query_while("SELECT id
	FROM ".PREFIX."modules_mini	
	WHERE side = 0 AND activ = 1 AND class > " . $previous_class . "
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = class - 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	
	$cache->generate_file('modules_mini');		
	
	redirect(HOST . DIR . '/admin/admin_menus.php#m' . $id);	
}
elseif( !empty($top) || !empty($bottom) ) //Monter/descendre.
{
	if( !empty($top) )
	{	
		$topmoins = ($top - 1);
		
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = 0 WHERE class = '" . $top . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $top . "' WHERE class = '" . $topmoins . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $topmoins . "' WHERE class = 0 AND side = '" . $pos . "'", __LINE__, __FILE__);
		
		###### Régénération du cache des liens #######
		$cache->generate_file('modules_mini');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
	elseif( !empty($bottom))
	{
		$bottomplus = ($bottom + 1);
		
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = 0 WHERE class = '" . $bottom . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $bottom . "' WHERE class = '" . $bottomplus . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $bottomplus . "' WHERE class = 0 AND side = '" . $pos . "'", __LINE__, __FILE__);
		
		###### Régénération du cache des liens #######
		$cache->generate_file('modules_mini');
		
		redirect(HOST . SCRIPT . '#m' . $id);
	}
}
else	
{		
	$template->set_filenames(array(
		'admin_menus_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_menus_management.tpl'
	));
	
	//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
	$info_theme = parse_ini_file('../templates/' . $CONFIG['theme'] . '/config/' . $CONFIG['lang'] . '/config.ini');
		
	$i = 0;
	$array_auth_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $sql->query_while("SELECT id, class, name, contents, side, activ, secure, added
	FROM ".PREFIX."modules_mini
	ORDER BY class, side", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$config = @parse_ini_file('../' . $row['name'] . '/lang/' . $CONFIG['lang'] . '/config.ini');
		if( is_array($config) )
			$row['name'] = !empty($config['name']) ? $config['name'] : '';		
		
		//Rangs d'autorisation.
		$ranks = '';
		foreach($array_auth_ranks as $rank => $name)
		{
			$selected = ($row['secure'] == $rank) ? 'selected="selected"' : '';
			$ranks .= '<option value="' . $rank . '" ' . $selected . '>' . $name . '</option>';
		}
			
		if( ($row['side'] == 0 || !$info_theme['right_column']) && $info_theme['left_column'] )
			$block_position = 'mod_left'; //Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
		elseif( ($row['side'] == 1 || !$info_theme['left_column']) && $info_theme['right_column'] )
			$block_position = 'mod_right';
				
		if( $row['activ'] == 1 && !empty($block_position) )
		{
			//Affichage réduit des différents modules.
			$template->assign_block_vars($block_position, array(
				'IDMENU' => $row['id'],
				'NAME' => !empty($admin_link) ? '<a href="' . $admin_link . '">' . $row['name'] . '</a>' : ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'RANK' => $ranks,
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['side'] . "&amp;unactiv=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
			));
		}	
		else //Affichage des menus désactivés
		{
			$template->assign_block_vars('mod_main', array(
				'IDMENU' => $row['id'],
				'NAME' => ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" /></a>' : '',
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'RANK' => $ranks,
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['side'] . "&amp;activ=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
			));
		}
		$i++;
	}
	$sql->close($result);
	
	$template->assign_vars(array(
		'LEFT_COLUMN' => $info_theme['left_column'],
		'RIGHT_COLUMN' => $info_theme['right_column'],
		'L_CONFIRM_DEL_MENU' => $LANG['confirm_del_menu'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_ACTIVATION' => $LANG['activation'],
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
		'L_ERASE' => $LANG['erase']
	));
	
	$template->pparse('admin_menus_management');
}

require_once('../includes/admin_footer.php');

?>