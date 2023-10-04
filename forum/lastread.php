<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 1.6 - 2007 04 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get_all_langs('forum');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($lang['forum.last.read.messages'], '');

define('TITLE', $lang['forum.last.read.messages']);
require_once('../kernel/header.php');
$request = AppContext::get_request();

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

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Réservé aux membres.
	AppContext::get_response()->redirect(UserUrlBuilder::connect()->rel());

if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Affichage des message()s non lu(s) du membre.
{
	$view = new FileTemplate('forum/forum_forum.tpl');
	$view->add_lang($lang);

	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time = (time() - ($config->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = forum_limit_time_msg();

	$nbr_topics = 0;

	try {
		$row = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as nbr_topics
		FROM " . PREFIX . "forum_view v
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = v.idtopic
		WHERE t.last_timestamp >= :timestamp AND v.user_id = :user_id", array(
			'timestamp' => $max_time,
			'user_id' => AppContext::get_current_user()->get_id()
		));
		$nbr_topics = $row['nbr_topics'];
	} catch (RowNotFoundException $e) {}

	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_topics, $config->get_number_topics_per_page(), Pagination::LIGHT_PAGINATION);
	$pagination->set_url(new Url('/forum/lastread.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$result = PersistenceContext::get_querier()->select("SELECT
		m1.display_name AS login, m1.level AS user_level, m1.user_groups AS user_groups,
		m2.display_name AS last_login, m2.level AS last_user_level, m2.user_groups AS last_user_groups,
		t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id, t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, t.id_category,
		v.last_view_id,
		p.question,
		tr.id AS idtrack
	FROM " . PREFIX . "forum_view v
	LEFT JOIN " . PREFIX . "forum_topics t ON t.id = v.idtopic
	LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
	LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = t.id
	LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = t.id AND tr.user_id = :user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = t.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = t.last_user_id
	WHERE t.last_timestamp >= :timestamp AND v.user_id = :user_id
	ORDER BY t.last_timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from", array(
		'user_id' => AppContext::get_current_user()->get_id(),
		'timestamp' => $max_time,
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	));
	while ($row = $result->fetch())
	{
		//On définit un array pour l'appelation correspondant au type de champ
		$type = array('2' => $lang['forum.announce'] . ':', '1' => $lang['forum.pinned'] . ':', '0' => '');

		//Vérifications des topics Lu/non Lus.
		$topic_icon = 'fa-announce';
		$blink = false;
		if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
		{
			$topic_icon = $topic_icon . '-new'; //Image affiché aux visiteurs.
			$blink = true;
		}
		$topic_icon .= ($row['type'] == '1') ? '-post' : '';
		$topic_icon .= ($row['type'] == '2') ? '-top' : '';
		$topic_icon .= ($row['status'] == '0' && $row['type'] == '0') ? '-lock' : '';

		//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
		if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
		{
			$last_msg_id       = $row['last_view_id'];
			$last_page         = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id       = $row['last_msg_id'];
			$last_page         = ceil( $row['nbr_msg'] / $config->get_number_messages_per_page() );
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page         = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
		}

		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '-' . Url::encode_rewrite($row['title']) : '';

		//Ancre ajoutée aux messages non lus.
		$new_anchor = 'topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id ;

		//On crée une pagination (si activé) si le nombre de topics est trop important.
		$page = AppContext::get_request()->get_getint('pt', 1);
		$topic_pagination = new ModulePagination($page, $row['nbr_msg'], $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
		$topic_pagination->set_url(new Url('/forum/topic' . url('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d' . $rewrited_title . '.php')));

		$group_color = User::get_group_color($row['user_groups'], $row['user_level']);
		$last_group_color = User::get_group_color($row['last_user_groups'], $row['last_user_level']);

		$last_msg_date = new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE);

		$view->assign_block_vars('topics', array_merge(
			Date::get_array_tpl_vars($last_msg_date, 'last_message_date'),
			array(
			'C_PAGINATION'            => $topic_pagination->has_several_pages(),
			'C_IMG_POLL'              => !empty($row['question']),
			'C_IMG_TRACK'             => !empty($row['idtrack']),
			'C_DISPLAY_ISSUE_STATUS'  => ($config->is_message_before_topic_title_displayed() && $config->is_message_before_topic_title_icon_displayed() && $row['display_msg']),
			'C_HOT_TOPIC'             => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $config->get_number_messages_per_page())),
			'C_BLINK'                 => $blink,
			'C_AUTHOR'                => !empty($row['login']),
			'C_GROUP_COLOR'           => !empty($group_color),
			'C_LAST_MESSAGE_GUEST'    => !empty($row['last_login']),
			'C_LAST_USER_GROUP_COLOR' => !empty($last_group_color),

			'CATEGORY_ID'             => $row['id_category'],
			'TITLE'                   => stripslashes($row['title']),
			'TOPIC_ICON'              => $topic_icon,
			'TYPE'                    => $type[$row['type']],
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

			'U_ANCHOR'                => $new_anchor,
			'U_AUTHOR_PROFILE'        => UserUrlBuilder::profile($row['user_id'])->rel(),
			'U_TOPIC'                 => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
			'U_LAST_MESSAGE'          => "topic" . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id,
			'U_LAST_USER_PROFILE'     => UserUrlBuilder::profile($row['last_user_id'])->rel(),

			'L_ISSUE_STATUS_MESSAGE'  => ($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '',
			)
		));
	}
	$result->dispose();

	//Le membre a déjà lu tous les messages.
	if ($nbr_topics == 0)
	{
		$view->put_all(array(
			'C_NO_TOPICS' => true
		));
	}

	//Listes les utilisateurs en ligne.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" ."/forum/lastread.php%'");

	//Liste des catégories.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = CategoriesService::get_categories_manager('forum')->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
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

	$view->assign_block_vars('syndication_cats', array(
		'LINK'  => PATH_TO_ROOT . '/forum/lastread.php',
		'LABEL' => $lang['forum.last.read.messages']
	));

	$vars_tpl = array(
		'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),
		'C_PAGINATION'          => $pagination->has_several_pages(),

		'FORUM_NAME'            => $config->get_forum_name(),
		'PAGINATION'            => $pagination->display(),
		'TOTAL_ONLINE'          => $total_online,
		'ONLINE_USERS_LIST'     => $users_list,
		'ADMINISTRATORS_NUMBER' => $total_admin,
		'MODERATORS_NUMBER'     => $total_modo,
		'MEMBERS_NUMBER'        => $total_member,
		'GUESTS_NUMBER'         => $total_visit,
		'SELECT_CAT'            => $cat_list, //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
		'CATEGORY_NAME'         => $lang['forum.last.read.messages'],

		'U_CHANGE_CAT'       => 'lastread.php' . '&amp;token=' . AppContext::get_session()->get_token(),
		'U_ONCHANGE'         => url(".php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
		'U_ONCHANGE_CAT'     => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
		'U_POST_NEW_SUBJECT' => '',

		'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
		'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
		'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators']    : $lang['user.moderator'],
		'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
		'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
		'L_TOPIC'  => ($nbr_topics > 1) ? $lang['forum.topics'] : $lang['forum.topic'],
	);

	$view->put_all($vars_tpl);
	$top_view->put_all($vars_tpl);
	$bottom_view->put_all($vars_tpl);

	$view->put('FORUM_TOP', $top_view);
	$view->put('FORUM_BOTTOM', $bottom_view);

	$view->display();
}
else
	AppContext::get_response()->redirect('/forum/index.php');

include('../kernel/footer.php');

?>
