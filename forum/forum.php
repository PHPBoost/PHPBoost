<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 1.2 - 2005 10 26
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');
$request = AppContext::get_request();

$lang = LangLoader::get_all_langs('forum');

$id_get = $request->get_getint('id', 0);
$categories_cache = CategoriesService::get_categories_manager('forum')->get_categories_cache();

//Vérification de l'existance de la catégorie.
if (empty($id_get) || ($id_get != Category::ROOT_CATEGORY && !$categories_cache->category_exists($id_get)))
{
	$controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($controller);
}

try {
	$category = $categories_cache->get_category($id_get);
} catch (CategoryNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

if ($category->get_type() != ForumCategory::TYPE_FORUM)
	AppContext::get_response()->redirect('/forum/' . url('index.php?id=' . $category->get_id(), 'cat-' . $category->get_id() . '-' . $category->get_rewrited_name() . '.php'));

//Vérification des autorisations d'accès.
if (!ForumAuthorizationsService::check_authorizations($category->get_id())->read())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

if ($category->get_url())
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//Récupération de la barre d'arborescence.
$Bread_crumb->add($config->get_forum_name(), 'index.php');
$categories = array_reverse(CategoriesService::get_categories_manager('forum')->get_parents($id_get, true));
foreach ($categories as $id => $cat)
{
	if ($cat->get_id() != Category::ROOT_CATEGORY)
	{
		if ($cat->get_type() == ForumCategory::TYPE_FORUM)
			$Bread_crumb->add($cat->get_name(), 'forum' . url('.php?id=' . $cat->get_id(), '-' . $cat->get_id() . '-' . $cat->get_rewrited_name() . '.php'));
		else
			$Bread_crumb->add($cat->get_name(), url('index.php?id=' . $cat->get_id(), 'cat-' . $cat->get_id() . '-' . $cat->get_rewrited_name() . '.php'));
	}
}

if (!empty($id_get))
	define('TITLE', $category->get_name());
else
	define('TITLE', $lang['forum.module.title']);

$description = $category->get_description();
if (empty($description))
	$description = StringVars::replace_vars($lang['forum.root.description.seo'], array('site' => GeneralConfig::load()->get_site_name())) . ($category->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $category->get_name() : '');
define('DESCRIPTION', $description);

require_once('../kernel/header.php');

//Redirection changement de catégorie.
$change_cat = $request->get_postint('change_cat', 0);
if (!empty($change_cat))
{
	$new_cat = '';
	try {
		$new_cat = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($change_cat);
	} catch (CategoryNotFoundException $e) { }
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . ($new_cat && ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '-' . $new_cat->get_rewrited_name() : '') . '.php', '&'));
}

