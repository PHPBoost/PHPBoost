<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 0918
 * @since       PHPBoost 1.6 - 2007 08 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../pages/pages_begin.php');
include_once('pages_functions.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$request = AppContext::get_request();

$id_edit = (int)retrieve(GET, 'id', 0);
$id_edit_post = (int)retrieve(POST, 'id_edit', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_post;
$title = retrieve(POST, 'title', '');
$contents = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
$contents_preview = retrieve(POST, 'contents', '', TSTRING_PARSE);
$count_hits = (int)($request->has_postparameter('count_hits') && $request->get_value('count_hits') == 'on');
$enable_com = (int)($request->has_postparameter('comments_activated') && $request->get_value('comments_activated') == 'on');
$own_auth = retrieve(POST, 'own_auth', '');
$is_cat = (int)($request->has_postparameter('is_cat') && $request->get_value('is_cat') == 'on');
$id_cat = (int)retrieve(POST, 'id_cat', 0);
$display_print_link = (int)($request->has_postparameter('display_print_link') && $request->get_value('display_print_link') == 'on');
$preview = (bool)retrieve(POST, 'preview', false);
$del_article = (int)retrieve(GET, 'del', 0);

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

$categories = PagesCategoriesCache::load()->get_categories();

//Variable d'erreur
$error = '';
if ($id_edit > 0)
	define('TITLE', $LANG['pages_edition']);
else
	define('TITLE', $LANG['pages_creation']);

if ($id_edit > 0)
{
	try {
		$page_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', 'display_print_link'), 'WHERE id = :id', array('id' => $id_edit));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$Bread_crumb->add(TITLE, url('post.php?id=' . $id_edit));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$Bread_crumb->add(stripslashes($categories[$id]['title']), url('pages.php?title=' . Url::encode_rewrite(stripslashes($categories[$id]['title'])), Url::encode_rewrite(stripslashes($categories[$id]['title']))));
		$id = (int)$categories[$id]['id_parent'];
	}
	if (AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'));

$location_id = $id_edit ? 'pages-edit-'. $id_edit : '';

require_once('../kernel/header.php');

//On crée ou on édite une page
if (!empty($contents))
{
	if ($own_auth)
	{
		//Génération du tableau des droits.
		$array_auth_all = Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM);
		$page_auth = TextHelper::serialize($array_auth_all);
	}
	else
		$page_auth = '';

	//on ne prévisualise pas, donc on poste le message ou on l'édite
	if (!$preview)
	{
		//Edition d'une page
		if ($id_edit > 0)
		{
			try {
				$page_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'pages', array('id', 'title', 'contents', 'auth', 'encoded_title', 'is_cat', 'id_cat', 'display_print_link'), 'WHERE id = :id', array('id' => $id_edit));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			//Autorisation particulière ?
			$special_auth = !empty($page_infos['auth']);
			$array_auth = TextHelper::unserialize($page_infos['auth']);
			//Vérification de l'autorisation d'éditer la page
			if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
				AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));

			//on vérifie que la catégorie ne s'insère pas dans un de ses filles
			if ($page_infos['is_cat'] == 1)
			{
				$sub_cats = array();
				pages_find_subcats($sub_cats, $page_infos['id_cat']);
				$sub_cats[] = $page_infos['id_cat'];
				if (in_array($id_cat, $sub_cats)) //Si l'ancienne catégorie ne contient pas la nouvelle (sinon boucle infinie)
					$error = 'cat_contains_cat';
			}

			//Articles (on édite l'entrée de l'article pour la catégorie donc aucun problème)
			if ($page_infos['is_cat'] == 0)
			{
				//On met à jour la table
				PersistenceContext::get_querier()->update(PREFIX . 'pages', array('contents' => pages_parse($contents), 'count_hits' => $count_hits, 'activ_com' => $enable_com, 'auth' => $page_auth, 'id_cat' => $id_cat, 'display_print_link' => $display_print_link), 'WHERE id = :id', array('id' => $id_edit));
				//On redirige vers la page mise à jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
			//catégories : risque de boucle infinie
			elseif ($page_infos['is_cat'] == 1 && empty($error))
			{
				//Changement de catégorie mère ? => on met à jour la table catégories
				if ($id_cat != $page_infos['id_cat'])
				{
					PersistenceContext::get_querier()->update(PREFIX . 'pages_cats', array('id_parent' => $id_cat), 'WHERE id = :id', array('id' => $page_infos['id_cat']));
				}
				//On met à jour la table
				PersistenceContext::get_querier()->update(PREFIX . 'pages', array('contents' => pages_parse($contents), 'count_hits' => $count_hits, 'activ_com' => $enable_com, 'auth' => $page_auth, 'display_print_link' => $display_print_link), 'WHERE id = :id', array('id' => $id_edit));
				//Régénération du cache
				PagesCategoriesCache::invalidate();
				//On redirige vers la page mise à jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title'], '&'));
			}
		}
		//Création d'une page
		elseif (!empty($title))
		{
			if (!AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
				AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));

			$encoded_title = Url::encode_rewrite($title);
			$is_already_page = PersistenceContext::get_querier()->count(PREFIX . "pages", 'WHERE encoded_title=:encoded_title', array('encoded_title' => $encoded_title));

			//Si l'article n'existe pas déjà, on enregistre
			if ($is_already_page == 0)
			{
				$result = PersistenceContext::get_querier()->insert(PREFIX . 'pages', array('title' => $title, 'encoded_title' => $encoded_title, 'contents' => pages_parse($contents), 'user_id' => AppContext::get_current_user()->get_id(), 'count_hits' => $count_hits, 'activ_com' => $enable_com, 'timestamp' => time(), 'auth' => $page_auth, 'is_cat' => $is_cat, 'id_cat' => $id_cat, 'display_print_link' => $display_print_link));
				//Si c'est une catégorie
				if ($is_cat > 0)
				{
					$last_id_page = $result->get_last_inserted_id();
					$result = PersistenceContext::get_querier()->insert(PREFIX . 'pages_cats', array('id_parent' => $id_cat, 'id_page' => $last_id_page));
					$last_id_pages_cat = $result->get_last_inserted_id();
					PersistenceContext::get_querier()->update(PREFIX . 'pages', array('id_cat' => $last_id_pages_cat), 'WHERE id = :id', array('id' => $last_id_page));
					//Régénération du cache
					PagesCategoriesCache::invalidate();
				}
				//On redirige vers la page mise à jour
				AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
			}
			//Sinon, message d'erreur
			else
			{
				$error = 'page_already_exists';
			}
		}
	}
	else
		$error = 'preview';
}
//Suppression d'une page
elseif ($del_article > 0)
{
    //Vérification de la validité du jeton
    AppContext::get_session()->csrf_get_protect();

	try {
		$page_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', 'display_print_link'), 'WHERE id = :id', array('id' => $del_article));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = TextHelper::unserialize($page_infos['auth']);
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));

	//la page existe bien, on supprime
	if (!empty($page_infos['title']))
	{
		PersistenceContext::get_querier()->delete(PREFIX . 'pages', 'WHERE id=:id', array('id' => $del_article));
		PersistenceContext::get_querier()->delete(PREFIX . 'pages', 'WHERE redirect=:redirect', array('redirect' => $del_article));
		CommentsService::delete_comments_topic_module('pages', $del_article);
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=delete_success', '', '&'));
	}
	else
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=delete_failure', '', '&'));
}

