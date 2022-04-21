<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
include_once('../wiki/wiki_functions.php');

$lang = LangLoader::get_all_langs('wiki');

$config = WikiConfig::load();

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$bread_crumb_key = 'wiki_post';
require_once('../wiki/wiki_bread_crumb.php');

$is_cat = (int)retrieve(POST, 'is_cat', false);
$is_cat_get = (int)(retrieve(GET, 'type', '') == 'cat');
$is_cat = $is_cat > 0 ? $is_cat : $is_cat_get;
$id_edit = (int)retrieve(POST, 'id_edit', 0);
$title = retrieve(POST, 'title', '');
$encoded_title = retrieve(GET, 'title', '');
$contents = wiki_parse(retrieve(POST, 'content', '', TSTRING_AS_RECEIVED));
$contents_preview = retrieve(POST, 'content', '', TSTRING_PARSE);
$change_reason = $id_edit > 0 ? wiki_parse(retrieve(POST, 'change_reason', '', TSTRING_AS_RECEIVED)) : $lang['wiki.item.init'];
$change_reason_preview = retrieve(POST, 'change_reason', '', TSTRING_PARSE);
$id_cat = (int)retrieve(GET, 'id_parent', 0);
$new_id_cat = (int)retrieve(POST, 'id_cat', 0);
$id_cat = $id_cat > 0 ? $id_cat : $new_id_cat;
$preview = (bool)retrieve(POST, 'preview', false);
$id_edit_get = (int)retrieve(GET, 'id', 0);
$id_edit = $id_edit > 0 ? $id_edit : $id_edit_get;

define('TITLE', $id_edit ? $lang['wiki.edit.item'] : $lang['wiki.create.item']);

$location_id = $id_edit ? 'wiki-edit-'. $id_edit : '';

require_once('../kernel/header.php');

$categories = WikiCategoriesCache::load()->get_categories();

//Variable d'erreur
$error = '';

$view = new FileTemplate('wiki/post.tpl');
$view->add_lang($lang);

