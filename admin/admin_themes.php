<?php
/*##################################################
 *                               admin_themes.php
 *                            -------------------
 *   begin                : June 29, 2005
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
###################################################*/

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');
	
$uninstall = isset($_GET['uninstall']) ? true : false;	
$edit = isset($_GET['edit']) ? true : false;	
$id = retrieve(GET, 'id', 0);

$theme_tmp = $CONFIG['theme'];
//On recupère toute les informations supplementaires.
$Cache->Load_file('config', RELOAD_CACHE);

if( isset($_GET['activ']) && !empty($id) ) //Aprobation du thème.
{
	$Sql->Query_inject("UPDATE ".PREFIX."themes SET activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "' AND theme <> '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif( isset($_GET['secure']) && !empty($id) ) //Niveau d'autorisation du thème.
{
	$Sql->Query_inject("UPDATE ".PREFIX."themes SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "' AND theme <> '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif( isset($_POST['valid']) ) //Modification de tout les thèmes.	
{
	$result = $Sql->Query_while("SELECT id, name, activ, secure
	FROM ".PREFIX."themes
	WHERE activ = 1 AND theme != '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$activ = retrieve(POST, $row['id'] . 'activ', 0);
		$secure = retrieve(POST, $row['id'] . 'secure', 0);
		if( $row['activ'] != $activ || $row['secure'] != $secure )
			$Sql->Query_inject("UPDATE ".PREFIX."modules SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	redirect(HOST . SCRIPT);	
}
elseif( $edit && !empty($id) ) //Edition
{
	if( isset($_POST['valid_edit']) ) //Modication de la configuration du thème.
	{
		$left_column = !empty($_POST['left_column']) ? 1 : 0; 
		$right_column = !empty($_POST['right_column']) ? 1 : 0; 
		
		$Sql->Query_inject("UPDATE ".PREFIX."themes SET left_column = '" . $left_column . "', right_column = '" . $right_column . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Régénération du cache.
		$Cache->Generate_file('themes');
		
		redirect(HOST . SCRIPT . '#t' . $id);	
	}
	else
	{
		$Template->Set_filenames(array(
			'admin_themes_management'=> 'admin/admin_themes_management.tpl'
		));
		
		//Récupération des configuration dans la base de données.
		$config_theme = $Sql->Query_array("themes", "theme", "left_column", "right_column", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//On récupère la configuration du thème.
		$info_theme = load_ini_file('../templates/' . $config_theme['theme'] . '/config/', $CONFIG['lang']);
			
		$Template->Assign_vars(array(
			'C_EDIT_THEME' => true,
			'IDTHEME' => $id,
			'THEME_NAME' => $info_theme['name'],
			'LEFT_COLUMN_ENABLED' => $config_theme['left_column'] ? 'checked="ckecked"' : '',
			'RIGHT_COLUMN_ENABLED' => $config_theme['right_column'] ? 'checked="ckecked"' : '',
			'L_THEME_ADD' => $LANG['theme_add'],	
			'L_THEME_MANAGEMENT' => $LANG['theme_management'],
			'L_THEME' => $LANG['theme'],
			'L_LEFT_COLUMN' => $LANG['activ_left_column'],
			'L_RIGHT_COLUMN' => $LANG['activ_right_column'],
			'L_RESET' => $LANG['reset'],
			'L_UPDATE' => $LANG['update']
		));

		$Template->Pparse('admin_themes_management'); 
	}
}
elseif( $uninstall ) //Désinstallation.
{
	if( !empty($_POST['valid_del']) )
	{		
		$idtheme = retrieve(POST, 'idtheme', 0); 
		$drop_files = !empty($_POST['drop_files']) ? true : false;
		
		$previous_theme = $Sql->Query("SELECT theme FROM ".PREFIX."themes WHERE id = '" . $idtheme . "'", __LINE__, __FILE__);
		if( $previous_theme != $CONFIG['theme'] && !empty($idtheme) )
		{
			//On met le thème par défaut du site aux membres ayant choisi le thème qui vient d'être supprimé!		
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_theme = '" . $CONFIG['theme'] . "' WHERE user_theme = '" . $previous_theme . "'", __LINE__, __FILE__);
				
			//On supprime le theme de la bdd.
			$Sql->Query_inject("DELETE FROM ".PREFIX."themes WHERE id = '" . $idtheme . "'", __LINE__, __FILE__);
		}
		else
			redirect(HOST . DIR . '/admin/admin_themes.php?error=incomplete#errorh');
		
		//Suppression des fichiers du module
		if( $drop_files && !empty($previous_theme) )
		{
			if( !delete_directory('../templates/' . $previous_theme, '../templates/' . $previous_theme) )
				$error = 'files_del_failed';
		}
	
		$error = !empty($error) ? '?error=' . $error : '';
		redirect(HOST . SCRIPT . $error);
	}
	else
	{
		//Récupération de l'identifiant du thème.
		$idtheme = '';
		foreach($_POST as $key => $value)
			if( $value == $LANG['uninstall'] )
				$idtheme = $key;
				
		$Template->Set_filenames(array(
			'admin_themes_management'=> 'admin/admin_themes_management.tpl'
		));
		
		$Template->Assign_vars(array(
			'C_DEL_THEME' => true,
			'IDTHEME' => $idtheme,
			'THEME' => $CONFIG['theme'],
			'L_THEME_ADD' => $LANG['theme_add'],	
			'L_THEME_MANAGEMENT' => $LANG['theme_management'],
			'L_DEL_THEME' => $LANG['del_theme'],
			'L_DEL_FILE' => $LANG['del_theme_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_DELETE' => $LANG['delete']
		));

		$Template->Pparse('admin_themes_management'); 
	}
}		
else
{			
	$Template->Set_filenames(array(
		'admin_themes_management'=> 'admin/admin_themes_management.tpl'
	));
	 
	$Template->Assign_vars(array(
		'C_THEME_MAIN' => true,
		'THEME' => $CONFIG['theme'],	
		'LANG' => $CONFIG['lang'],	
		'L_THEME_ADD' => $LANG['theme_add'],	
		'L_THEME_MANAGEMENT' => $LANG['theme_management'],
		'L_THEME_ON_SERV' => $LANG['theme_on_serv'],
		'L_THEME' => $LANG['theme'],
		'L_PREVIEW' => $LANG['preview'],
		'L_EXPLAIN_DEFAULT_THEME' => $LANG['explain_default_theme'],
		'L_NO_THEME_ON_SERV' => $LANG['no_theme_on_serv'],
		'L_RANK' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_XHTML' => $LANG['xhtml_version'],
		'L_CSS' => $LANG['css_version'],
		'L_MAIN_COLOR' => $LANG['main_colors'],
		'L_VARIABLE_WIDTH' => $LANG['exensible'],
		'L_WIDTH' => $LANG['width'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_GUEST' => $LANG['guest'],
		'L_EDIT' => $LANG['edit'],
		'L_UNINSTALL' => $LANG['uninstall']		
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG[$get_error], E_USER_NOTICE);
	elseif( !empty($get_error) && isset($LANG[$get_error]) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	 
	
	//On recupère les dossier des thèmes contenu dans le dossier templates	
	$z = 0;
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$dir_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( strpos($dir, '.') === false )
				$dir_array[] = $dir; //On crée un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier		

		$themes_bdd = array();
		$result = $Sql->Query_while("SELECT id, theme, activ, secure 
		FROM ".PREFIX."themes", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['theme'], $dir_array) !== false)
				$themes_bdd[] = array('id' => $row['id'], 'name' => $row['theme'], 'activ' => $row['activ'], 'secure' => $row['secure']); 		}
		$Sql->Close($result);
		
		$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		foreach($themes_bdd as $key => $theme) //On effectue la recherche dans le tableau.
		{
			//On selectionne le theme suivant les valeurs du tableau. 
			$info_theme = load_ini_file('../templates/' . $theme['name'] . '/config/', $CONFIG['lang']);
			
			$options = '';
			for($i = -1 ; $i <= 2 ; $i++) //Rang d'autorisation.
			{
				$selected = ($i == $theme['secure']) ? 'selected="selected"' : '';
				$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
			}	
			
			$default_theme = ($theme['name'] == $CONFIG['theme']);
			$Template->Assign_block_vars('list', array(
				'C_THEME_DEFAULT' => $default_theme ? true : false,
				'C_THEME_NOT_DEFAULT' => !$default_theme ? true : false,
				'IDTHEME' =>  $theme['id'],		
				'THEME' =>  $info_theme['name'],				
				'ICON' => $theme['name'],
				'VERSION' => $info_theme['version'],
				'AUTHOR' => (!empty($info_theme['author_mail']) ? '<a href="mailto:' . $info_theme['author_mail'] . '">' . $info_theme['author'] . '</a>' : $info_theme['author']),
				'AUTHOR_WEBSITE' => (!empty($info_theme['author_link']) ? '<a href="' . $info_theme['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
				'DESC' => $info_theme['info'],
				'COMPAT' => $info_theme['compatibility'],
				'HTML_VERSION' => $info_theme['html_version'],
				'CSS_VERSION' => $info_theme['css_version'],
				'MAIN_COLOR' => $info_theme['main_color'],
				'VARIABLE_WIDTH' => ($info_theme['variable_width'] ? $LANG['yes'] : $LANG['no']),
				'WIDTH' => $info_theme['width'],
				'OPTIONS' => $options,
				'THEME_ACTIV' => ($theme['activ'] == 1) ? 'checked="checked"' : '',
				'THEME_UNACTIV' => ($theme['activ'] == 0) ? 'checked="checked"' : ''
			));
			$z++;
		}
	}	
	
	if( $z != 0 )
		$Template->Assign_vars(array(		
			'C_THEME_PRESENT' => true
		));
	else
		$Template->Assign_vars(array(		
			'C_NO_THEME_PRESENT' => true
		));
		
	$Template->Pparse('admin_themes_management'); 
}

$CONFIG['theme'] = $theme_tmp;

require_once('../kernel/admin_footer.php');

?>