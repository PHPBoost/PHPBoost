<?php
/*##################################################
 *                               admin_forum_config.php
 *                            -------------------
 *   begin                : October 30, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

$get_id = ( !empty($_GET['id'])) ? numeric($_GET['id']) : '' ;	

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	$result = $Sql->Query_while("SELECT id, special 
	FROM ".PREFIX."ranks", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$name = !empty($_POST[$row['id'] . 'name']) ? securit($_POST[$row['id'] . 'name']) : '';
		$msg = !empty($_POST[$row['id'] . 'msg']) ? numeric($_POST[$row['id'] . 'msg']) : '0';
		$icon = !empty($_POST[$row['id'] . 'icon']) ? securit($_POST[$row['id'] . 'icon']) : '';

		if( !empty($name) && $row['special'] != 1 )
			$Sql->Query_inject("UPDATE ".PREFIX."ranks SET name = '" . $name . "', msg = '" . $msg . "', icon = '" . $icon . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		else
			$Sql->Query_inject("UPDATE ".PREFIX."ranks SET name = '" . $name . "', icon = '" . $icon . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	$Sql->Close($result);

	###### Régénération du cache des rangs #######
	$Cache->Generate_file('ranks');
		
	redirect(HOST . SCRIPT);	
}
elseif( !empty($_GET['del']) && !empty($get_id) ) //Suppression du rang.
{
	//On supprime dans la bdd.
	$Sql->Query_inject("DELETE FROM ".PREFIX."ranks WHERE id = '" . $get_id . "'", __LINE__, __FILE__);	

	###### Régénération du cache des rangs #######
	$Cache->Generate_file('ranks');
	
	redirect(HOST . SCRIPT); 	
}
else //Sinon on rempli le formulaire	 
{	
	$Template->Set_filenames(array(
		'admin_ranks' => '../templates/' . $CONFIG['theme'] . '/admin/admin_ranks.tpl'
	));

	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'L_REQUIRE_RANK_NAME' => $LANG['require_rank_name'],
		'L_REQUIRE_NBR_MSG_RANK' => $LANG['require_nbr_msg_rank'],
		'L_CONFIRM_DEL_RANK' => $LANG['confirm_del_rank'],
		'L_RANKS_MANAGEMENT' => $LANG['rank_management'],
		'L_ADD_RANKS' => $LANG['rank_add'],
		'L_RANK_NAME' => $LANG['rank_name'],
		'L_NBR_MSG' => $LANG['nbr_msg'],
		'L_IMG_ASSOC' => $LANG['img_assoc'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_ADD' => $LANG['add']
	));

	//On recupère les images des groupes
	$rep = '../templates/' . $CONFIG['theme']  . '/images/ranks';
	$j = 0;
	$array_files = array();
	if(  is_dir($rep) ) //Si le dossier existe
	{
		$dh = @opendir( $rep);
		while( !is_bool($fichier = readdir($dh)) )
		{	
			if( $j > 1 && $fichier != 'index.php' && $fichier != 'Thumbs.db' )
				$array_files[] = $fichier; //On crée un array, avec les different fichiers.
			$j++;
		}	
		closedir($dh); //On ferme le dossier
	}	
	
	$result = $Sql->Query_while("SELECT id, name, msg, icon, special
	FROM ".PREFIX."ranks 
	ORDER BY msg", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{				
		if( $row['special'] == 0 )
			$del = '<a href="admin_ranks.php?del=1&amp;id=' . $row['id'] . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" title="" /></a>';
		else
			$del = $LANG['special_rank'];

		$rank_options = '<option value="">--</option>';
		foreach($array_files as $icon)
		{			
			$selected = ($icon == $row['icon']) ? ' selected="selected"' : '';
			$rank_options .= '<option value="' . $icon . '"' . $selected . '>' . $icon . '</option>';
		}
		
		$Template->Assign_block_vars('rank', array(
			'ID' => $row['id'],
			'RANK' => $row['name'],
			'MSG' => ($row['special'] == 0) ? '<input type="text" maxlength="6" size="6" name="' . $row['id'] . 'msg" id="vmsg" value="' . $row['msg'] . '" class="text" />' : $LANG['special_rank'],
			'RANK_OPTIONS' => $rank_options,
			'IMG_RANK' => $row['icon'],
			'DELETE' => $del
		));
	}
	$Sql->Close($result);
	
	$Template->Pparse('admin_ranks');
}

require_once('../includes/admin_footer.php');

?>