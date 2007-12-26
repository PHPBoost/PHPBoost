<?php
/*##################################################
 *                               admin_lang.php
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
	
$uninstall = isset($_GET['uninstall']) ? true : false;	
$id = !empty($_GET['id']) ? numeric($_GET['id']) : '0';
$error = !empty($_GET['error']) ? trim($_GET['error']) : ''; 

if( isset($_GET['activ']) && !empty($id) ) //Activation
{
	$sql->query_inject("UPDATE ".PREFIX."lang SET activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "' AND lang != '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	header('location:' . HOST . SCRIPT . '#t' . $id);	
	exit;
}
if( isset($_GET['secure']) && !empty($id) ) //Changement de niveau d'autorisation.
{
	$sql->query_inject("UPDATE ".PREFIX."lang SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "' AND lang != '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	header('location:' . HOST . SCRIPT . '#t' . $id);	
	exit;
}
elseif( isset($_POST['valid']) ) //Mise à jour
{
	$result = $sql->query_while("SELECT id, name, activ, secure
	FROM ".PREFIX."lang
	WHERE activ = 1 AND lang != '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$activ = isset($_POST[$row['id'] . 'activ']) ? numeric($_POST[$row['id'] . 'activ']) : '0';
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '0';
		if( $row['activ'] != $activ || $row['secure'] != $secure )
			$sql->query_inject("UPDATE ".PREFIX."modules SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	header('location:' . HOST . SCRIPT);	
	exit;
}
elseif( $uninstall ) //Désinstallation.
{
	if( !empty($_POST['valid_del']) )
	{		
		$idlang = !empty($_POST['idlang']) ? numeric($_POST['idlang']) : '0'; 
		$drop_files = !empty($_POST['drop_files']) ? true : false;
		
		$previous_lang = $sql->query("SELECT lang FROM ".PREFIX."lang WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		if( $previous_lang != $CONFIG['lang'] && !empty($idlang) && !empty($previous_lang) )
		{
			//On met le thème par défaut du site aux membres ayant choisi le thème qui vient d'être supprimé!		
			$sql->query_inject("UPDATE ".PREFIX."member SET user_lang = '" . $CONFIG['lang'] . "' WHERE user_lang = '" . $previous_lang . "'", __LINE__, __FILE__);
				
			//On supprime le lang de la bdd.
			$sql->query_inject("DELETE FROM ".PREFIX."lang WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		}
		else
		{
			header('location:' . HOST . DIR . '/admin/admin_lang.php?error=incomplete#errorh');
			exit;
		}
		
		//Suppression des fichiers du module
		if( $drop_files && !empty($previous_lang) )
		{
			if( !delete_directory('../lang/' . $previous_lang, '../lang/' . $previous_lang) )
				$error = 'files_del_failed';
		}
	
		$error = !empty($error) ? '?error=' . $error : '';
		header('location:' . HOST . SCRIPT . $error);
		exit;		
	}
	else
	{
		//Récupération de l'identifiant du thème.
		$idlang = '';
		foreach($_POST as $key => $value)
			if( $value == $LANG['uninstall'] )
				$idlang = $key;
				
		$template->set_filenames(array(
			'admin_lang_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_lang_management.tpl'
		));
		
		$template->assign_block_vars('del', array(			
			'IDLANG' => $idlang
		));
		
		$template->assign_vars(array(
			'THEME' => $CONFIG['theme'],
			'L_LANG_ADD' => $LANG['lang_add'],	
			'L_LANG_MANAGEMENT' => $LANG['lang_management'],
			'L_DEL_LANG' => $LANG['del_lang'],
			'L_DEL_FILE' => $LANG['del_lang_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_DELETE' => $LANG['delete']
		));

		$template->pparse('admin_lang_management'); 
	}
}		
else
{			
	$template->set_filenames(array(
		'admin_lang_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_lang_management.tpl'
	));
	 
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],		
		'L_LANG_ADD' => $LANG['lang_add'],	
		'L_LANG_MANAGEMENT' => $LANG['lang_management'],
		'L_LANG_ON_SERV' => $LANG['lang_on_serv'],
		'L_LANG' => $LANG['lang'],
		'L_EXPLAIN_DEFAULT_LANG' => $LANG['explain_default_lang'],
		'L_NO_LANG_ON_SERV' => $LANG['no_lang_on_serv'],
		'L_RANK' => $LANG['rank'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_ACTIV' => $LANG['activ'],
		'L_DESC' => $LANG['description'],
		'L_YES' => $LANG['yes'],
		'L_GUEST' => $LANG['guest'],
		'L_UNINSTALL' => $LANG['uninstall']		
	));
	
	$template->assign_block_vars('main', array(		
	));
		
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG[$get_error], E_USER_NOTICE, NO_LINE_ERROR, NO_FILE_ERROR, 'main.');
	elseif( !empty($get_error) && isset($LANG[$get_error]) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING, NO_LINE_ERROR, NO_FILE_ERROR, 'main.');
	 
	
	//On recupère les dossier des thèmes contenu dans le dossier templates	
	$z = 0;
	$rep = '../lang/';
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

		$lang_bdd = array();
		$result = $sql->query_while("SELECT id, lang, activ, secure 
		FROM ".PREFIX."lang", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['lang'], $file_array) !== false)
				$lang_bdd[] = array('id' => $row['id'], 'name' => $row['lang'], 'activ' => $row['activ'], 'secure' => $row['secure']); //On supprime ces clées du tableau.
		}
		$sql->close($result);
		
		foreach($lang_bdd as $key => $lang) //On effectue la recherche dans le tableau.
		{
			//On selectionne le lang suivant les valeurs du tableau. 
			$info_lang = @parse_ini_file('../lang/' . $lang['name'] . '/config.ini');
			
			$template->assign_block_vars('main.list', array(
				'IDLANG' =>  $lang['id'],		
				'LANG' =>  $info_lang['name'],
				'IDENTIFIER' =>  $info_lang['identifier'],
				'AUTHOR' => (!empty($info_lang['author_mail']) ? '<a href="mailto:' . $info_lang['author_mail'] . '">' . $info_lang['author'] . '</a>' : $info_lang['author']),
				'AUTHOR_WEBSITE' => (!empty($info_lang['author_link']) ? '<a href="' . $info_lang['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
				'COMPAT' => $info_lang['compatibility']
			));
						
			if( $lang['name'] != $CONFIG['lang'] )
			{
				$value = 
				'<td class="row2" style="text-align:center;">	
						<input type="radio" name="' . $lang['id'] . 'activ" value="1" ' . (($lang['activ'] == 1) ? 'checked="checked"' : '') . ' onchange="document.location = \'admin_lang.php?activ=1&amp;id=' . $lang['id'] . '\'" /> ' . $LANG['yes'] . '
						<input type="radio" name="' . $lang['id'] . 'activ" value="0" ' . (($lang['activ'] == 0) ? 'checked="checked"' : '') . ' onchange="document.location = \'admin_lang.php?activ=0&amp;id=' . $lang['id'] . '\'" /> ' . $LANG['no'] . '
					</td>
					<td class="row2" style="text-align:center;">	
						<select name="' . $lang['id'] . 'secure" onchange="document.location = \'admin_lang.php?secure=\' + this.options[this.selectedIndex].value + \'&amp;id=' . $lang['id'] . '\'">'; 
								
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
					$selected = ($i == $lang['secure']) ? 'selected="selected"' : '';
					$value .= '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>';
				}	
				$value .= '								
					</select>
				</td>
				<td class="row2" style="text-align:center;">
					<input type="submit" name="' . $lang['id'] . '" value="' . $LANG['uninstall'] . '" class="submit" />
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
		$template->assign_block_vars('main.lang', array(		
		));
	else
		$template->assign_block_vars('main.no_lang', array(		
		));
		
	$template->pparse('admin_lang_management'); 
}

include_once('../includes/admin_footer.php');

?>