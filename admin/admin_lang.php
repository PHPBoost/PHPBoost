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

if (isset($_GET['activ']) && !empty($id)) //Activation
{
	$Sql->query_inject("UPDATE " . DB_TABLE_LANG . " SET activ = '" . numeric($_GET['activ']) . "' WHERE id = '" . $id . "' AND lang <> '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	
	//Régénération du cache.
	$Cache->Generate_file('langs');
		
	redirect(HOST . SCRIPT . '#t' . $id);	
}
if (isset($_GET['secure']) && !empty($id)) //Changement de niveau d'autorisation.
{
	$Sql->query_inject("UPDATE " . DB_TABLE_LANG . " SET secure = '" . numeric($_GET['secure']) . "' WHERE id = '" . $id . "' AND lang <> '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	
	//Régénération du cache.
	$Cache->Generate_file('langs');
		
	redirect(HOST . SCRIPT . '#t' . $id);	
}
elseif (isset($_POST['valid'])) //Mise à jour
{
	$result = $Sql->query_while("SELECT id, name, activ, secure
	FROM " . PREFIX . "lang
	WHERE activ = 1 AND lang != '" . $CONFIG['lang'] . "'", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$activ = retrieve(POST, $row['id'] . 'activ', 0);
		$secure = retrieve(POST, $row['id'] . 'secure', 0);
		if ($row['activ'] != $activ || $row['secure'] != $secure)
			$Sql->query_inject("UPDATE " . DB_TABLE_LANG . " SET activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	
	//Régénération du cache.
	$Cache->Generate_file('langs');
		
	redirect(HOST . SCRIPT);	
}
elseif ($uninstall) //Désinstallation.
{
	if (!empty($_POST['valid_del']))
	{		
		$idlang = retrieve(POST, 'idlang', 0); 
		$drop_files = !empty($_POST['drop_files']) ? true : false;
		
		$previous_lang = $Sql->query("SELECT lang FROM " . DB_TABLE_LANG . " WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		if ($previous_lang != $CONFIG['lang'] && !empty($idlang) && !empty($previous_lang))
		{
			//On met le thème par défaut du site aux membres ayant choisi le thème qui vient d'être supprimé!		
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_lang = '" . $CONFIG['lang'] . "' WHERE user_lang = '" . $previous_lang . "'", __LINE__, __FILE__);
				
			//On supprime le lang de la bdd.
			$Sql->query_inject("DELETE FROM " . DB_TABLE_LANG . " WHERE id = '" . $idlang . "'", __LINE__, __FILE__);
		}
		else
			redirect('/admin/admin_lang.php?error=incomplete#errorh');
		
		//Suppression des fichiers du module
		if ($drop_files && !empty($previous_lang))
		{
			
			$folder = new Folder('../lang/' . $previous_lang);
			if (!$folder->delete())
				$error = 'files_del_failed';
		}
	
		//Régénération du cache.
		$Cache->Generate_file('langs');
		
		$error = !empty($error) ? '?error=' . $error : '';
		redirect(HOST . SCRIPT . $error);
	}
	else
	{
		//Récupération de l'identifiant du thème.
		$idlang = '';
		foreach ($_POST as $key => $value)
			if ($value == $LANG['uninstall'])
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
		'THEME' => get_utheme(),		
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
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif (!empty($get_error) && isset($LANG[$get_error]))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	 
	
	//On liste les langues.
	$z = 0;
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $Sql->query_while("SELECT id, lang, activ, secure 
	FROM " . PREFIX . "lang", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//On selectionne le lang suivant les valeurs du tableau. 
		$info_lang = load_ini_file('../lang/', $row['lang']);
		
		$options = '';
		for ($i = -1 ; $i <= 2 ; $i++) //Rang d'autorisation.
		{
			$selected = ($i == $row['secure']) ? 'selected="selected"' : '';
			$options .= '<option value="' . $i . '" ' . $selected . '>' . $array_ranks[$i] . '</option>';
		}
		
		$default_lang = ($row['lang'] == $CONFIG['lang']);
		$Template->assign_block_vars('list', array(
			'C_LANG_DEFAULT' => $default_lang ? true : false,
			'C_LANG_NOT_DEFAULT' => !$default_lang ? true : false,
			'IDLANG' =>  $row['id'],		
			'LANG' =>  $info_lang['name'],
			'IDENTIFIER' =>  $info_lang['identifier'],
			'AUTHOR' => (!empty($info_lang['author_mail']) ? '<a href="mailto:' . $info_lang['author_mail'] . '">' . $info_lang['author'] . '</a>' : $info_lang['author']),
			'AUTHOR_WEBSITE' => (!empty($info_lang['author_link']) ? '<a href="' . $info_lang['author_link'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="" /></a>' : ''),
			'COMPAT' => $info_lang['compatibility'],
			'OPTIONS' => $options,
			'LANG_ACTIV' => ($row['activ'] == 1) ? 'checked="checked"' : '',
			'LANG_UNACTIV' => ($row['activ'] == 0) ? 'checked="checked"' : ''
		));
		$z++;
	}
	$Sql->query_close($result);
	
	if ($z != 0)
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