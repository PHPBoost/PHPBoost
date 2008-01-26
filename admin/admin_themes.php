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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');
	
$uninstall = isset($_GET['uninstall']) ? true : false;	
$id = !empty($_GET['id']) ? numeric($_GET['id']) : '0';
$error = !empty($_GET['error']) ? trim($_GET['error']) : ''; 

$theme_tmp = $CONFIG['theme'];
//On recupère toute les informations supplementaires.
$cache->load_file('config', RELOAD_CACHE);

if( isset($_GET['activ']) && !empty($id) ) //Aprobation du thème.
{
	$sql->query_inject("UPDATE ".PREFIX."themes SET activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "' AND theme <> '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	redirect(HOST . SCRIPT . '#t' . $id);	
}
if( isset($_GET['secure']) && !empty($id) ) //Niveau d'autorisation du thème.
{
	$sql->query_inject("UPDATE ".PREFIX."themes SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "' AND theme <> '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif( isset($_POST['valid']) ) //Modification de tout les thèmes.	
{
	$result = $sql->query_while("SELECT id, name, activ, secure
	FROM ".PREFIX."themes
	WHERE activ = 1 AND theme != '" . $CONFIG['theme'] . "'", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$activ = isset($_POST[$row['id'] . 'activ']) ? numeric($_POST[$row['id'] . 'activ']) : '0';
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '0';
		if( $row['activ'] != $activ || $row['secure'] != $secure )
			$sql->query_inject("UPDATE ".PREFIX."modules SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	redirect(HOST . SCRIPT);	
}
elseif( $uninstall ) //Désinstallation.
{
	if( !empty($_POST['valid_del']) )
	{		
		$idtheme = !empty($_POST['idtheme']) ? numeric($_POST['idtheme']) : '0'; 
		$drop_files = !empty($_POST['drop_files']) ? true : false;
		
		$previous_theme = $sql->query("SELECT theme FROM ".PREFIX."themes WHERE id = '" . $idtheme . "'", __LINE__, __FILE__);
		if( $previous_theme != $CONFIG['theme'] && !empty($idtheme) )
		{
			//On met le thème par défaut du site aux membres ayant choisi le thème qui vient d'être supprimé!		
			$sql->query_inject("UPDATE ".PREFIX."member SET user_theme = '" . $CONFIG['theme'] . "' WHERE user_theme = '" . $previous_theme . "'", __LINE__, __FILE__);
				
			//On supprime le theme de la bdd.
			$sql->query_inject("DELETE FROM ".PREFIX."themes WHERE id = '" . $idtheme . "'", __LINE__, __FILE__);
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
				
		$template->set_filenames(array(
			'admin_themes_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_themes_management.tpl'
		));
		
		$template->assign_block_vars('del', array(			
			'IDTHEME' => $idtheme
		));
		
		$template->assign_vars(array(
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

		$template->pparse('admin_themes_management'); 
	}
}		
else
{			
	$template->set_filenames(array(
		'admin_themes_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_themes_management.tpl'
	));
	 
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],	
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
		'L_GUEST' => $LANG['guest'],
		'L_UNINSTALL' => $LANG['uninstall']		
	));
	
	$template->assign_block_vars('main', array(		
	));
		
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG[$get_error], E_USER_NOTICE);
	elseif( !empty($get_error) && isset($LANG[$get_error]) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING);
	 
	
	//On recupère les dossier des thèmes contenu dans le dossier templates	
	$z = 0;
	$rep = '../templates/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$file_array = array();
		$dh = @opendir( $rep);
		while( !is_bool($fichier = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $fichier) )
				$file_array[] = $fichier; //On crée un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier		

		$themes_bdd = array();
		$result = $sql->query_while("SELECT id, theme, activ, secure 
		FROM ".PREFIX."themes", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['theme'], $file_array) !== false)
				$themes_bdd[] = array('id' => $row['id'], 'name' => $row['theme'], 'activ' => $row['activ'], 'secure' => $row['secure']); 		}
		$sql->close($result);
		
		foreach($themes_bdd as $key => $theme) //On effectue la recherche dans le tableau.
		{
			//On selectionne le theme suivant les valeurs du tableau. 
			$info_theme = @parse_ini_file('../templates/' . $theme['name'] . '/config/' . $CONFIG['lang'] . '/config.ini');
			if( !$info_theme ) //Echec, on cherche d'autres langues présentes.
			{
				$lang = '';
				$rep = '../templates/' . $theme['name'] . '/config/';
				$dh = @opendir( $rep);
				while( !is_bool($folder = @readdir($dh)) )
				{	
					//Si c'est un repertoire, on affiche.
					if( !preg_match('`\.`', $folder) )
					{
						$lang = $folder;
						break;
					}
				}	
				@closedir($dh); //On ferme le dossier
				
				$info_theme = @parse_ini_file('../templates/' . $theme['name'] . '/config/' . $lang . '/config.ini');
				if( !$info_theme ) //Echec, on passe ce thème défectueux.
					continue;
			}
			
			$template->assign_block_vars('main.list', array(
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
				'WIDTH' => $info_theme['width']
			));
						
			if( $theme['name'] != $CONFIG['theme'] )
			{
				$value = 
				'<td class="row2" style="text-align:center;">	
						<input type="radio" name="' . $theme['id'] . 'activ" value="1" ' . (($theme['activ'] == 1) ? 'checked="checked"' : '') . ' onchange="document.location = \'admin_themes.php?activ=1&amp;id=' . $theme['id'] . '\'" /> ' . $LANG['yes'] . '
						<input type="radio" name="' . $theme['id'] . 'activ" value="0" ' . (($theme['activ'] == 0) ? 'checked="checked"' : '') . ' onchange="document.location = \'admin_themes.php?activ=0&amp;id=' . $theme['id'] . '\'" /> ' . $LANG['no'] . '
					</td>
					<td class="row2" style="text-align:center;">	
						<select name="' . $theme['id'] . 'secure" onchange="document.location = \'admin_themes.php?secure=\' + this.options[this.selectedIndex].value + \'&amp;id=' . $theme['id'] . '\'">'; 
								
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
					$selected = ($i == $theme['secure']) ? 'selected="selected"' : '';
					$value .= '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>';
				}	
				$value .= '								
					</select>
				</td>
				<td class="row2" style="text-align:center;">
					<input type="submit" name="' . $theme['id'] . '" value="' . $LANG['uninstall'] . '" class="submit" />
				</td>';
					
				$template->assign_block_vars('main.list.not_default', array(
					'VALUE' => $value
				));
			}
			else
				$template->assign_block_vars('main.list.default', array(
				));
			$z++;
		}
	}	
	
	if( $z != 0 )
		$template->assign_block_vars('main.theme', array(		
		));
	else
		$template->assign_block_vars('main.no_theme', array(		
		));
		
	$template->pparse('admin_themes_management'); 
}
$CONFIG['theme'] = $theme_tmp;

require_once('../includes/admin_footer.php');

?>