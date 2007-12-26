<?php
/*##################################################
 *                               admin_lang_add.php
 *                            -------------------
 *   begin                : Februar 21, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

//On affiche le contenu du repertoire templates, pour lister les thèmes disponibles..

$install = !empty($_GET['install']) ? true : false;
$error = !empty($_GET['error']) ? trim($_GET['error']) : '';

//Si c'est confirmé on execute
if( $install )
{
	//Récupération de l'identifiant du thème.
	$lang = '';
	foreach($_POST as $key => $value)
		if( $value == $LANG['install'] )
			$lang = $key;
			
	$secure = isset($_POST[$lang.'secure']) ? numeric($_POST[$lang.'secure']) : '-1';
	$activ = isset($_POST[$lang.'activ']) ? numeric($_POST[$lang.'activ']) : '0';
		
	$check_lang = $sql->query("SELECT lang FROM ".PREFIX."lang WHERE lang = '" . securit($lang) . "'", __LINE__, __FILE__);	
	if( empty($check_lang) && !empty($lang) )
	{
		$sql->query_inject("INSERT INTO ".PREFIX."lang (lang, activ, secure) VALUES('" . securit($lang) . "', '" . $activ . "', '" .  $secure . "')", __LINE__, __FILE__);
		
		header('location:' . HOST . SCRIPT); 
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_modules_add.php?error=e_lang_already_exist#errorh');
		exit;
	}
}
		
elseif( !empty($_FILES['upload_lang']['name']) ) //Upload et décompression de l'archive Zip/Tar
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../lang/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en écriture, upload possible
	{
		$check_lang = $sql->query("SELECT COUNT(*) FROM ".PREFIX."lang WHERE lang = '" . securit($_FILES['upload_lang']['name']) . "'", __LINE__, __FILE__);
		if( empty($check_lang) )
		{
			include_once('../includes/upload.class.php');
			$upload = new Upload($dir);
			if( $upload->upload_file('upload_lang', '`([a-z0-9_-])+\.(gzip|zip)+`i') )
			{					
				$archive_path = '../lang/' . $upload->filename['upload_lang'];
				//Place à la décompression.
				if( $upload->extension['upload_lang'] == 'gzip' )
				{
					include_once('../includes/pcltar.lib.php');
					if( !$zip_files = PclTarExtract($upload->filename['upload_lang'], '../lang/') )
						$error = $upload->error;
				}
				elseif( $upload->extension['upload_lang'] == 'zip' )
				{
					include_once('../includes/pclzip.lib.php');
					$zip = new PclZip($archive_path);
					if( !$zip_files = $zip->extract(PCLZIP_OPT_PATH, '../lang/', PCLZIP_OPT_SET_CHMOD, 0666) )
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
		'admin_lang_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_lang_add.tpl'
	));
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],		
		'LANG' => $CONFIG['lang'],
		'L_LANG_ADD' => $LANG['lang_add'],	
		'L_UPLOAD_LANG' => $LANG['upload_lang'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
		'L_LANG_MANAGEMENT' => $LANG['lang_management'],
		'L_LANG' => $LANG['lang'],
		'L_NO_LANG_ON_SERV' => $LANG['no_lang_on_serv'],
		'L_RANK' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_INSTALL' => $LANG['install']
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_lang_already_exist', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING);
		
	//On recupère les dossier des thèmes contenu dans le dossier templates.
	$z = 0;
	$rep = '../lang/';
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
			$result = $sql->query_while("SELECT lang 
			FROM ".PREFIX."lang", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				//On recherche les clées correspondante à celles trouvée dans la bdd.
				$key = array_search($row['lang'], $fichier_array);
				if( $key !== false)
					unset($fichier_array[$key]); //On supprime ces clées du tableau.
			}
			$sql->close($result);
			
			foreach($fichier_array as $lang_array => $value_array) //On effectue la recherche dans le tableau.
			{
				$info_lang = @parse_ini_file('../lang/' . $value_array . '/config.ini');
				$template->assign_block_vars('list', array(
					'IDLANG' =>  $value_array,		
					'LANG' =>  $info_lang['name'],	
					'IDENTIFIER' =>  $info_lang['identifier'],
					'AUTHOR' => (!empty($info_lang['author_mail']) ? '<a href="mailto:' . $info_lang['author_mail'] . '">' . $info_lang['author'] . '</a>' : $info_lang['author']),
					'AUTHOR_WEBSITE' => (!empty($info_lang['author_link']) ? '<a href="' . $info_lang['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
					'COMPAT' => $info_lang['compatibility']
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
		$template->assign_block_vars('lang', array(		
		));
	else
		$template->assign_block_vars('no_lang', array(		
		));
	
	$template->pparse('admin_lang_add'); 
}

include_once('../includes/admin_footer.php');

?>