<?php
/*##################################################
 *                               admin_themes_add.php
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

##########################admin_themes_add.tpl###########################
//On affiche le contenu du repertoire templates, pour lister les thèmes disponibles..

$install = !empty($_GET['install']) ? true : false;
$error = !empty($_GET['error']) ? trim($_GET['error']) : '';

//Si c'est confirmé on execute
if( $install )
{
	//Récupération de l'identifiant du thème.
	$theme = '';
	foreach($_POST as $key => $value)
		if( $value == $LANG['install'] )
			$theme = $key;
			
	$secure = isset($_POST[$theme.'secure']) ? numeric($_POST[$theme.'secure']) : '-1';
	$activ = isset($_POST[$theme.'activ']) ? numeric($_POST[$theme.'activ']) : '0';
		
	$check_theme = $sql->query("SELECT theme FROM ".PREFIX."themes WHERE theme = '" . securit($theme) . "'", __LINE__, __FILE__);	
	if( empty($check_theme) && !empty($theme) )
	{
		$sql->query_inject("INSERT INTO ".PREFIX."themes (theme, activ, secure) VALUES('" . securit($theme) . "', '" . $activ . "', '" .  $secure . "')", __LINE__, __FILE__);
		
		header('location:' . HOST . SCRIPT); 
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_modules_add.php?error=e_theme_already_exist#errorh');
		exit;
	}
}
		
elseif( !empty($_FILES['upload_theme']['name']) ) //Upload et décompression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../templates/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en écriture, upload possible
	{
		$check_theme = $sql->query("SELECT COUNT(*) FROM ".PREFIX."themes WHERE theme = '" . securit($_FILES['upload_theme']['name']) . "'", __LINE__, __FILE__);
		if( empty($check_theme) && !is_dir('../templates/' . $_FILES['upload_theme']['name']) )
		{
			include_once('../includes/upload.class.php');
			$upload = new Upload($dir);
			if( $upload->upload_file('upload_theme', '`([a-z0-9_-])+\.(gzip|zip)+`i') )
			{					
				$archive_path = '../templates/' . $upload->filename['upload_theme'];
				//Place à la décompression.
				if( $upload->extension['upload_theme'] == 'gzip' )
				{
					include_once('../includes/pcltar.lib.php');
					if( !$zip_files = PclTarExtract($upload->filename['upload_theme'], '../templates/') )
						$error = $upload->error;
				}
				elseif( $upload->extension['upload_theme'] == 'zip' )
				{
					include_once('../includes/pclzip.lib.php');
					$zip = new PclZip($archive_path);
					if( !$zip_files = $zip->extract(PCLZIP_OPT_PATH, '../templates/', PCLZIP_OPT_SET_CHMOD, 0666) )
						$error = $upload->error;
				}
				else
					$error = 'e_upload_invalid_format';
				
				//Suppression de l'archive désormais inutile.
				if( !@unlink($archive_path) )
					$error = 'e_unlink_disabled';
			}
			else
				$error = 'e_upload_error';
		}
		else
			$error = 'e_upload_already_exist';
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	header('location:' . HOST . SCRIPT . $error);	
	exit;
}
else  
{
	$template->set_filenames(array(
		'admin_themes_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_themes_add.tpl'
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],		
		'LANG' => $CONFIG['lang'],
		'L_THEME_ADD' => $LANG['theme_add'],	
		'L_UPLOAD_THEME' => $LANG['upload_theme'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
		'L_THEME_MANAGEMENT' => $LANG['theme_management'],
		'L_THEME' => $LANG['theme'],
		'L_PREVIEW' => $LANG['preview'],
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
		'L_INSTALL' => $LANG['install']
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_theme_already_exist', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING);
		
	//On recupère les dossier des thèmes contenu dans le dossier templates.
	$z = 0;
	$rep = '../templates/';
	if ( is_dir($rep)) //Si le dossier existe
	{
		$dh = @opendir( $rep);
		while ( ! is_bool( $fichier = readdir( $dh ) ) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $fichier) )
				$fichier_array[] = $fichier; //On crée un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier
	
		if( is_array($fichier_array) )
		{			
			$result = $sql->query_while("SELECT theme 
			FROM ".PREFIX."themes", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				//On recherche les clées correspondante à celles trouvée dans la bdd.
				$key = array_search($row['theme'], $fichier_array);
				if( $key !== false)
					unset($fichier_array[$key]); //On supprime ces clées du tableau.
			}
			$sql->close($result);
			
			foreach($fichier_array as $theme_array => $value_array) //On effectue la recherche dans le tableau.
			{
				$info_theme = @parse_ini_file('../templates/' . $value_array . '/config/' . $CONFIG['lang'] . '/config.ini');
				if( !$info_theme ) //Echec, on cherche d'autres langues présentes.
				{
					$lang = '';
					$rep = '../templates/' . $value_array . '/config/';
					$dh = @opendir( $rep);
					while( !is_bool($folder = readdir($dh)) )
					{	
						//Si c'est un repertoire, on affiche.
						if( !preg_match('`\.`', $folder) )
						{
							$lang = $folder;
							break;
						}
					}	
					closedir($dh); //On ferme le dossier
					
					$info_theme = @parse_ini_file('../templates/' . $value_array . '/config/' . $lang . '/config.ini');
					if( !$info_theme ) //Echec, on passe ce thème défectueux.
						continue;
				}
			
				$template->assign_block_vars('list', array(
					'IDTHEME' =>  $value_array,		
					'THEME' =>  $info_theme['name'],			
					'ICON' => $value_array,
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
					
					$selected = ($i == -1) ? 'selected="selected"' : '';
					$template->assign_block_vars('list.select', array(	
						'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
					));
				}
				$z++;
			}
		}				
	}	

	if( $z != 0 )
		$template->assign_block_vars('theme', array(		
		));
	else
		$template->assign_block_vars('no_theme', array(		
		));
	
	$template->pparse('admin_themes_add'); 
}

require_once('../includes/admin_footer.php');

?>