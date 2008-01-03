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
	
	header('location:' . HOST . SCRIPT);	
	exit;
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
	
	header('location:' . HOST . DIR . '/admin/admin_menus.php#m' . $id);	
	exit;
}
elseif( isset($_GET['secure']) && !empty($id) ) //Gestion de la sécurité pour un module donné.
{
	$sql->query_inject("UPDATE ".PREFIX."modules_mini SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	$cache->generate_file('modules_mini');		
	
	header('location:' . HOST . DIR . '/admin/admin_menus.php#m' . $id);	
	exit;
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
	
	header('location:' . HOST . DIR . '/admin/admin_menus.php#m' . $id);	
	exit;
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
	
	header('location:' . HOST . DIR . '/admin/admin_menus.php#m' . $id);	
	exit;
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
		
		header('location:' . HOST . SCRIPT . '#m' . $id);
		exit;
	}
	elseif( !empty($bottom))
	{
		$bottomplus = ($bottom + 1);
		
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = 0 WHERE class = '" . $bottom . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $bottom . "' WHERE class = '" . $bottomplus . "' AND side = '" . $pos . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."modules_mini SET class = '" . $bottomplus . "' WHERE class = 0 AND side = '" . $pos . "'", __LINE__, __FILE__);
		
		###### Régénération du cache des liens #######
		$cache->generate_file('modules_mini');
		
		header('location:' . HOST . SCRIPT . '#m' . $id);
		exit;
	}
}
else	
{		
	$template->set_filenames(array(
		'admin_menus_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_menus_management.tpl'
	));
	
	$template->assign_vars(array(
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
		'L_MENUS_AVAILABLE' => $LANG['menus_available'],
		'L_UPDATE' => $LANG['update'],
		'L_ERASE' => $LANG['erase']
	));
	
	//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
	$info_theme = parse_ini_file('../templates/' . $CONFIG['theme'] . '/config/' . $CONFIG['lang'] . '/config.ini');
		
	$min_idmod_left = $sql->query("SELECT MIN(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 0", __LINE__, __FILE__);
	$max_idmod_left = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 0", __LINE__, __FILE__);
	$min_idmod_right = $sql->query("SELECT MIN(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 1", __LINE__, __FILE__);
	$max_idmod_right = $sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE activ = 1 AND side = 1", __LINE__, __FILE__);
	
	$min_idmod_left_unactiv = $sql->query("SELECT MIN(class) FROM ".PREFIX."modules_mini WHERE side = 0 AND activ = 0", __LINE__, __FILE__);
	$min_idmod_right_unactiv = $sql->query("SELECT MIN(class) FROM ".PREFIX."modules_mini WHERE side = 1 AND activ = 0", __LINE__, __FILE__);

	$left_unactiv = 0;
	$right_unactiv = 0;
	$z = 0;
	$result = $sql->query_while("SELECT id, class, name, contents, side, activ, secure, added
	FROM ".PREFIX."modules_mini
	ORDER BY class, side", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$config = @parse_ini_file('../' . $row['name'] . '/lang/' . $CONFIG['lang'] . '/config.ini');
		if( is_array($config) )
			$row['name'] = !empty($config['name']) ? $config['name'] : '';		
		
		if( $row['activ'] == 1 )
		{
			if( $row['side'] == 0 || !$info_theme['left_column'] )
			{
				$block_position = 'mod_left';//Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
				$top_link = ($min_idmod_left != $row['class']) ? '<a href="admin_menus.php?top=' . $row['class'] . '&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '">
				<img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" /></a>' : '';
				$bottom_link = ($max_idmod_left == $row['class']) ? '<a href="admin_menus.php?right=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" /></a>' : '<a href="admin_menus.php?bot=' . $row['class'] . '&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" /></a>';
			}
			elseif( $row['side'] == 1 && $info_theme['left_column'] )
			{	
				$block_position = 'mod_right';
				$bottom_link = ($max_idmod_right != $row['class']) ? '<a href="admin_menus.php?bot=' . $row['class'] . '&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" /></a>' : '';				
				$top_link = ($min_idmod_right == $row['class']) ? '<a href="admin_menus.php?left=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" /></a>' : '<a href="admin_menus.php?top=' . $row['class'] . '&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" /></a>';
			}

			//Affichage réduit des différents modules.
			$template->assign_block_vars($block_position, array(
				'IDMENU' => $row['id'],
				'NAME' => !empty($admin_link) ? '<a href="' . $admin_link . '">' . $row['name'] . '</a>' : ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" /></a>' : '',
				'TOP' => $top_link,				
				'BOTTOM' => $bottom_link,
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'START_LEFT' => ($row['class'] == $min_idmod_left) ? '<td style="width:156px;vertical-align:top;padding:4px;">' : '',
				'START_RIGHT' => ($row['class'] == $min_idmod_right) ? '<td style="width:156px;vertical-align:top;padding:4px;">' : '',
				'END_LEFT' => ($row['class'] == $max_idmod_left) ? '</td>' : '',
				'END_RIGHT' => ($row['class'] == $max_idmod_right) ? '</td>' : '',
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['side'] . "&amp;unactiv=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
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
				
				$selected = ( $row['secure'] == $i ) ? 'selected="selected"' : '';
				$template->assign_block_vars($block_position . '.select', array(	
					'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
				));
			}
		}	
		else
		{
			//Affichage des menus non affichés
			if( $row['side'] == 0 )
			{	
				$block_position = 'left';
				$left_unactiv++;
			}
			else
			{	
				$block_position = 'right';
				$right_unactiv++;
			}
				
			$template->assign_block_vars('main_' . $block_position, array(
				'IDMENU' => $row['id'],
				'NAME' => ucfirst($row['name']),
				'EDIT' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?edit=1&amp;id=' . $row['id'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" /></a>' : '',
				'DEL' => ($row['added'] == 1) ? '<a href="admin_menus_add.php?del=1&amp;pos=' . $row['side'] . '&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm_menu();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" /></a>' : '',
				'ACTIV_ENABLED' => ($row['activ'] == '1') ? 'selected="selected"' : '',
				'ACTIV_DISABLED' => ($row['activ'] == '0') ? 'selected="selected"' : '',
				'START_LEFT' => ($row['class'] == $min_idmod_left_unactiv) ? ''  : '</td></tr>' . "\n",
				'START_RIGHT' => ($row['class'] == $min_idmod_right_unactiv) ? ''  : '</td></tr>' . "\n",
				'CONTENTS' => !empty($row['contents']) ? '<br />' . second_parse($row['contents']) : '',
				'U_ONCHANGE_ACTIV' => "'admin_menus.php?id=" . $row['id'] . "&amp;pos=" . $row['side'] . "&amp;activ=' + this.options[this.selectedIndex].value",
				'U_ONCHANGE_SECURE' => "'admin_menus.php?id=" . $row['id'] . "&amp;secure=' + this.options[this.selectedIndex].value"
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
				
				$selected = ( $row['secure'] == $i ) ? 'selected="selected"' : '';
				$template->assign_block_vars('main_' . $block_position . '.select', array(	
					'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
				));
			}
		}
	}
	$sql->close($result);
	
	$template->assign_vars(array(
		'END_TABLE_LEFT' => ($left_unactiv != 0) ? '</td></tr>' : '<tr><td>&nbsp;</td></tr>',
		'END_TABLE_RIGHT' => ($right_unactiv != 0) ? '</td></tr>' : '<tr><td>&nbsp;</td></tr>'
	));
	
	for($i = 1; $i < $right_unactiv; $i++)
	{
		$template->assign_block_vars('left_unactiv', array(
			'LEFT_UNACTIV' => '<tr><td style="width:156px;vertical-align:top;padding:4px;">&nbsp;</td></tr>'
		));
	}
	for($i = 1; $i < $left_unactiv; $i++)
	{
		$template->assign_block_vars('right_unactiv', array(
			'RIGHT_UNACTIV' => '<tr><td style="width:156px;vertical-align:top;padding:4px;">&nbsp;</td></tr>'
		));
	}
		
	$template->pparse('admin_menus_management');
}

require_once('../includes/admin_footer.php');

?>