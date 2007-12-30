<?php
/*##################################################
 *                              articles_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Autorisation sur le module.
if( !$groups->check_auth($SECURE_MODULE['articles'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

define('READ_CAT_ARTICLES', 0x01);
define('WRITE_CAT_ARTICLES', 0x02);
define('EDIT_CAT_ARTICLES', 0x04);

$cache->load_file('articles');
load_module_lang('articles', $CONFIG['lang']); //Chargement de la langue du module.


$get_note =  !empty($_GET['note']) ? numeric($_GET['note']) : 0;
$idartcat = isset($_GET['cat']) ? numeric($_GET['cat']) : 0;
$idart = !empty($_GET['id']) ? numeric($_GET['id']) : 0;

if( empty($idartcat) )//Racine.
{
	$CAT_ARTICLES[0]['auth'] = $CONFIG_ARTICLES['auth_root'];
	$CAT_ARTICLES[0]['aprob'] = 1;
	$CAT_ARTICLES[0]['name'] = $LANG['root'];
	$CAT_ARTICLES[0]['level'] = -1;
	$CAT_ARTICLES[0]['id_left'] = 0;
	$CAT_ARTICLES[0]['id_right'] = 0;
}
	
if( isset($_GET['cat']) )
{ 
	//Création de l'arborescence des catégories.
	speed_bar_generate($SPEED_BAR, $LANG['title_articles'], transid('articles.php'));
	foreach($CAT_ARTICLES as $id => $array_info_cat)
	{
		if( $CAT_ARTICLES[$idartcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_ARTICLES[$idartcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_ARTICLES[$idartcat]['level'] )
			speed_bar_generate($SPEED_BAR, $array_info_cat['name'], 'articles' . transid('.php?cat=' . $id, '-' . $id . '.php'));
	}
	if( !empty($idart) )
	{
		$articles = $sql->query_array('articles', '*', "WHERE visible = 1 AND id = '" . $idart . "' AND idcat = " . $idartcat, __LINE__, __FILE__);
		
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($articles['title']));
		speed_bar_generate($SPEED_BAR, $articles['title'], 'articles' . transid('.php?cat=' . $idartcat . '&amp;id=' . $idart, '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php'));
		
		if( !empty($get_note) )
			speed_bar_generate($SPEED_BAR, $LANG['note'], '');
		elseif( !empty($_GET['i']) )
			speed_bar_generate($SPEED_BAR, $LANG['com'], '');
	}
	else
		define('TITLE', $LANG['title_articles'] . ' - ' . addslashes($CAT_ARTICLES[$idartcat]['name']));
}
else
{
	speed_bar_generate($SPEED_BAR, $LANG['title_articles'], '');
	define('TITLE', $LANG['title_articles']);
}

?>