$captcha = AppContext::get_captcha_service()->get_default_factory();
if (!empty($contents)) //On enregistre un article
{
	include_once('../wiki/wiki_functions.php');
	//On crée le menu des paragraphes et on enregistre le menu
	$menu = '';

	//Si on détecte la syntaxe des menus alors on lance les fonctions, sinon le menu sera vide et non affiché
	if (preg_match('`[\-]{2,6}`isuU', $contents))
	{
		$menu_list = wiki_explode_menu($contents); //On éclate le menu en tableaux
		$menu = wiki_display_menu($menu_list); //On affiche le menu
	}

	if ($preview)//Prévisualisation
	{
		$preview_contents = preg_replace('`action="(.*)"`suU', '', $contents); // suppression des actions des formulaires HTML pour eviter les problemes de parsing

		$view->put('C_PREVIEW', true);
		$view->assign_block_vars('preview', array(
			'CONTENT' => FormatingHelper::second_parse(wiki_no_rewrite(stripslashes($preview_contents))),
			'TITLE'   => stripslashes($title)
		));
		if (!empty($menu))
		{
			$view->assign_block_vars('preview.menu', array(
				'MENU' => $menu
			));
		}
	}
	else //Sinon on poste
	{
		if ($id_edit > 0)//On édite un article
		{
			try {
				$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('title', 'encoded_title', 'auth'), 'WHERE id = :id', array('id' => $id_edit));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			//Autorisations
			$general_auth = empty($article_infos['auth']);
			$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();
			if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT))))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}

			$previous_id_contents = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'id_contents', 'WHERE id = :id', array('id' => $id_edit));
			//On met à jour l'ancien contenu (comme archive)
			PersistenceContext::get_querier()->update(PREFIX . "wiki_contents", array('activ' => 0), 'WHERE id_contents = :id', array('id' => $previous_id_contents));
			//On insère le contenu
			$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_contents", array('id_article' => $id_edit, 'menu' => $menu, 'content' => $contents, 'activ' => 1, 'user_id' => AppContext::get_current_user()->get_id(), 'user_ip' => AppContext::get_request()->get_ip_address(), 'timestamp' => time(), 'change_reason' => $change_reason));
			//Dernier id enregistré
			$id_contents = $result->get_last_inserted_id();

	 		//On donne le nouveau id de contenu
			PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $id_contents), 'WHERE id = :id', array('id' => $id_edit));

            // Feeds Regeneration
            Feed::clear_cache('wiki');
			HooksService::execute_hook_action('edit', 'wiki', array_merge($article_infos, array('content' => $contents, 'url' => Url::to_rel('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'])))));

			//On redirige
			$redirect = $article_infos['encoded_title'];
			AppContext::get_response()->redirect(url('wiki.php?title=' . $redirect, $redirect, '', '&'));
		}
		elseif (!empty($title)) //On crée un article
		{
			//autorisations
			if ($is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_CAT))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
			elseif (!$is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
			elseif (!$captcha->is_valid() && !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
			{
				$error_controller = new UserErrorController(
					$lang['warning.error'],
					$lang['warning.captcha.validation.error'],
					UserErrorController::NOTICE
				);
				DispatchManager::redirect($error_controller);
			}

			//On vérifie que le titre n'existe pas
			$article_exists = PersistenceContext::get_querier()->count(PREFIX . "wiki_articles", 'WHERE encoded_title = :encoded_title', array('encoded_title' => Url::encode_rewrite($title)));
			//Si il existe: message d'erreur
			if ($article_exists > 0)
				$errstr = $lang['wiki.title.already.exists'];
			else //On enregistre
			{
				$properties = array(
					'title'            => $title,
					'encoded_title'    => Url::encode_rewrite($title),
					'id_cat'           => $new_id_cat,
					'is_cat'           => $is_cat,
					'undefined_status' => '',
					'auth'             => ''
				);

				$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_articles", $properties);
				//On récupère le numéro de l'article créé
				$id_article = $result->get_last_inserted_id();
				//On insère le contenu
				$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_contents", array('id_article' => $id_article, 'menu' => $menu, 'content' => $contents, 'activ' => 1, 'user_id' => AppContext::get_current_user()->get_id(), 'user_ip' => AppContext::get_request()->get_ip_address(), 'timestamp' => time(), 'change_reason' => $change_reason));
				//On met à jour le numéro du contenu dans la table articles
				$id_contents = $result->get_last_inserted_id();
				if ($is_cat == 1)//si c'est une catégorie, on la crée
				{
					$result = PersistenceContext::get_querier()->insert(PREFIX . "wiki_cats", array('id_parent' => $new_id_cat, 'article_id' => $id_article));
					//on récupère l'id de la dernière catégorie créée
					$id_created_cat = $result->get_last_inserted_id();
					PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $id_contents, 'id_cat' => $id_created_cat), 'WHERE id = :id', array('id' => $id_article));
					//On régénère le cache
					WikiCategoriesCache::invalidate();
				}
				else
					PersistenceContext::get_querier()->update(PREFIX . "wiki_articles", array('id_contents' => $id_contents), 'WHERE id = :id', array('id' => $id_article));

				// Feeds Regeneration
				Feed::clear_cache('wiki');
				HooksService::execute_hook_action('add', 'wiki', array_merge($properties, array('contents' => $contents, 'url' => Url::to_rel('/wiki/' . url('wiki.php?title=' . $properties['encoded_title'], $properties['encoded_title'])))));

				$redirect = PersistenceContext::get_querier()->get_column_value(PREFIX . "wiki_articles", 'encoded_title', 'WHERE id = :id', array('id' => $id_article));
				AppContext::get_response()->redirect(url('wiki.php?title=' . $redirect, $redirect, '' , '&'));
			}
		}
	}
}

