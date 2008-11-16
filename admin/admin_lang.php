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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
	
$uninstall = retrieve(GET, 'uninstall', false);	
$id = retrieve(GET, 'id', 0);
$error = retrieve(GET, 'error', ''); 

if( isset($_GET['activ']) && !empty($id) ) //Activation
{
	$Sql->query_inject("UPDATE ".PREFIX."lang SET activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "' AND lang <> '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	
	redirect(HOST . SCRIPT . '#t' . $id);	
}
if( isset($_GET['secure']) && !empty($id) ) //Changement de niveau d'autorisation.
{
	$Sql->query_inject("UPDATE ".PREFIX."lang SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "' AND lang <> '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	
	redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif( isset($_POST['valid']) ) //Mise à jour
{
	$result = $Sql->query_while("SELECT id, name, activ, secure
	FROM ".PREFIX."lang
	WHERE activ = 1 AND lang != '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	while( $row = $Sql->fetch_assoc($result) )
	{
		$activ = retrieve(POST, $row['id'] . 'activ', 0);
		$secure = retrieve(POST, $row['id'] . 'secure', 0);
		if( $row['activ'] != $activ || $row['secure'] != $secure )
			$Sql->query_inject("UPDATE ".PREFIX."modules SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	redirect(HOST . SCRIPT);	
}
elseif( $uninstall ) //Désinstallation.
{
	if( !empty($_POST['valid_del']) )
	{		
		$idlang = retrieve(POST, 'idlang', 0); 
		$drop_files = !empty($_POST['drop_files']) ? true : false;
		
		$previous_lang = $Sql->query("SELECT lang FROM ".PREFIX."lang WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		if( $previous_lang != $CONFIG['lang'] && !empty($idlang) && !empty($previous_lang) )
		{
			//On met le thème par défaut du site aux membres ayant choisi le thème qui vient d'être supprimé!		
			$Sql->query_inject("UPDATE ".PREFIX."member SET user_lang = '" . $CONFIG['lang'] . "' WHERE user_lang = '" . $previous_lang . "'", __LINE__, __FILE__);
				
			//On supprime le lang de la bdd.
			$Sql->query_inject("DELETE FROM ".PREFIX."lang WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		}
		else
			redirect(HOST . DIR . '/admin/admin_lang.php?error=incomplete#errorh');
		
		//Suppression des fichiers du module
		if( $drop_files && !empty($previous_lang) )
		{
			if( !delete_directory('../lang/' . $previous_lang, '../lang/' . $previous_lang) )
				$error = 'files_del_failed';
		}
	
		$error = !empty($error) ? '?error=' . $error : '';
		redirect(HOST . SCRIPT . $error);
	}
	else
	{
		//Récupération de l'identifiant du thème.
		$idlang = '';
		foreach($_POST as $key => $value)
			if( $value == $LANG['uninstall'] )
				$idlang = $key;
				
		$Template->set_filenames(array(
			'admin_lang_management'=> 'admin/admin_lang_management.tpl'
		));
		
		$Template->assign_vars(array(
			'C_DEL_LANG' => true,
			'IDLANG' => $idlang,
			'L_LANG_ADD' => $LANG['lang_add'],	
			'L_LANG_MANAGEMENT' => $LANG['lang_management'],
			'L_DEL_LANG' => $LANG['del_lang'],
			'L_DEL_FILE' => $LANG['del_lang_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_DELETE' => $LANG['delete']
		));

		$Template->pparse('admin_lang_management'); 
	}
}		
else
{			
	$Template->set_filenames(array(
		'admin_lang_management'=> 'admin/admin_lang_management.tpl'
	));
	 
	$Template->assign_vars(array(
		'C_LANG_MAIN' => true,
		'THEME' => uget_theme(),		
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
		'L_NO' => $LANG['no'],
		'L_GUEST' => $LANG['guest'],
		'L_UNINSTALL' => $LANG['uninstall']		
	));
		
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if( $get_error == 'incomplete' )
		$Errorh->handler($LANG[$get_error], E_USER_NOTICE);
	elseif( !empty($get_error) && isset($LANG[$get_error]) )
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	 
	
	//On recupère les dossier des thèmes contenu dans le dossier templates	
	$z = 0;
	$rep = '../lang/';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$dir_array = array();
		$dh = @opendir($rep);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( strpos($dir, '.') === false )
				$dir_array[] = $dir; //On crée un array, avec les different dossiers.
		}	
		closedir($dh); //On ferme le dossier		

		$lang_bdd = array();
		$result = $Sql->query_while("SELECT id, lang, activ, secure 
		FROM ".PREFIX."lang", __LINE__, __FILE__);
		while( $row = $Sql->fetch_assoc($result) )
		{
			//On recherche les clées correspondante à celles trouvée dans la bdd.
			if( array_search($row['lang'], $dir_array) !== false)
				$lang_bdd[] = array('id' => $row['id'], 'name' => $row['lang'], 'activ' => $row['activ'], 'secure' => $row['secure']); //On supprime ces clées du tableau.
		}
		$Sql->query_close($result);
		
		$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		foreach($lang_bdd as $key => $lang) //On effectue la recherche dans le tableau.
		{
			//On selectionne le lang suivant les valeurs du tableau. 
			$info_lang = load_ini_file('../lang/', $lang['name']);
			
			$options = '';
			for($i = -1 ; $i <= 2 ; $i++) //Rang d'autorisation.
			{
				$selected = ($i == $lang['secure']) ? 'selected="selected"' : '';
				$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
			}
			
			$default_lang = ($lang['name'] == $CONFIG['lang']);
			$Template->assign_block_vars('list', array(
				'C_LANG_DEFAULT' => $default_lang ? true : false,
				'C_LANG_NOT_DEFAULT' => !$default_lang ? true : false,
				'IDLANG' =>  $lang['id'],		
				'LANG' =>  $info_lang['name'],
				'IDENTIFIER' =>  $info_lang['identifier'],
				'AUTHOR' => (!empty($info_lang['author_mail']) ? '<a href="mailto:' . $info_lang['author_mail'] . '">' . $info_lang['author'] . '</a>' : $info_lang['author']),
				'AUTHOR_WEBSITE' => (!empty($info_lang['author_link']) ? '<a href="' . $info_lang['author_link'] . '"><img src="../templates/' . uget_theme() . '/images/' . uget_lang() . '/user_web.png" alt="" /></a>' : ''),
				'COMPAT' => $info_lang['compatibility'],
				'OPTIONS' => $options,
				'LANG_ACTIV' => ($lang['activ'] == 1) ? 'checked="checked"' : '',
				'LANG_UNACTIV' => ($lang['activ'] == 0) ? 'checked="checked"' : ''
			));
			$z++;
		}
	}	
	
	if( $z != 0 )
		$Template->assign_vars(array(		
			'C_LANG_PRESENT' => true
		));
	else
		$Template->assign_vars(array(		
			'C_NO_LANG_PRESENT' => true
		));
		
	$Template->pparse('admin_lang_management'); 
}

require_once('../admin/admin_footer.php');

?>