if (!empty($id_get))
{
	$view = new FileTemplate('forum/forum_forum.tpl');
	$view->add_lang($lang);

	//Invité?
	$is_guest = AppContext::get_current_user()->get_id() == -1;

	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();

	//Affichage des sous forums s'il y en a.
	if (CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_children($id_get))
	{
		$view->put_all(array(
			'C_FORUM_SUB_CATS' => true
		));

		//Vérification des autorisations.
		$authorized_categories = CategoriesService::get_authorized_categories($id_get);

		//On liste les sous-catégories.
		$result = PersistenceContext::get_querier()->select('SELECT
			c.id AS cid, c.id_parent, c.name, c.rewrited_name, c.description as subname, c.url, c.thumbnail, c.icon, c.color, c.last_topic_id, c.status AS cat_status,
			t.id AS tid, t.id_category, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, t.status,
			m.user_id, m.display_name, m.level AS user_level, m.user_groups,
			v.last_view_id
		FROM ' . ForumSetup::$forum_cats_table . ' c
		LEFT JOIN ' . ForumSetup::$forum_topics_table . ' t ON t.id = c.last_topic_id
		LEFT JOIN ' . ForumSetup::$forum_view_table . ' v ON v.user_id = :user_id AND v.idtopic = t.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = t.last_user_id
		WHERE c.id_parent = :id_cat AND c.id IN :authorized_categories
		ORDER BY c.id_parent, c.c_order', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'id_cat' => $category->get_id(),
			'authorized_categories' => $authorized_categories
		));

		$categories = array();
		while ($row = $result->fetch())
		{
			$cat = $categories_cache->get_category($row['cid']);
			$elements_number = $cat->get_elements_number();

			$categories[$row['cid']] = $row;
			$categories[$row['cid']]['nbr_topic'] = $elements_number['topics_number'];
			$categories[$row['cid']]['nbr_msg'] = $elements_number['messages_number'];
		}
		$result->dispose();

		$display_sub_cats = false;
		$is_sub_forum = array();
		foreach ($categories as $row)
		{
			if (in_array($row['id_parent'], $is_sub_forum))
				$is_sub_forum[] = $row['cid'];

			if (!in_array($row['cid'], $is_sub_forum))
			{
				if ((int)$row['nbr_msg'] > 0)
				{
					//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
					if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
					{
						$last_msg_id = $row['last_view_id'];
						$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
						$last_page_rewrite = '-0-' . $row['last_view_id'];
					}
					else
					{
						$last_msg_id = $row['last_msg_id'];
						$last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
						$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
						$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
					}

					$last_topic_title = (($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '') . ' ' . $row['title'];

					$last_group_color = User::get_group_color($row['user_groups'], $row['user_level']);
				}
				else
				{
					$row['last_timestamp'] = $last_group_color = '';
				}

				//Vérirication de l'existance de sous forums.
				$subforums = '';
				$children = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_children($row['cid']);
				if ($children)
				{
					foreach ($children as $id => $child) //Listage des sous forums.
					{
						if ($child->get_id_parent() == $row['cid'] && ForumAuthorizationsService::check_authorizations($child->get_id())->read()) //Sous forum distant d'un niveau au plus.
						{
							$is_sub_forum[] = $child->get_id();
							$link = $child->get_url() ? '<a href="' . $child->get_url() . '" class="forum-subforum-element offload">' : '<a href="forum' . url('.php?id=' . $child->get_id(), '-' . $child->get_id() . '-' . $child->get_rewrited_name() . '.php') . '" class="forum-subforum-element">';
							$subforums .= !empty($subforums) ? ', ' . $link . $child->get_name() . '</a>' : $link . $child->get_name() . '</a>';
						}
					}
				}

				//Vérifications des topics Lu/non Lus.
				$topic_icon = 'fa-announce';
				$blink = false;
				if (!$is_guest)
				{
					if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
					{
						$topic_icon =  $topic_icon . '-new'; //Image affiché aux visiteurs.
						$blink = true;
					}
				}
				$topic_icon .= ($row['cat_status'] == ForumCategory::STATUS_LOCKED) ? '-lock' : '';

				$last_msg_date = new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE);

				$view->assign_block_vars('subcats', array_merge(
					Date::get_array_tpl_vars($last_msg_date, 'LAST_MESSAGE_DATE'), array(
					'C_BLINK'                 => $blink,
					'C_SUBFORUMS'             => !empty($subforums),
					'C_LAST_TOPIC_MSG'        => !empty($row['last_topic_id']),
					'C_LAST_MESSAGE_GUEST'    => !empty($row['display_name']),
					'C_HAS_THUMBNAIL'         => !empty($row['thumbnail']),
					'C_HAS_CATEGORY_ICON'     => !empty($row['icon']),
					'C_HAS_CATEGORY_COLOR'    => !empty($row['color']),
					'C_LAST_USER_GROUP_COLOR' => !empty($last_group_color),

					'TOPIC_ICON'              => $topic_icon,
					'CATEGORY_ID'             => $row['cid'],
					'CATEGORY_NAME'           => stripslashes($row['name']),
					'CATEGORY_PARENT_ID'      => $row['id_parent'],
					'CATEGORY_ICON'           => $row['icon'],
					'CATEGORY_COLOR'          => $row['color'],
					'DESCRIPTION'             => stripslashes($row['subname']),
					'REWRITED_NAME'           => $row['rewrited_name'],
					'SUBFORUMS'               => !empty($subforums) && !empty($row['subname']) ? $subforums : $subforums,
					'TOPICS_NUMBER'           => $row['nbr_topic'],
					'MESSAGES_NUMBER'         => $row['nbr_msg'],
					'LAST_TOPIC_TITLE'        => !empty($row['last_topic_id']) ? stripslashes($last_topic_title) : '',
					'LAST_USER_LOGIN'         => $row['display_name'],
					'LAST_USER_LEVEL'         => UserService::get_level_class($row['user_level']),
					'LAST_USER_GROUP_COLOR'   => $last_group_color,

					'U_LINK'                  => Url::to_rel($row['url']),
					'U_CATEGORY'              => url('.php?id=' . $row['cid'], '-' . $row['cid'] . '-' . $row['rewrited_name'] . '.php'),
					'U_LAST_TOPIC'            => "topic" . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '-' . Url::encode_rewrite($row['title']) . '.php'),
					'U_LAST_MESSAGE'          => !empty($row['last_topic_id']) ? "topic" . url('.php?' . $last_page . 'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title']) . '.php') . '#m' . $last_msg_id : '',
					'U_CATEGORY_THUMBNAIL' 	  => $categories_cache->get_category($row['cid'])->get_thumbnail()->rel(),
					'U_LAST_USER_PROFILE'     => UserUrlBuilder::profile($row['last_user_id'])->rel(),
				)));
			}
		}
	}

	//On vérifie si l'utilisateur a les droits d'écritures.
	$check_group_write_auth = ForumAuthorizationsService::check_authorizations($id_get)->write();
	$locked_cat = ($category->get_status() == ForumCategory::STATUS_LOCKED);
	if (!$check_group_write_auth)
	{
		$view->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $lang['forum.error.category.right']
		));
	}
	//Catégorie verrouillée?
	elseif ($locked_cat)
	{
		$check_group_write_auth = false;
		$view->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $lang['forum.error.locked.category']
		));
	}

	$nbr_topic = PersistenceContext::get_querier()->count(PREFIX . 'forum_topics', 'WHERE id_category=:id_category', array('id_category' => $id_get));

	//On crée une pagination (si activé) si le nombre de forum est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_topic, $config->get_number_topics_per_page(), Pagination::LIGHT_PAGINATION);
	$pagination->set_url(new Url('/forum/forum' . url('.php?id=' . $id_get . '&amp;p=%d', '-' . $id_get . '-%d-' . $category->get_rewrited_name() . '.php')));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Affichage de l'arborescence des catégories.
	$i = 0;
	$current_subcat = "";

	foreach ($Bread_crumb->get_links() as $key => $array)
	{
		if ($i >= 2)
		{
			$view->assign_block_vars('syndication_cats', array(
				'C_DISPLAY_RAQUO' => $i > 2,
				'LINK'            => $array[1],
				'LABEL'           => $array[0]
			));
		}
		$current_subcat = $array[0];
		$i++;
	}

	//Si l'utilisateur a les droits d'édition.
	$check_group_edit_auth = ForumAuthorizationsService::check_authorizations($id_get)->moderation();

	$vars_tpl = array(
		'C_THUMBNAILS_DISPLAYED' => $config->are_thumbnails_displayed(),
		'C_PAGINATION'           => $pagination->has_several_pages(),
		'C_CONTROLS'             => $check_group_edit_auth,
		'C_POST_NEW_TOPIC'       => $check_group_write_auth,
		'C_HAS_THUMBNAIL'        => !empty($categories_cache->get_category($id_get)->get_thumbnail()->rel()),
		'C_HAS_CATEGORY_ICON'    => !empty($categories_cache->get_category($id_get)->get_icon()),
		'C_HAS_CATEGORY_COLOR'   => !empty($categories_cache->get_category($id_get)->get_color()),

		'FORUM_NAME'          => $config->get_forum_name(),
		'PAGINATION'          => $pagination->display(),
		'CATEGORY_ID'         => $id_get,
		'CATEGORY_NAME'       => $category->get_name(),
		'CATEGORY_PARENT_ID'  => $category->get_id_parent(),
		'CATEGORY_SUB_ORDER'  => $category->get_order(),
		'CATEGORY_ICON'       => $category->get_icon(),
		'CATEGORY_COLOR'      => $category->get_color(),
		'CURRENT_SUBCAT_NAME' => $current_subcat,

		'U_MARK_AS_READ'       => Url::to_rel('/forum/action' . url('.php?read=1&amp;f=' . $id_get, '')),
		'U_CATEGORY_THUMBNAIL' => $categories_cache->get_category($id_get)->get_thumbnail()->rel(),
		'U_CHANGE_CAT'         => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '-' . $category->get_rewrited_name() . '.php'),
		'U_ONCHANGE'           => url(".php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
		'U_ONCHANGE_CAT'       => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
		'U_POST_NEW_SUBJECT'   => 'post' . url('.php?new=topic&amp;id=' . $id_get, ''),

		'L_CHANGE_STATUT_TO' => sprintf($lang['forum.change.issue.status.to'], $config->get_message_before_topic_title()),
	);

	$nbr_topics_display = 0;
	$result = PersistenceContext::get_querier()->select("SELECT
		m1.display_name AS login, m1.level AS user_level, m1.user_groups AS m_user_groups,
		m2.display_name AS last_login, m2.level AS last_user_level, m2.user_groups AS last_user_groups,
		t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id , t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, t.id_category,
		v.last_view_id,
		p.question,
		tr.id AS idtrack,
		msg.timestamp AS first_timestamp
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = :user_id AND v.idtopic = t.id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = t.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = t.last_user_id
	LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = t.id
	LEFT JOIN " . PREFIX . "forum_msg msg ON msg.id = t.first_msg_id
	LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = t.id AND tr.user_id = :user_id
	WHERE t.id_category = :id_category
	ORDER BY t.type DESC , t.last_timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from", array(
		'user_id' => AppContext::get_current_user()->get_id(),
		'id_category' => $id_get,
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	));
	while ($row = $result->fetch())
	{
		//On définit un array pour l'appellation correspondant au type de champ
		$type = array('2' => $lang['forum.announce'] . ':', '1' => $lang['forum.pinned'] . ':', '0' => '');

		//Vérifications des topics Lu/non Lus.
		$topic_icon = 'fa-announce';
		$new_msg = false;
		$blink = false;
		if (!$is_guest) //Non visible aux invités.
		{
			if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
			{
				$topic_icon =  $topic_icon . '-new'; //Image affiché aux visiteurs.
				$new_msg = true;
				$blink = true;
			}
		}
		$topic_icon .= ($row['type'] == '1') ? '-post' : '';
		$topic_icon .= ($row['type'] == '2') ? '-top' : '';
		$topic_icon .= ($row['status'] == '0' && $row['type'] == '0') ? '-lock' : '';

		//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
		//Puis calcul de la page du last_msg_id ou du last_view_id.
		if (!empty($row['last_view_id']))
		{
			$last_msg_id = $row['last_view_id'];
			$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id = $row['last_msg_id'];
			$last_page = ceil( $row['nbr_msg'] / $config->get_number_messages_per_page() );
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
		}

		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title_topic = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '-' . Url::encode_rewrite($row['title']) : '';

		//Ancre ajoutée aux messages non lus.
		$new_anchor = ($new_msg === true && !$is_guest) ? 'topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title_topic . '.php') . '#m' . $last_msg_id : '';

		//On crée une pagination (si activé) si le nombre de topics est trop important.
		$page = AppContext::get_request()->get_getint('pt', 1);
		$topic_pagination = new ModulePagination($page, $row['nbr_msg'], $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
		$topic_pagination->set_url(new Url('/forum/topic' . url('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d' . $rewrited_title_topic . '.php')));

		$group_color      = User::get_group_color($row['m_user_groups'], $row['user_level']);
		$last_group_color = User::get_group_color($row['last_user_groups'], $row['last_user_level']);

		$last_msg_date = new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE);
		$first_msg_date = new Date($row['first_timestamp'], Timezone::SERVER_TIMEZONE);

		$view->assign_block_vars('topics', array_merge(
			Date::get_array_tpl_vars($first_msg_date, 'first_message_date'),
			Date::get_array_tpl_vars($last_msg_date, 'last_message_date'),
			array(
			'C_PAGINATION'            => $topic_pagination->has_several_pages(),
			'C_IMG_POLL'              => !empty($row['question']),
			'C_IMG_TRACK'             => !empty($row['idtrack']),
			'C_DISPLAY_ISSUE_STATUS'  => ($config->is_message_before_topic_title_displayed() && $config->is_message_before_topic_title_icon_displayed() && $row['display_msg']),
			'C_HOT_TOPIC'             => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $config->get_number_messages_per_page())),
			'C_BLINK'                 => $blink,
			'C_ANCHOR'                => !empty($new_anchor),
			'C_AUTHOR'                => !empty($row['login']),
			'C_GROUP_COLOR'           => !empty($group_color),
			'C_LAST_MESSAGE_GUEST'    => !empty($row['last_login']),
			'C_LAST_USER_GROUP_COLOR' => !empty($last_group_color),

			'ID'              		  => $row['id'],
			'TOPIC_ICON'              => $topic_icon,
			'U_ANCHOR'                => $new_anchor,
			'TYPE'                    => $type[$row['type']],
			'CATEGORY_ID'             => $row['id_category'],
			'TITLE'                   => stripslashes($row['title']),
			'AUTHOR_LEVEL'            => UserService::get_level_class($row['user_level']),
			'AUTHOR'                  => $row['login'],
			'GROUP_COLOR'             => $group_color,
			'DESCRIPTION'             => stripslashes($row['subtitle']),
			'PAGINATION'              => $topic_pagination->display(),
			'MESSAGES_NUMBER'         => ($row['nbr_msg'] - 1),
			'VIEWS_NUMBER'            => $row['nbr_views'],
			'LAST_USER_LOGIN'         => $row['last_login'],
			'LAST_USER_LEVEL'         => UserService::get_level_class($row['last_user_level']),
			'LAST_USER_GROUP_COLOR'   => $last_group_color,

			'U_TOPIC'             => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title_topic . '.php'),
			'U_AUTHOR_PROFILE'    => UserUrlBuilder::profile($row['user_id'])->rel(),
			'U_LAST_USER_PROFILE' => UserUrlBuilder::profile($row['last_user_id'])->rel(),
			'U_LAST_MESSAGE'      => "topic" . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title_topic . '.php') . '#m' . $last_msg_id,

			'L_ISSUE_STATUS_MESSAGE'  => ($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : ''
			)
		));
		$nbr_topics_display++;
	}
	$result->dispose();

	//Affichage message aucun topics.
	if ($nbr_topics_display == 0)
	{
		$view->put_all(array(
			'C_NO_TOPICS' => true,
		));
	}

	//Listes les utilisateurs en ligne.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" . url('/forum/forum.php?id=' . $id_get, '/forum/forum-' . $id_get) . "%'");

	//Liste des catégories.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = CategoriesService::get_categories_manager('forum')->get_select_categories_form_field('cats', '', $id_get, $search_category_children_options);
	$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
	$method->setAccessible(true);
	$categories_tree_options = $method->invoke($categories_tree);
	$cat_list = '';
	foreach ($categories_tree_options as $option)
	{
		if ($option->get_raw_value())
		{
			$cat = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($option->get_raw_value());
			if (!$cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}

	$vars_tpl = array_merge($vars_tpl, array(
		'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'C_NO_USER_ONLINE' => (($total_online - $total_visit) == 0),

		'TOTAL_ONLINE'          => $total_online,
		'ONLINE_USERS_LIST'     => $users_list,
		'ADMINISTRATORS_NUMBER' => $total_admin,
		'MODERATORS_NUMBER'     => $total_modo,
		'MEMBERS_NUMBER'        => $total_member,
		'GUESTS_NUMBER'         => $total_visit,
		'SELECT_CAT'            => $cat_list, //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.

		'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
		'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
		'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators'] : $lang['user.moderator'],
		'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
		'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
	));

	$view->put_all($vars_tpl);
	$top_view->put_all($vars_tpl);
	$bottom_view->put_all($vars_tpl);

	$view->put('FORUM_TOP', $top_view);
	$view->put('FORUM_BOTTOM', $bottom_view);

	$view->display();
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('forum');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

include('../kernel/footer.php');
?>
