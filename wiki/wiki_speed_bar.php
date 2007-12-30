<?php
/*##################################################
 *                              wiki_speed_bar.php
 *                            -------------------
 *   begin                : May 5, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if( defined('PHP_BOOST') !== true)	exit;

if( !$groups->check_auth($SECURE_MODULE['wiki'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

require_once('../wiki/wiki_auth.php');

switch($speed_bar_key)
{
	case 'wiki':			
		$SPEED_BAR = array();
		if( !empty($id_contents) )
			$SPEED_BAR[] = array($LANG['wiki_history'], '');
		if( !empty($article_infos['title']) )
		{
			$SPEED_BAR[] = array($article_infos['title'], transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
			$id_cat = (int)$article_infos['id_cat'];
		}
		if( !empty($id_cat)  && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$SPEED_BAR[] = array($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$SPEED_BAR[] = array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$SPEED_BAR = array_reverse($SPEED_BAR);
		break;
	case 'wiki_history':
		$SPEED_BAR = array(
			array((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']),transid('wiki.php')),
			array($LANG['wiki_history'], transid('history.php'))
			);
			if( !empty($id_article) )
			$SPEED_BAR[] = array($article_infos['title'], transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
		break;
	case 'wiki_history_article':
		$cache->load_file('wiki');
		$SPEED_BAR = array(
			array($LANG['wiki_history'], transid('history.php?id=' . $id_article)),
			array($article_infos['title'], transid('wiki.php?title=' . url_encode_rewrite($article_infos['title'])), url_encode_rewrite($article_infos['title']))
		);
		$id_cat = (int)$article_infos['id_cat'];
		if( !empty($id_cat)  && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$SPEED_BAR[] = array($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$SPEED_BAR[] = array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$SPEED_BAR = array_reverse($SPEED_BAR);
		break;
	case 'wiki_post':
		$SPEED_BAR = array(
			array((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php')),
			array($LANG['wiki_contribuate'], '')
		);
		break;
	case 'wiki_property':
		$cache->load_file('wiki');
		$SPEED_BAR = array();
		if( $id_auth > 0 )
		{
			$SPEED_BAR[] = array($LANG['wiki_auth_management'], transid('property.php?auth=' . $article_infos['id']));
		}
		elseif( $wiki_status > 0 )
		{
			$SPEED_BAR[] = array($LANG['wiki_status_management'], transid('property.php?status=' . $article_infos['id']));
		}
		elseif( $move > 0 )
			$SPEED_BAR[] =  array($LANG['wiki_moving_article'], transid('property.php?move=' . $move));
		elseif( $rename > 0 )
			$SPEED_BAR[] = array($LANG['wiki_renaming_article'], transid('property.php?rename=' . $rename));
		elseif( $redirect > 0 )
			$SPEED_BAR[] = array($LANG['wiki_redirections'], transid('property.php?redirect=' . $redirect));
		elseif( $create_redirection > 0 )
			$SPEED_BAR[] = array($LANG['wiki_create_redirection'], transid('property.php?create_redirection=' . $create_redirection));
		elseif( isset($_GET['i']) && $idcom > 0 )
			$SPEED_BAR[] = array($LANG['wiki_article_com'], transid('property.php?com=' . $idcom . '&amp;i=0'));
		elseif( $del > 0 )
			$SPEED_BAR[] = array($LANG['wiki_remove_cat'], transid('property.php?del=' . $del));
			
		$SPEED_BAR[] = array($article_infos['title'], transid('wiki.php?title=' . url_encode_rewrite($article_infos['title']), url_encode_rewrite($article_infos['title'])));
		$id_cat = !empty($article_infos['id_cat']) ? (int)$article_infos['id_cat'] : 0;
		if( !empty($id_cat)  && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat;
			do
			{
				$SPEED_BAR[] = array($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$SPEED_BAR[] = array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$SPEED_BAR = array_reverse($SPEED_BAR);
		break;
	case 'wiki_favorites':
		$SPEED_BAR = array(
			array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php')),
			array($LANG['wiki_favorites'], transid('favorites.php'))
		);
		break;
	case 'wiki_explorer':
		$SPEED_BAR = array(
			array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php')),
			array($LANG['wiki_explorer'], transid('explorer.php'))
		);
		break;
	case 'wiki_search':
		$SPEED_BAR = array(
			array((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php')),
		array($LANG['wiki_search'], transid('search.php'))
		);
		break;
	default:
		$SPEED_BAR = array(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		break;
}
	
?>