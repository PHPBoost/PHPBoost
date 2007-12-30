<?php
/*##################################################
 *                               admin_links.php
 *                            -------------------
 *   begin                : June 20, 2005
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
load_module_lang('links', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$id = !empty($_GET['id']) ? numeric($_GET['id']) : '' ;
$add = !empty($_GET['add']) ? true : false;
$top = !empty($_GET['top']) ? securit($_GET['top']) : '' ;
$bottom = !empty($_GET['bot']) ? securit($_GET['bot']) : '' ;

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	$result = $sql->query_while("SELECT id, name, url, sep
	FROM ".PREFIX."links
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$name = !empty($_POST[$row['id'] . 'name']) ? securit($_POST[$row['id'] . 'name']) : '';  
		$activ = isset($_POST[$row['id'] . 'activ']) ? numeric($_POST[$row['id'] . 'activ']) : '0'; //Désactivé par défaut.  
		$secure = isset($_POST[$row['id'] . 'secure']) ? numeric($_POST[$row['id'] . 'secure']) : '-1'; //Visiteurs par défaut.
		$url = !empty($_POST[$row['id'] . 'url']) ? securit($_POST[$row['id'] . 'url']) : ''; 
		
		if( ($row['sep'] == 1 || !empty($url)) && !empty($name) )
			$sql->query_inject("UPDATE ".PREFIX."links SET name = '" . $name . "', url = '" . $url . "', activ = '" . $activ . "', secure = '" . $secure . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	
	###### Régénération du cache des liens #######
	$cache->generate_module_file('links');
	
	header('location:' . HOST . SCRIPT);
	exit;	
}
elseif( !empty($_GET['del']) && !empty($id) ) //Suppresion du lien.
{
	//On supprime dans la bdd.
	$sql->query_inject("DELETE FROM ".PREFIX."links WHERE id = '" . $id . "'", __LINE__, __FILE__);	

	###### Régénération du cache des liens #######
	$cache->generate_module_file('links');
	
	header('location:' . HOST . SCRIPT);	
	exit;	
}
elseif( !empty($_POST['add']) ) //Ajout du lien.
{
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$url = !empty($_POST['url']) ? securit($_POST['url']) : '';    
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '0';   
	$secure = isset($_POST['secure']) ? numeric($_POST['secure']) : '-1';	
	
	if( !empty($name) && !empty($url) )
	{	
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$idnext = $sql->query("SELECT MAX(class) FROM ".PREFIX."links", __LINE__, __FILE__);
		$idnext++;
		$sql->query_inject("INSERT INTO ".PREFIX."links (class,name,url,activ,secure,sep) VALUES('" . $idnext . "', '" . $name . "', '" . $url . "', '" . $activ . "', '" . $secure . "', '0')", __LINE__, __FILE__);	
		
		###### Régénération du cache des liens #######
		$cache->generate_module_file('links');
		header('location:' . HOST . SCRIPT);
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/links/admin_links.php?add=1&error=incomplete#errorh');
		exit;
	}

}
elseif( !empty($_POST['sepa']) ) //Insertion d'un séparateur.
{
	$name = !empty($_POST['name2']) ? securit($_POST['name2']) : '';
	$activ = isset($_POST['activ']) ? numeric($_POST['activ']) : '0';   
	$secure = isset($_POST['secure']) ? numeric($_POST['secure']) : '-1';	
	
	if( !empty($name) )
	{	
		//On insere le nouveau lien, tout en précisant qu'il s'agit d'un lien ajouté et donc supprimable
		$idnext = $sql->query("SELECT MAX(class) FROM ".PREFIX."links", __LINE__, __FILE__);
		$idnext++;
		$sql->query_inject("INSERT INTO ".PREFIX."links (class,name,url,activ,secure,sep) VALUES('" . $idnext . "', '" . $name . "', '', '" . $activ . "', '" . $secure . "', '1')", __LINE__, __FILE__);	
	
		###### Régénération du cache des liens #######
		$cache->generate_module_file('links');	
		header('location:' . HOST . SCRIPT);
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/links/admin_links.php?add=1&error=incomplete#errorh');
		exit;
	}
}
elseif( (!empty($top) || !empty($bottom)) && !empty($id) ) //Monter/descendre.
{
	if( !empty($top) )
	{	
		$topmoins = ($top - 1);
		
		$sql->query_inject("UPDATE ".PREFIX."links SET class = 0 WHERE class = '" . $top . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."links SET class = '" . $top . "' WHERE class = '" . $topmoins . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."links SET class = '" . $topmoins . "' WHERE class = 0", __LINE__, __FILE__);
		
		###### Régénération du cache des liens #######
		$cache->generate_module_file('links');
		
		header('location:' . HOST . SCRIPT . '#l' . $id);
		exit;
	}
	elseif( !empty($bottom) )
	{
		$bottomplus = ($bottom + 1);
		
		$sql->query_inject("UPDATE ".PREFIX."links SET class = 0 WHERE class = '" . $bottom . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."links SET class = '" . $bottom . "' WHERE class = '" . $bottomplus . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."links SET class = '" . $bottomplus . "' WHERE class = 0", __LINE__, __FILE__);
		
		###### Régénération du cache des liens #######
		$cache->generate_module_file('links');		
		header('location:' . HOST . SCRIPT . '#l' . $id);
		exit;
	}
}
elseif( $add )
{
	$template->set_filenames(array(
	'admin_links_management' => '../templates/' . $CONFIG['theme'] . '/links/admin_links_management.tpl'
	));
	
	$template->assign_block_vars('add', array(
	));
		
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE, NO_LINE_ERROR, NO_FILE_ERROR, 'add.');
		
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_LINK_CONFIGURATION' => $LANG['link_configuration'],
		'L_LINK_ADD' => $LANG['link_add'],
		'L_NAME' => $LANG['name'],
		'L_PATH' => $LANG['path'],
		'L_VISIBLE' => $LANG['visible'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_RANK' => $LANG['rank'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_ADD_SEP' => $LANG['add_sep'],
		'L_IMG' => $LANG['img'],
		'L_ADD' => $LANG['add'],
		'L_RESET' => $LANG['reset']
	));

	$template->pparse('admin_links_management'); // traitement du modele	
}
else	
{		
	$template->set_filenames(array(
		'admin_links_management' => '../templates/' . $CONFIG['theme'] . '/links/admin_links_management.tpl'
	));
	
	$template->assign_block_vars('management', array(
	));
		
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'L_DEL_ENTRY' => $LANG['del_entry'],
		'L_LINK_CONFIGURATION' => $LANG['link_configuration'],
		'L_LINK_ADD' => $LANG['link_add'],
		'L_NAME' => $LANG['name'],
		'L_PATH' => $LANG['path'],
		'L_VISIBLE' => $LANG['visible'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_POSITION' => $LANG['position'],
		'L_DEL' => $LANG['delete'],
		'L_RANK' => $LANG['rank'],
		'L_GUEST' => $LANG['guest'],
		'L_MEMBER' => $LANG['member'],
		'L_MODO' => $LANG['modo'],
		'L_ADMIN' => $LANG['admin'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
		
	$result = $sql->query_while("SELECT l.id, l.class, l.url, l.name, l.activ, l.secure, l.sep, MAX(l1.class) as max_class, MIN(l1.class) as min_class
	FROM ".PREFIX."links l, 
	".PREFIX."links l1
	GROUP BY l.class
	ORDER BY l.class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( $row['sep'] == '1' )
			$url = '<img src="../templates/' . $CONFIG['theme'] . '/images/row1.png" alt="" />';
		else
			$url = '<input type="text" size="30" name="' . $row['id'] . 'url" value="' . $row['url'] . '" class="text" />';
			
		//Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
		$top_link = ($row['min_class'] != $row['class']) ? '<a href="admin_links.php?top=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . $CONFIG['theme'] . '/images/admin/up.png" alt="" title="" /></a>' : '';
		$bottom_link = ($row['max_class'] != $row['class']) ? '<a href="admin_links.php?bot=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . $CONFIG['theme'] . '/images/admin/down.png" alt="" title="" /></a>' : '';
		
		$template->assign_block_vars('management.links', array(
			'IDCAT' => $row['id'],
			'NAME' => $row['name'],
			'URL' => $url,
			'TOP' => $top_link,
			'BOTTOM' => $bottom_link,
			'DELETE' => '<a href="admin_links.php?delete=true&amp;id=' . $row['class'] . '" onClick="javascript:return Confirm();">' . $LANG['delete'] . '</a>',
			'HOST' => HOST
		));

		//Activation des liens.
		if( $row['activ'] == '1' ) //activé
		{
			$template->assign_block_vars('management.links.activ', array(
				'ACTIV_ENABLED' => 'checked="checked"'
			));
				
		}
		elseif( $row['activ'] == '0' )				
		{
			$template->assign_block_vars('management.links.activ', array(
				'ACTIV_DISABLED' => 'checked="checked"'
			));
		} 
		
		//Rang d'autorisation.
		for($i = -1; $i <= 2; $i++)
		{
			switch ($i) 
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
				
			$selected = ($row['secure'] == $i) ? 'selected="selected"' : '' ;	
			$template->assign_block_vars('management.links.select', array(
				'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
			));
		}		
	}
	$sql->close($result);

	$template->pparse('admin_links_management'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>