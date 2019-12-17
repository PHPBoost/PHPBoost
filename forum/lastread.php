<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 1.6 - 2007 04 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($LANG['show_last_read'], '');

define('TITLE', $LANG['show_last_read']);
require_once('../kernel/header.php');
$request = AppContext::get_request();

//Redirection changement de catégorie.
$change_cat = $request->get_postint('change_cat', 0);
if (!empty($change_cat))
{
	$new_cat = '';
	try {
		$new_cat = CategoriesService::get_categories_manager('forum', 'idcat')->get_categories_cache()->get_category($change_cat);
	} catch (CategoryNotFoundException $e) { }
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . ($new_cat && ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . $new_cat->get_rewrited_name() : '') . '.php', '&'));
}

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Réservé aux membres.
	AppContext::get_response()->redirect(UserUrlBuilder::connect()->rel());

if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Affichage des message()s non lu(s) du membre.
{
	$tpl = new FileTemplate('forum/forum_forum.tpl');

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

	$result = PersistenceContext::get_querier()->select("SELECT m1.display_name AS login, m1.level AS user_level, m1.groups AS groups, m2.display_name AS last_login, m2.level AS last_user_level, m2.groups AS last_user_groups, t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id, t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, v.last_view_id, p.question, tr.id AS idtrack
	FROM " . PREFIX . "forum_view v
	LEFT JOIN " . PREFIX . "forum_topics t ON t.id = v.idtopic
	LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
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
		$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');

		//Vérifications des topics Lu/non Lus.
		$img_announce = 'fa-announce';
		$blink = false;
		if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
		{
			$img_announce = $img_announce . '-new'; //Image affiché aux visiteurs.
			$blink = true;
		}
		$img_announce .= ($row['type'] == '1') ? '-post' : '';
		$img_announce .= ($row['type'] == '2') ? '-top' : '';
		$img_announce .= ($row['status'] == '0' && $row['type'] == '0') ? '-lock' : '';

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
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['title']) : '';

		//Ancre ajoutée aux messages non lus.
		$new_ancre = 'topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id ;

		//On crée une pagination (si activé) si le nombre de topics est trop important.
		$page = AppContext::get_request()->get_getint('pt', 1);
		$topic_pagination = new ModulePagination($page, $row['nbr_msg'], $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
		$topic_pagination->set_url(new Url('/forum/topic' . url('.php?id=' . $row['id'] . '&amp;pt=%d', '-' . $row['id'] . '-%d' . $rewrited_title . '.php')));

		$group_color = User::get_group_color($row['groups'], $row['user_level']);
		$last_group_color = User::get_group_color($row['last_user_groups'], $row['last_user_level']);

		$last_msg_date = new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE);

		$tpl->assign_block_vars('topics', array_merge(
			Date::get_array_tpl_vars($last_msg_date, 'last_msg_date'),
			array(
			'C_PAGINATION'                => $topic_pagination->has_several_pages(),
			'C_IMG_POLL'                  => !empty($row['question']),
			'C_IMG_TRACK'                 => !empty($row['idtrack']),
			'C_DISPLAY_MSG'               => ($config->is_message_before_topic_title_displayed() && $config->is_message_before_topic_title_icon_displayed() && $row['display_msg']),
			'C_HOT_TOPIC'                 => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $config->get_number_messages_per_page())),
			'C_BLINK'                     => $blink,
			'IMG_ANNOUNCE'                => $img_announce,
			'U_ANCRE'                     => $new_ancre,
			'TYPE'                        => $type[$row['type']],
			'TITLE'                       => stripslashes($row['title']),
			'C_AUTHOR'                    => !empty($row['login']),
			'U_AUTHOR'                    => UserUrlBuilder::profile($row['user_id'])->rel(),
			'AUTHOR_LEVEL'                => UserService::get_level_class($row['user_level']),
			'AUTHOR'                      => $row['login'],
			'C_GROUP_COLOR'               => !empty($group_color),
			'GROUP_COLOR'                 => $group_color,
			'L_GUEST'                     => $LANG['guest'],
			'DESC'                        => stripslashes($row['subtitle']),
			'PAGINATION'                  => $topic_pagination->display(),
			'MSG'                         => ($row['nbr_msg'] - 1),
			'VUS'                         => $row['nbr_views'],
			'U_TOPIC_VARS'                => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title . '.php'),
			'L_DISPLAY_MSG'               => ($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '',
			'LAST_MSG_URL'                => "topic" . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title . '.php') . '#m' . $last_msg_id,
			'C_LAST_MSG_GUEST'            => !empty($row['last_login']),
			'LAST_MSG_USER_PROFIL'        => UserUrlBuilder::profile($row['last_user_id'])->rel(),
			'LAST_MSG_USER_LOGIN'         => $row['last_login'],
			'LAST_MSG_USER_LEVEL'         => UserService::get_level_class($row['last_user_level']),
			'C_LAST_MSG_USER_GROUP_COLOR' => !empty($last_group_color),
			'LAST_MSG_USER_GROUP_COLOR'   => $last_group_color
			)
		));
	}
	$result->dispose();

	//Le membre a déjà lu tous les messages.
	if ($nbr_topics == 0)
	{
		$tpl->put_all(array(
			'C_NO_TOPICS' => true,
			'L_NO_TOPICS' => '0 ' . $LANG['no_last_read']
		));
	}

	//Listes les utilisateurs en ligne.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" ."/forum/lastread.php%'");

	//Liste des catégories.
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
	$categories_tree = CategoriesService::get_categories_manager('forum', 'idcat')->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
	$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
	$method->setAccessible(true);
	$categories_tree_options = $method->invoke($categories_tree);
	$cat_list = '';
	foreach ($categories_tree_options as $option)
	{
		if ($option->get_raw_value())
		{
			$cat = CategoriesService::get_categories_manager('forum', 'idcat')->get_categories_cache()->get_category($option->get_raw_value());
			if (!$cat->get_url())
				$cat_list .= $option->display()->render();
		}
	}
	
	$tpl->assign_block_vars('syndication_cats', array(
		'LINK'  => PATH_TO_ROOT . '/forum/lastread.php',
		'LABEL' => $LANG['show_last_read']
	));
	
	$vars_tpl = array(
		'C_USER_CONNECTED'   => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'TOTAL_ONLINE'       => $total_online,
		'C_NO_USER_ONLINE'   => (($total_online - $total_visit) == 0),
		'USERS_ONLINE'       => $users_list,
		'ADMIN'              => $total_admin,
		'MODO'               => $total_modo,
		'MEMBER'             => $total_member,
		'GUEST'              => $total_visit,
		'SELECT_CAT'         => $cat_list, //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
		'L_USER'             => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN'            => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO'             => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER'           => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST'            => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND'              => $LANG['and'],
		'L_ONLINE'           => TextHelper::strtolower($LANG['online']),
		'C_PAGINATION'       => $pagination->has_several_pages(),
		'FORUM_NAME'         => $config->get_forum_name(),
		'PAGINATION'         => $pagination->display(),
		'U_CHANGE_CAT'       => 'lastread.php' . '&amp;token=' . AppContext::get_session()->get_token(),
		'U_ONCHANGE'         => url(".php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
		'U_ONCHANGE_CAT'     => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
		'FORUM_CAT'          => $LANG['show_last_read'],
		'U_POST_NEW_SUBJECT' => '',
		'L_FORUM_INDEX'      => $LANG['forum_index'],
		'L_FORUM'            => $LANG['forum'],
		'L_AUTHOR'           => $LANG['author'],
		'L_TOPIC'            =>  ($nbr_topics > 1) ? $LANG['topic_s'] : $LANG['topic'],
		'L_MESSAGE'          => $LANG['replies'],
		'L_ANSWERS'          => $LANG['answers'],
		'L_VIEW'             => $LANG['views'],
		'L_LAST_MESSAGE'     => $LANG['last_message'],
	);

	$tpl->put_all($vars_tpl);
	$tpl_top->put_all($vars_tpl);
	$tpl_bottom->put_all($vars_tpl);

	$tpl->put('forum_top', $tpl_top);
	$tpl->put('forum_bottom', $tpl_bottom);

	$tpl->display();
}
else
	AppContext::get_response()->redirect('/forum/index.php');

include('../kernel/footer.php');

?>