$tpl = new FileTemplate('pages/post.tpl');

if ($id_edit > 0)
{
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = TextHelper::unserialize($page_infos['auth']);
	//Vérification de l'autorisation d'éditer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth', '', '&'));

	//Erreur d'enregistrement ?
	if ($error == 'cat_contains_cat')
		$tpl->put('message_helper', MessageHelper::display($LANG['pages_cat_contains_cat'], MessageHelper::WARNING));
	elseif ($error == 'preview')
	{
		$preview_contents = preg_replace('`action="(.*)"`suU', '', pages_parse($contents)); // suppression des actions des formulaires HTML pour eviter les problemes de parsing
		$tpl->put('message_helper', MessageHelper::display($LANG['pages_notice_previewing'], MessageHelper::NOTICE));
		$tpl->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse($preview_contents),
			'TITLE' => stripslashes($title)
		));
	}

	//Génération de l'arborescence des catégories
	$cats = array();
	//numéro de la catégorie de la page ou de la catégorie
	$id_cat_display = $page_infos['is_cat'] == 1 ? $categories[$page_infos['id_cat']]['id_parent'] : $page_infos['id_cat'];
	$cat_list = display_pages_cat_explorer($id_cat_display, $cats, 1);

	$tpl->put_all(array(
		'CONTENTS' => !empty($error) ? pages_unparse(stripslashes($contents_preview)) : pages_unparse($page_infos['contents']),
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($page_infos['count_hits'] == 1 ? 'checked="checked"' : ''),
		'COMMENTS_ACTIVATED_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') : ($page_infos['activ_com'] == 1 ? 'checked="checked"' : ''),
		'DISPLAY_PRINT_LINK_CHECKED' => !empty($error) ? ($display_print_link == 1 ? 'checked="checked"' : '') : ($page_infos['display_print_link'] == 1 ? 'checked="checked"' : ''),
		'OWN_AUTH_CHECKED' => !empty($page_infos['auth']) ? 'checked="checked"' : '',
		'CAT_0' => $id_cat_display == 0 ? 'selected' : '',
		'ID_CAT' => $id_cat_display,
		'SELECTED_CAT' => $id_cat_display,
		'CHECK_IS_CAT' => 'disabled="disabled"' . ($page_infos['is_cat'] == 1 ? ' checked="checked"' : '')
	));
}
else
{
	//Autorisations
	if (!AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');

	//La page existe déjà !
	if ($error == 'page_already_exists')
		$tpl->put('message_helper', MessageHelper::display($LANG['pages_already_exists'], MessageHelper::WARNING));
	elseif ($error == 'preview')
	{
		$preview_contents = preg_replace('`action="(.*)"`suU', '', pages_parse($contents)); // suppression des actions des formulaires HTML pour eviter les problemes de parsing
		$tpl->put('message_helper', MessageHelper::display($LANG['pages_notice_previewing'], MessageHelper::NOTICE));
		$tpl->assign_block_vars('previewing', array(
			'PREVIEWING' => pages_second_parse(stripslashes($preview_contents)),
			'TITLE' => stripslashes($title)
		));
	}
	if (!empty($error))
		$tpl->put_all(array(
			'CONTENTS' => pages_unparse(stripslashes($contents_preview)),
			'PAGE_TITLE' => stripslashes($title)
		));

	$tpl->assign_block_vars('create', array());

	//Génération de l'arborescence des catégories
	$cats = array();
	$cat_list = display_pages_cat_explorer(0, $cats, 1);
	$current_cat = $LANG['pages_root'];

	$tpl->put_all(array(
		'COUNT_HITS_CHECKED' => !empty($error) ? ($count_hits == 1 ? 'checked="checked"' : '') : ($pages_config->get_count_hits_activated() == true ? 'checked="checked"' : ''),
		'COMMENTS_ACTIVATED_CHECKED' => !empty($error) ? ($enable_com == 1 ? 'checked="checked"' : '') :($pages_config->get_comments_activated() == true ? 'checked="checked"' : ''),
		'DISPLAY_PRINT_LINK_CHECKED' => !empty($error) ? ($display_print_link == 1 ? 'checked="checked"' : '') : 'checked="checked"',
		'OWN_AUTH_CHECKED' => '',
		'CAT_0' => 'selected',
		'ID_CAT' => '0',
		'SELECTED_CAT' => '0',
		'CHECK_IS_CAT' => ($is_cat == 1 ? 'checked="checked"' : '')
	));
}

if (!empty($page_infos['auth']))
	$array_auth = TextHelper::unserialize($page_infos['auth']);
else
	$array_auth = $config_authorizations;

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

$tpl->put_all(array(
	'ID_EDIT' => $id_edit,
	'SELECT_READ_PAGE' => Authorizations::generate_select(READ_PAGE, $array_auth),
	'SELECT_EDIT_PAGE' => Authorizations::generate_select(EDIT_PAGE, $array_auth),
	'SELECT_READ_COM' => Authorizations::generate_select(READ_COM, $array_auth),
	'OWN_AUTH_DISABLED' => !empty($page_infos['auth']) ? 'false' : 'true',
	'DISPLAY' => empty($page_infos['auth']) ? 'display:none;' : '',
	'CAT_LIST' => $cat_list,
	'KERNEL_EDITOR' => $editor->display(),
	'L_AUTH' => $LANG['pages_auth'],
	'L_COMMENTS_ACTIVATED' => $LANG['pages_comments_activated'],
	'L_DISPLAY_PRINT_LINK' => $LANG['pages_display_print_link'],
	'L_COUNT_HITS' => $LANG['pages_count_hits_activated'],
	'L_ALERT_CONTENTS' => $LANG['page_alert_contents'],
	'L_ALERT_TITLE' => $LANG['page_alert_title'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_OWN_AUTH' => $LANG['pages_own_auth'],
	'L_IS_CAT' => $LANG['pages_is_cat'],
	'L_CAT' => $LANG['pages_parent_cat'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_PATH' => $LANG['pages_page_path'],
	'L_PROPERTIES' => $LANG['pages_properties'],
	'L_TITLE_POST' => $id_edit > 0 ? sprintf($LANG['pages_edit_page'], stripslashes($page_infos['title'])) : $LANG['pages_creation'],
	'L_TITLE_FIELD' => $LANG['page_title'],
	'L_CONTENTS' => $LANG['page_contents'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_SUMBIT' => $LANG['submit'],
	'L_ROOT' => $LANG['pages_root'],
	'L_PREVIEWING' => $LANG['pages_previewing'],
	'L_CONTENTS_PART' => $LANG['pages_contents_part'],
	'L_SUBMIT' => $id_edit > 0 ? $LANG['update'] : $LANG['submit']
));

$tpl->display();

require_once('../kernel/footer.php');

?>
