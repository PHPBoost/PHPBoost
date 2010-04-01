<?php
/*##################################################
 *                              action.php
 *                            -------------------
 *   begin                : May 07, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

require_once('../kernel/begin.php'); 
include_once('../wiki/wiki_functions.php'); 

load_module_lang('wiki');

require('../wiki/wiki_auth.php');

$id_auth = retrieve(POST, 'id_auth', 0);
$id_status = retrieve(POST, 'id_status', 0);
$type_status = retrieve(POST, 'status', '');
$id_change_status = retrieve(POST, 'id_change_status', 0);
$contents = wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));
$move = retrieve(POST, 'id_to_move', 0);
$new_cat = retrieve(POST, 'new_cat', 0);
$id_to_rename = retrieve(POST, 'id_to_rename', 0);
$new_title = retrieve(POST, 'new_title', '');
$create_redirection_while_renaming = retrieve(POST, 'create_redirection_while_renaming', false);
$create_redirection = retrieve(POST, 'create_redirection', 0);
$redirection_title = retrieve(POST, 'redirection_title', '');
$del_redirection = retrieve(GET, 'del_redirection', 0);
$restore = retrieve(GET, 'restore', 0);
$del_archive = retrieve(GET, 'del_contents', 0);
$del_article = retrieve(GET, 'del_article', 0);
$del_to_remove = retrieve(POST, 'id_to_remove', 0);
$report_cat = retrieve(POST, 'report_cat', 0);
$remove_action = retrieve(POST, 'action', ''); //Action à faire lors de la suppression

if ($id_auth > 0)
{
	if (!$User->check_auth($_WIKI_CONFIG['auth'], WIKI_RESTRICTION))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	$encoded_title = $Sql->query("SELECT encoded_title FROM " . PREFIX . "wiki_articles WHERE id = '" . $id_auth . "'", __LINE__, __FILE__);
	if (empty($encoded_title))
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
		
	if (!empty($_POST['default'])) //Configuration par défaut
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET auth = '' WHERE id= '" . $id_auth . "'", __LINE__, __FILE__);
	else
	{
		//Génération du tableau des droits.
		$array_auth_all = Authorizations::build_auth_array_from_form(WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM);
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET auth = '" . addslashes(serialize($array_auth_all)) . "' WHERE id= '" . $id_auth . "'", __LINE__, __FILE__);
	}

	//Redirection vers l'article
	AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $encoded_title, $encoded_title, '&'));
}
if ($id_change_status > 0)
{
	$type_status = ($type_status == 'radio_undefined') ? 'radio_undefined' : 'radio_defined';
	
	//Si il s'agit d'un statut personnalisé
	if ($type_status == 'radio_undefined' && $contents != '')
	{
		$id_status = -1;
	}
	elseif ($type_status == 'radio_defined' && $id_status > 0 && is_array($LANG['wiki_status_list'][$id_status - 1]))
	{
		$contents = '';
	}
	else
		$id_status = 0;
		
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $id_change_status . "'", __LINE__, __FILE__);
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_STATUS)) && ($general_auth || $User->check_auth($article_auth , WIKI_STATUS))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 

	if (!empty($article_infos['encoded_title']))//Si l'article existe
	{
		//On met à jour dans la base de données
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET defined_status = '" . $id_status . "', undefined_status = '" . $contents . "' WHERE id = '" . $id_change_status . "'", __LINE__, __FILE__);
		//Redirection vers l'article
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($move > 0) //Déplacement d'un article
{
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "is_cat", "encoded_title", "id_cat", "auth", "WHERE id = '" . $move . "'", __LINE__, __FILE__);
	if ( empty($article_infos['encoded_title']))//Ce n'est pas un article ou une catégorie
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
		
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_MOVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_MOVE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	if ($article_infos['is_cat'] == 0)//Article: il ne peut pas y avoir de problème
	{
		if (array_key_exists($new_cat, $_WIKI_CATS) || $new_cat == 0)//Si la nouvelle catégorie existe
		{
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_cat = '" . $new_cat . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('wiki');
		}
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	//Catégorie: on vérifie qu'on ne la place pas dans elle-même ou dans une de ses catégories filles
	elseif ($article_infos['is_cat'] == 1)
	{
		//On fait un tableau contenant la liste des sous catégories de cette catégorie
		$sub_cats = array();
		wiki_find_subcats($sub_cats, $article_infos['id_cat']);
		$sub_cats[] = $article_infos['id_cat'];

		if (!in_array($new_cat, $sub_cats)) //Si l'ancienne catégorie ne contient pas la nouvelle (sinon boucle infinie)
		{
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET id_parent = '" . $new_cat . "' WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('wiki');
			//on redirige vers l'article
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
		}
		else //On redirige vers une page d'erreur
			AppContext::get_response()->redirect('/wiki/' .  url('property.php?move=' . $move  . '&error=e_cat_contains_cat', '', '&') . '#errorh');
	}
}
elseif ($id_to_rename > 0 && !empty($new_title)) //Renommer un article
{
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "*", "WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
		
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_RENAME)) && ($general_auth || $User->check_auth($article_auth , WIKI_RENAME))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$already_exists = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . Url::encode_rewrite($new_title) . "'", __LINE__, __FILE__);

	if (empty($article_infos['encoded_title']))//L'article n'existe pas
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	elseif (Url::encode_rewrite($new_title) == $article_infos['encoded_title'])//Si seul le titre change mais pas le titre encodé
	{
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET title = '" . $new_title . "' WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	elseif ($already_exists > 0) //Si le titre existe déjà erreur, on le signale
		AppContext::get_response()->redirect('/wiki/' . url('property.php?rename=' . $id_to_rename  . '&error=title_already_exists', '', '&') . '#errorh');
	elseif ($already_exists == 0)
	{
		if ($create_redirection_while_renaming) //On crée un nouvel article
		{
			//On ajoute un article
			$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_articles (id_contents, title, encoded_title, hits, id_cat, is_cat, defined_status, undefined_status, redirect, auth) VALUES ('" . $article_infos['id_contents'] . "', '" . $new_title . "', '" . Url::encode_rewrite($new_title) . "', '" . $article_infos['hits'] . "', '" . $article_infos['id_cat'] . "', '" . $article_infos['is_cat'] . "', '" . $article_infos['defined_status'] . "', '" . $article_infos['undefied_status'] . "', 0, '" . $article_infos['auth'] . "')", __LINE__, __FILE__);
			$new_id_article = $Sql->insert_id("SELECT MAX(id_contents) FROM " . PREFIX . "wiki_contents");
			
			//On met à jour la table contents
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET id_article = '" . $new_id_article . "' WHERE id_article = '" . $id_to_rename . "'", __LINE__, __FILE__);
			//On inscrit la redirection à l'ancien article
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET redirect = '" . $new_id_article . "', id_contents = 0 WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
			//On redirige les éventuelles redirections vers cet article sur son nouveau nom
			$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET redirect = '" . $new_id_article . "' WHERE redirect = '" . $id_to_rename . "'", __LINE__, __FILE__);
			//Si c'est une catégorie on change l'id d'article associé
			if ($article_infos['is_cat'] == 1)
			{
				$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET article_id = '" . $new_id_article . "' WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
				$Cache->Generate_module_file('wiki');
			}
    		 // Feeds Regeneration
             
             Feed::clear_cache('wiki');
		   AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($new_title), Url::encode_rewrite($new_title), '&'));
		}
		else //On met à jour l'article
		{
            $Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET title = '" . $new_title . "', encoded_title = '" . Url::encode_rewrite($new_title) . "' WHERE id = '" . $id_to_rename . "'", __LINE__, __FILE__);
			
            //Feeds Regeneration
            
            Feed::clear_cache('wiki');
            
            AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($new_title), Url::encode_rewrite($new_title), '&'));
		}
	}
}
elseif ($del_redirection > 0)//Supprimer une redirection
{
    //Vérification de la validité du jeton
    $Session->csrf_get_protect();
    
	$is_redirection = $Sql->query("SELECT redirect FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
	if ($is_redirection > 0)
	{
		$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $is_redirection . "'", __LINE__, __FILE__);
		
		$general_auth = empty($article_infos['auth']) ? true : false;
		$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
		if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $User->check_auth($article_auth , WIKI_REDIRECT))))
			$Errorh->handler('e_auth', E_USER_REDIRECT); 
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
}
elseif ($create_redirection > 0 && !empty($redirection_title))
{
	$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', '*', "WHERE id = '" . $create_redirection . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $User->check_auth($article_auth , WIKI_REDIRECT))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$num_title = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title =  '" . Url::encode_rewrite($redirection_title) . "'", __LINE__, __FILE__);

	if (!empty($article_infos['encoded_title']))
	{
		if ($num_title == 0) //Si aucun article existe
		{
			$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_articles (title, encoded_title, redirect, undefined_status, auth) VALUES ('" . $redirection_title . "', '" . Url::encode_rewrite($redirection_title) . "', '" . $create_redirection . "', '', '')", __LINE__, __FILE__);
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($redirection_title), Url::encode_rewrite($redirection_title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('property.php?create_redirection=' . $create_redirection  . '&error=title_already_exists', '', '&') . '#errorh');
	}
}
//Restauration d'une archive
elseif (!empty($restore)) //on restaure un ancien article
{
	//On cherche l'article correspondant
	$id_article = $Sql->query("SELECT id_article FROM " . PREFIX . "wiki_contents WHERE id_contents = " . $restore, __LINE__, __FILE__);
	if (!empty($id_article))
	{
		//On récupère l'ancien id du contenu
		$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', 'id_contents', 'encoded_title', 'auth', 'WHERE id = ' . $id_article, __LINE__, __FILE__);
		
		$general_auth = empty($article_infos['auth']) ? true : false;
		$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
		if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE_ARCHIVE))))
			$Errorh->handler('e_auth', E_USER_REDIRECT); 
		
		//On met à jour la table articles avec le nouvel id
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_contents = " . $restore . " WHERE id = " . $id_article, __LINE__, __FILE__);
		//On met le nouvel id comme actif
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET activ = 1 WHERE id_contents = " . $restore, __LINE__, __FILE__);
		//L'ancien id devient archive
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_contents SET activ = 0 WHERE id_contents = " . $article_infos['id_contents'], __LINE__, __FILE__);
	}
	
	AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'] , '&'));
}
//Suppression d'une archive
elseif ($del_archive > 0)
{
    //Vérification de la validité du jeton
    $Session->csrf_get_protect();
    
	$contents_infos = $Sql->query_array(PREFIX . "wiki_contents", "activ", "id_article", "WHERE id_contents = '" . $del_archive . "'", __LINE__, __FILE__);
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "auth", "WHERE id = '" . $contents_infos['id_article'] . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE_ARCHIVE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	if ($is_activ == 0) //C'est une archive -> on peut supprimer
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_contents = '" . $del_archive . "'", __LINE__, __FILE__);
	if (!empty($article_infos['encoded_title'])) //on redirige vees l'article
		AppContext::get_response()->redirect('/wiki/' . url('history.php?id=' . $contents_infos['id_article'], '', '&'));
}
elseif ($del_article > 0) //Suppression d'un article
{
    //Vérification de la validité du jeton
    $Session->csrf_get_protect();
    
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "auth", "encoded_title", "id_cat", "WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	//On rippe l'article
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_article . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $del_article . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $del_article . "'", __LINE__, __FILE__); 
	
	 // Feeds Regeneration
     
     Feed::clear_cache('wiki');
	
	if (array_key_exists($article_infos['id_cat'], $_WIKI_CATS))//Si elle  a une catégorie parente
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($_WIKI_CATS[$article_infos['id_cat']]['name']), Url::encode_rewrite($_WIKI_CATS[$article_infos['id_cat']]['name']), '&'));
	else
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
}
elseif ($del_to_remove > 0 && $report_cat >= 0) //Suppression d'une catégorie
{
	$remove_action = ($remove_action == 'move_all') ? 'move_all' : 'remove_all';
	
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "id_cat", "auth", "WHERE id = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();

	if (!((!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE))))
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$sub_cats = array();
	//On fait un tableau contenant la liste des sous catégories de cette catégorie
	wiki_find_subcats($sub_cats, $article_infos['id_cat']);
	$sub_cats[] = $article_infos['id_cat']; //On rajoute la catégorie que l'on supprime
	
	if (empty($article_infos['encoded_title'])) //si l'article n'existe pas on redirige vers l'index
		AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	
	if ($remove_action == 'move_all') //Vérifications préliminaires si on va tout supprimer
	{	
		//Si la nouvelle catégorie n'est pas une catégorie
		if (!array_key_exists($report_cat, $_WIKI_CATS) && $report_cat > 0)
			AppContext::get_response()->redirect('/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_not_a_cat#errorh', '', '&'));
			
		//Si on ne la déplace pas dans une de ses catégories filles
		if (($report_cat > 0 && in_array($report_cat, $sub_cats)) || $report_cat == $article_infos['id_cat'])//Si on veut reporter dans une catégorie parente
			AppContext::get_response()->redirect('/wiki/' . url('property.php?del=' . $del_to_remove . '&error=e_cat_contains_cat#errorh', '','&'));
	}

	//Quoi qu'il arrive on supprime l'article associé
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $del_to_remove . "'", __LINE__, __FILE__);	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_cats WHERE id = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $del_to_remove . "'", __LINE__, __FILE__);
	
	if ($remove_action == 'remove_all') //On supprime le contenu de la catégorie
	{
		foreach ($sub_cats as $id) //Chaque sous-catégorie
		{
			$result = $Sql->query_while ("SELECT id FROM " . PREFIX . "wiki_articles WHERE id_cat = '" . $id . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result)) //On supprime toutes les archives de chaque article avant de le supprimer lui-même
			{
				$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_contents WHERE id_article = '" . $row['id'] . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'wiki' AND idprov = '" . $row['id'] . "'", __LINE__);
			}
				
			$Sql->query_close($result);
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_articles WHERE id_cat = '" . $id . "'", __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_cats WHERE id = '" . $id . "'", __LINE__, __FILE__);
		}
		$Cache->Generate_module_file('wiki');

		// Feeds Regeneration
        
        Feed::clear_cache('wiki');
		
		//On redirige soit vers l'article parent soit vers la catégorie
		if (array_key_exists($article_infos['id_cat'], $_WIKI_CATS) && $_WIKI_CATS[$article_infos['id_cat']]['id_parent'] > 0)
		{
			$title = $_WIKI_CATS[$_WIKI_CATS[$article_infos['id_cat']]['id_parent']]['name'];
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	}
	elseif ($remove_action == 'move_all') //On déplace le contenu de la catégorie
	{
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_articles SET id_cat = '" . $report_cat . "' WHERE id_cat = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "wiki_cats SET id_parent = '" . $report_cat . "' WHERE id_parent = '" . $article_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Cache->Generate_module_file('wiki');
		
		if (array_key_exists($report_cat, $_WIKI_CATS))
		{
			$title = $_WIKI_CATS[$report_cat]['name'];
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));
	}
}

//On redirige vers l'index si on n'est rentré dans aucune des conditions ci-dessus
AppContext::get_response()->redirect('/wiki/' . url('wiki.php', '', '&'));

?>