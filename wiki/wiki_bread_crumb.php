<?php
/*##################################################
 *                              wiki_bread_crumb.php
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

if( defined('PHPBOOST') !== true)	exit;

require_once('../wiki/wiki_auth.php');

switch($bread_crumb_key)
{
	case 'wiki':			
		if( !empty($id_contents) )
			$Bread_crumb->Add_link($LANG['wiki_history'], '');
		if( !empty($article_infos['title']) )
		{
			if( $article_infos['is_cat'] == 0 )
				$Bread_crumb->Add_link($article_infos['title'], transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
			$id_cat = (int)$article_infos['id_cat'];
		}
		if( !empty($id_cat)  && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$Bread_crumb->Add_link($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Reverse_links();
		break;
	case 'wiki_history':
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']),transid('wiki.php'));
		$Bread_crumb->Add_link($LANG['wiki_history'], transid('history.php'));
			if( !empty($id_article) )
				$Bread_crumb->Add_link($article_infos['title'], transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
		break;
	case 'wiki_history_article':
		$Cache->Load_file('wiki');
		$Bread_crumb->Add_link($LANG['wiki_history'], transid('history.php?id=' . $id_article));
		$Bread_crumb->Add_link($article_infos['title'], transid('wiki.php?title=' . url_encode_rewrite($article_infos['title'])), url_encode_rewrite($article_infos['title']));

		$id_cat = (int)$article_infos['id_cat'];
		if( !empty($id_cat)  && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$Bread_crumb->Add_link($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Reverse_links();
		break;
	case 'wiki_post':
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Add_link($LANG['wiki_contribuate'], '');
		break;
	case 'wiki_property':
		$Cache->Load_file('wiki');
		if( $id_auth > 0 )
			$Bread_crumb->Add_link($LANG['wiki_auth_management'], transid('property.php?auth=' . $article_infos['id']));
		elseif( $wiki_status > 0 )
			$Bread_crumb->Add_link($LANG['wiki_status_management'], transid('property.php?status=' . $article_infos['id']));
		elseif( $move > 0 )
			$Bread_crumb->Add_link($LANG['wiki_moving_article'], transid('property.php?move=' . $move));
		elseif( $rename > 0 )
			$Bread_crumb->Add_link($LANG['wiki_renaming_article'], transid('property.php?rename=' . $rename));
		elseif( $redirect > 0 )
			$Bread_crumb->Add_link($LANG['wiki_redirections'], transid('property.php?redirect=' . $redirect));
		elseif( $create_redirection > 0 )
			$Bread_crumb->Add_link($LANG['wiki_create_redirection'], transid('property.php?create_redirection=' . $create_redirection));
		elseif( isset($_GET['i']) && $idcom > 0 )
			$Bread_crumb->Add_link($LANG['wiki_article_com'], transid('property.php?com=' . $idcom . '&amp;i=0'));
		elseif( $del_article > 0 )
			$Bread_crumb->Add_link($LANG['wiki_remove_cat'], transid('property.php?del=' . $del_article));
			
		if( isset($article_infos) && $article_infos['is_cat'] == 0 )
			$Bread_crumb->Add_link($article_infos['title'], transid('wiki.php?title=' . url_encode_rewrite($article_infos['title']), url_encode_rewrite($article_infos['title'])));
			
		$id_cat = !empty($article_infos['id_cat']) ? (int)$article_infos['id_cat'] : 0;
		if( $id_cat > 0 && is_array($_WIKI_CATS) ) //Catégories infinies
		{
			$id = $id_cat;
			do
			{
				$Bread_crumb->Add_link($_WIKI_CATS[$id]['name'], transid('wiki.php?title=' . url_encode_rewrite($_WIKI_CATS[$id]['name']), url_encode_rewrite($_WIKI_CATS[$id]['name'])));
				$id = (int)$_WIKI_CATS[$id]['id_parent'];
			}	
			while( $id > 0 );
		}
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Reverse_links();
		break;
	case 'wiki_favorites':
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Add_link($LANG['wiki_favorites'], transid('favorites.php'));
		break;
	case 'wiki_explorer':
		$Bread_crumb->Add_link(( !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Add_link($LANG['wiki_explorer'], transid('explorer.php'));
		break;
	case 'wiki_search':
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		$Bread_crumb->Add_link($LANG['wiki_search'], transid('search.php'));
		break;
	default:
		$Bread_crumb->Add_link((!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']), transid('wiki.php'));
		break;
}
	
?>