if ($id_edit > 0)//On édite
{
	try {
		$article_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . "wiki_articles", array('*'), 'WHERE id = :id', array('id' => $id_edit));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Autorisations
	$general_auth = empty($article_infos['auth']);
	$article_auth = !empty($article_infos['auth']) ? TextHelper::unserialize($article_infos['auth']) : array();
	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_EDIT)) && ($general_auth || AppContext::get_current_user()->check_auth($article_auth , WIKI_EDIT))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	try {
		$article_contents = PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_contents', array('*'), 'WHERE id_contents = :id', array('id' => $article_infos['id_contents']));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$contents = $article_contents['content'];
	if (!empty($article_contents['menu'])) //On reforme les paragraphes
	{
		$string_regex = '-';
		for ($i = 2; $i <= 6; $i++)
		{
			$string_regex .= '-';
			$contents = preg_replace('`[\r\n]+<(?:div|h[2-6]) class="formatter-title wiki-paragraph-' .  $i . '" id=".+">(.+)</(?:div|h[2-6])><br />[\r\n]+`suU', (AppContext::get_current_user()->get_editor() == 'TinyMCE' ? '<br />' : "\n") . $string_regex . ' $1 '. $string_regex . (AppContext::get_current_user()->get_editor() == 'TinyMCE' ? '<br/>' : ''), "\n" . $contents . "\n");
		}
		$contents = trim($contents);
	}

	$view->put_all(array(
		'SELECTED_CATEGORY' => $id_edit,
	));
}
else
{
	//autorisations
	if ($is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_CAT))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
	elseif (!$is_cat && !AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_CREATE_ARTICLE))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($encoded_title))
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['wiki.item.unexists'], MessageHelper::WARNING));

	if ($id_cat > 0 && array_key_exists($id_cat, $categories)) //Catégorie préselectionnée
	{
		$view->assign_block_vars('create', array());
		$cats = array();
		$cat_list = display_wiki_cat_explorer($id_cat, $cats, 1);
		$cats = array_reverse($cats);
		if (array_key_exists(0, $cats))
			unset($cats[0]);
		$nbr_cats = count($cats);
		$current_cat = '';
		$i = 1;
		foreach ($cats as $key => $value)
		{
			$current_cat .= stripslashes($categories[$value]['title']) . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . stripslashes($categories[$id_cat]['title']);
		$view->put_all(array(
			'SELECTED_CATEGORY' => $id_cat,
			'CATEGORY_0'        => '',
			'CATEGORY_LIST'     => $cat_list,
			'CURRENT_CATEGORY'  => $current_cat
		));
	}
	else // Si il n'a pas de catégorie parente
	{
		$view->assign_block_vars('create', array());
		$contents = '';
		$result = PersistenceContext::get_querier()->select("SELECT c.id, a.title, a.encoded_title
		FROM " . PREFIX . "wiki_cats c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
		WHERE c.id_parent = 0
		ORDER BY title ASC");
		while ($row = $result->fetch())
		{
			$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "wiki_cats", 'WHERE id_parent = :id', array('id' => $row['id']));
			$view->assign_block_vars('create.list', array(
				'ID'        => $row['id'],
				'TITLE'     => stripslashes($row['title']),
				'C_SUB_CAT' => $sub_cats_number > 0
			));
		}
		$result->dispose();
		$view->put_all(array(
			'SELECTED_CATEGORY' => 0,
			'CATEGORY_0'        => 'selected',
			'CATEGORY_LIST'     => '',
			'CURRENT_CATEGORY'  => $lang['wiki.no.selected.category']
		));
	}
}

//On travaille uniquement en BBCode, on force le langage de l'éditeur
$content_editor = AppContext::get_content_formatting_service()->get_default_factory();
$editor = $content_editor->get_editor();
$editor->set_identifier('content');

$view->put_all(array(
	'C_CAPTCHA'       => !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'C_IS_CATEGORY'   => $is_cat == 1,
	'C_EDIT_CATEGORY' => $is_cat == 1 && $id_edit > 1,
	'C_EDIT_ITEM'     => $is_cat == 0 && $id_edit > 0,
	'C_EDIT'          => $id_edit > 0,

	'TITLE'         => $id_edit != 0 ? stripslashes($article_infos['title']) : '',
	'EDIT_TITLE'    => ($id_edit == 0 ? (!empty($encoded_title) ? $encoded_title : stripslashes($title)) : stripslashes($article_infos['title'])),
	'KERNEL_EDITOR' => $editor->display(),
	'ID_CATEGORY'   => $id_edit ? $article_infos['id_cat'] : '',
	'CONTENT'       => (($id_edit && $contents_preview) || !$id_edit) ? wiki_unparse(stripslashes($contents_preview)) : wiki_unparse($contents),
    'CHANGE_REASON' => $id_edit ? wiki_unparse(stripslashes($change_reason_preview)) : wiki_unparse($change_reason),
	'ID_EDIT'       => $id_edit,
	'IS_CATEGORY'   => $is_cat,
	'CAPTCHA'       => $captcha->display(),

	'U_TARGET' => url('post.php' . ($is_cat == 1 ? '?type=cat' : '')),
));

//outils BBcode en javascript
include_once('../wiki/post_js_tools.php');
$view->put('POST_JS_TOOLS', $jstools_tpl);

//Eventuelles erreurs
if (!empty($errstr))
	$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, MessageHelper::WARNING));

$view->display();


require_once('../kernel/footer.php');

?>
