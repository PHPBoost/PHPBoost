<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.2 - 2005 10 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$request = AppContext::get_request();

//Redirection changement de catégorie.
$change_cat = $request->get_postint('change_cat', 0);
if (!empty($change_cat))
{
	$new_cat = '';
	try {
		$new_cat = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($change_cat);
	} catch (CategoryNotFoundException $e) { }
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . ($new_cat && ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . $new_cat->get_rewrited_name() : '') . '.php', '&'));
}

$id_get = $request->get_getint('id', 0);
$quote_get = $request->get_getint('quote', 0);

//On va chercher les infos sur le topic
try {
	$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id', 'user_id', 'id_category', 'title', 'subtitle', 'nbr_msg', 'last_msg_id', 'first_msg_id', 'last_timestamp', 'status', 'display_msg'), 'WHERE id=:id', array('id' => $id_get));
} catch (RowNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}
$topic['title'] = stripslashes($topic['title']);

//Existance de la catégorie.
if ($topic['id_category'] != Category::ROOT_CATEGORY && !CategoriesService::get_categories_manager()->get_categories_cache()->category_exists($topic['id_category']))
{
	$controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($controller);
}

try {
	$category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($topic['id_category']);
} catch (CategoryNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//Récupération de la barre d'arborescence.
$Bread_crumb->add($config->get_forum_name(), 'index.php');
$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($topic['id_category'], true));
foreach ($categories as $id => $cat)
{
	if ($cat->get_id() != Category::ROOT_CATEGORY)
		$Bread_crumb->add($cat->get_name(), 'forum' . url('.php?id=' . $cat->get_id(), '-' . $cat->get_id() . '+' . $cat->get_rewrited_name() . '.php'));
}
$Bread_crumb->add($topic['title'], '');

define('TITLE', $topic['title']);
define('DESCRIPTION', StringVars::replace_vars($LANG['topic_title_seo'], array('title' => $topic['title'], 'forum' => ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() : ''))));
require_once('../kernel/header.php');

$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($topic['title']) : ''; //On encode l'url pour un éventuel rewriting.

//Autorisation en lecture.
if (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->read() || !ForumAuthorizationsService::check_authorizations()->read_topics_content())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

if ($category->get_url())
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$tpl = new FileTemplate('forum/forum_topic.tpl');

$TmpTemplate = new FileTemplate('forum/forum_generic_results.tpl');
$module_data_path = $TmpTemplate->get_pictures_data_path();

//Si l'utilisateur a le droit de déplacer le topic, ou le verrouiller.
$check_group_edit_auth = ForumAuthorizationsService::check_authorizations($topic['id_category'])->moderation();
if ($check_group_edit_auth)
{
	$tpl->put_all(array(
		'C_FORUM_MODERATOR'    => true,
		'C_FORUM_LOCK_TOPIC'   => ($topic['status'] == '1'),
		'U_TOPIC_LOCK'         => url('.php?id=' . $id_get . '&amp;lock=true&amp;token=' . AppContext::get_session()->get_token()),
		'U_TOPIC_UNLOCK'       => url('.php?id=' . $id_get . '&amp;lock=false&amp;token=' . AppContext::get_session()->get_token()),
		'U_TOPIC_MOVE'         => url('.php?id=' . $id_get),
		'L_TOPIC_LOCK'         => ($topic['status'] == '1') ? $LANG['forum_lock'] : $LANG['forum_unlock'],
		'L_TOPIC_MOVE'         => $LANG['forum_move'],
		'L_ALERT_DELETE_TOPIC' => $LANG['alert_delete_topic'],
		'L_ALERT_LOCK_TOPIC'   => $LANG['alert_lock_topic'],
		'L_ALERT_UNLOCK_TOPIC' => $LANG['alert_unlock_topic'],
		'L_ALERT_MOVE_TOPIC'   => $LANG['alert_move_topic'],
		'L_ALERT_CUT_TOPIC'    => $LANG['alert_cut_topic']
	));
}
else
{
	$tpl->put_all(array(
		'C_FORUM_MODERATOR' => false
	));
}

//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou déjà lus).
mark_topic_as_read($id_get, $topic['last_msg_id'], $topic['last_timestamp']);

//Gestion de la page si redirection vers le dernier message lu.
$page = AppContext::get_request()->get_getint('pt', 1);
$idm = $request->get_getvalue('idm', 0);
if (!empty($idm))
{
	//Calcul de la page sur laquelle se situe le message.
	$nbr_msg_before = PersistenceContext::get_querier()->count(PREFIX . "forum_msg", 'WHERE idtopic = :idtopic AND id < :id', array('idtopic' => $id_get, 'id' => $idm)); //Nombre de message avant le message de destination.

	//Dernier message de la page? Redirection vers la page suivante pour prendre en compte la reprise du message précédent.
	if (is_int(($nbr_msg_before + 1) / $config->get_number_messages_per_page()))
	{
		//On redirige vers la page suivante, seulement si ce n'est pas la dernière.
		if ($topic['nbr_msg'] != ($nbr_msg_before + 1))
			$nbr_msg_before++;
	}

	$page = ceil(($nbr_msg_before + 1) / $config->get_number_messages_per_page()); //Modification de la page affichée.
}

//On crée une pagination si le nombre de msg est trop important.
$pagination = new ModulePagination($page, $topic['nbr_msg'], $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
$pagination->set_url(new Url('/forum/topic' . url('.php?id=' . $id_get . '&amp;pt=%d', '-' . $id_get . '-%d' . $rewrited_title . '.php')));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//Affichage de l'arborescence des catégories.
$i = 0;
$forum_cats = '';
$Bread_crumb->remove_last();
foreach ($Bread_crumb->get_links() as $key => $array)
{
	if ($i == 2)
		$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
	elseif ($i > 2)
		$forum_cats .= ' <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="' . $array[1] . '">' . $array[0] . '</a>';
	$i++;
}

$vars_tpl = array(
	'C_PAGINATION'            => $pagination->has_several_pages(),
	'C_FOCUS_CONTENT'         => !empty($quote_get),
	'FORUM_NAME'              => $config->get_forum_name(),
	'MODULE_DATA_PATH'        => $module_data_path,
	'DESC'                    => !empty($topic['subtitle']) ? stripslashes($topic['subtitle']) : '',
	'PAGINATION'              => $pagination->display(),
	'USER_ID'                 => $topic['user_id'],
	'ID'                      => $topic['id_category'],
	'IDTOPIC'                 => $id_get,
	'PAGE'                    => $page,
	'TITLE_T'                 => stripslashes($topic['title']),
	'DISPLAY_MSG'             => (($config->is_message_before_topic_title_displayed() && $topic['display_msg']) ? $config->get_message_before_topic_title() . ' ' : '') ,
	'U_MSG_SET_VIEW'          => Url::to_rel('/forum/action' . url('.php?read=1&amp;f=' . $topic['id_category'], '')),
	'U_CHANGE_CAT'            => 'topic' . url('.php?id=' . $id_get, '-' . $id_get . '+' . $category->get_rewrited_name() . '.php'),
	'U_ONCHANGE'              => url(".php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
	'U_ONCHANGE_CAT'          => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
	'U_FORUM_CAT'             => !empty($forum_cats) ? $forum_cats : '',
	'U_TITLE_T'               => 'topic' . url('.php?id=' . $id_get, '-' . $id_get . $rewrited_title . '.php'),
	'L_REQUIRE_MESSAGE'       => $LANG['require_text'],
	'L_DELETE_MESSAGE'        => $LANG['alert_delete_msg'],
	'L_GUEST'                 => $LANG['guest'],
	'L_DELETE'                => LangLoader::get_message('delete', 'common'),
	'L_EDIT'                  => LangLoader::get_message('edit', 'common'),
	'L_CUT_TOPIC'             => $LANG['cut_topic'],
	'L_EDIT_BY'               => $LANG['edit_by'],
	'L_PUNISHMENT_MANAGEMENT' => $LANG['punishment_management'],
	'L_WARNING_MANAGEMENT'    => $LANG['warning_management'],
	'L_FORUM_INDEX'           => $LANG['forum_index'],
	'L_QUOTE'                 => $LANG['quote'],
	'L_ON'                    => $LANG['on'],
	'L_RESPOND'               => $LANG['respond'],
	'L_SUBMIT'                => $LANG['submit'],
	'L_PREVIEW'               => $LANG['preview'],
	'L_RESET'                 => $LANG['reset']
);

$extended_fields_cache = ExtendedFieldsCache::load();
$displayed_extended_fields = $extended_fields_cache->get_websites_or_emails_extended_field_field_types();
$extended_fields_to_recover_list = '';
foreach ($displayed_extended_fields as $field_type)
{
	$extended_fields_to_recover_list .= 'ext_field.' . $field_type . ', ';
}

list($track, $track_pm, $track_mail, $poll_done) = array(false, false, false, false);
$ranks_cache = ForumRanksCache::load()->get_ranks(); //Récupère les rangs en cache.
$quote_last_msg = ($page > 1) ? 1 : 0; //On enlève 1 au limite si on est sur une page > 1, afin de récupérer le dernier msg de la page précédente.
$i = 0;
$j = 0;
$result = PersistenceContext::get_querier()->select("SELECT
	msg.id, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, msg.contents,
	m.user_id, m.delay_readonly, m.delay_banned, m.display_name as login, m.level, m.groups, m.email, m.show_email, m.registration_date AS registered, m.posted_msg,
	p.question, p.answers, p.voter_id, p.votes, p.type,
	ext_field.user_avatar,
	m2.display_name as login_edit,
	s.user_id AS connect,
	tr.id AS trackid, tr.pm as trackpm, tr.track AS track, tr.mail AS trackmail,
	ext_field.user_sign, " . $extended_fields_to_recover_list . "m.warning_percentage
FROM " . PREFIX . "forum_msg msg
LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = :idtopic
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = msg.user_id_edit
LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = :idtopic AND tr.user_id = :user_id
LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > :timestamp AND s.user_id != -1
WHERE msg.idtopic = :idtopic
ORDER BY msg.timestamp
LIMIT :number_items_per_page OFFSET :display_from", array(
	'idtopic' => $id_get,
	'user_id' => AppContext::get_current_user()->get_id(),
	'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration()),
	'number_items_per_page' => $pagination->get_number_items_per_page() + $quote_last_msg,
	'display_from' => $pagination->get_display_from() - $quote_last_msg
));
while ( $row = $result->fetch() )
{
	//Invité?
	$is_guest = empty($row['user_id']);
	$first_message = $row['id'] == $topic['first_msg_id'];

	//Gestion du niveau d'autorisation.
	list($edit, $del, $cut, $moderator) = array(false, false, false, false);
	if ($check_group_edit_auth || (AppContext::get_current_user()->get_id() == $row['user_id'] && !$is_guest && !$first_message))
	{
		list($edit, $del) = array(true, true);
		if ($check_group_edit_auth) //Fonctions réservées à ceux possédants les droits de modérateurs seulement.
		{
			$cut = !$first_message;
			$moderator = !$is_guest;
		}
	}
	elseif (AppContext::get_current_user()->get_id() == $row['user_id'] && !$is_guest && $first_message) //Premier msg du topic => suppression du topic non autorisé au membre auteur du message.
		$edit = true;

	//Gestion des sondages => executé une seule fois.
	if (!empty($row['question']) && $poll_done === false)
	{
		$tpl->put_all(array(
			'C_POLL_EXIST'  => true,
			'QUESTION'      => stripslashes($row['question']),
			'U_POLL_RESULT' => url('.php?id=' . $id_get . '&amp;r=1&amp;pt=' . $page),
			'U_POLL_ACTION' => url('.php?id=' . $id_get . '&amp;p=' . $page . '&amp;token=' . AppContext::get_session()->get_token()),
			'L_POLL'        => $LANG['poll'],
			'L_VOTE'        => $LANG['poll_vote'],
			'L_RESULT'      => $LANG['poll_result']
		));

		$array_voter = explode('|', $row['voter_id']);
		if (in_array(AppContext::get_current_user()->get_id(), $array_voter) || $request->get_getvalue('r', 0) || AppContext::get_current_user()->get_id() === -1) //Déjà voté.
		{
			$array_answer = explode('|', $row['answers']);
			$array_vote = explode('|', $row['votes']);

			$sum_vote = (int)array_sum($array_vote);
			$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.

			foreach ($array_answer as $key => $answer)
			{
				$total = (int)$array_vote[$key];
				$percent = NumberHelper::round(($total * 100 / $sum_vote), 1);

				$tpl->assign_block_vars('poll_result', array(
					'ANSWERS' => stripslashes($answer),
					'NBRVOTE' => $total,
					'WIDTH'   => $percent * 4, //x 4 Pour agrandir la barre de vote.
					'PERCENT' => $percent
				));
			}
		}
		else //Affichage des formulaires (radio/checkbox) pour voter.
		{
			$tpl->put_all(array(
				'C_POLL_QUESTION' => true
			));

			$z = 0;
			$array_answer = explode('|', $row['answers']);
			if ($row['type'] == 0)
			{
				foreach ($array_answer as $answer)
				{
					$tpl->assign_block_vars('poll_radio', array(
						'NAME'    => $z,
						'TYPE'    => 'radio',
						'ANSWERS' => stripslashes($answer)
					));
					$z++;
				}
			}
			elseif ($row['type'] == 1)
			{
				foreach ($array_answer as $answer)
				{
					$tpl->assign_block_vars('poll_checkbox', array(
						'NAME'    => 'forumpoll' . $z,
						'TYPE'    => 'checkbox',
						'ANSWERS' => stripslashes($answer)
					));
					$z++;
				}
			}
		}
		$poll_done = true;
	}

	//Rang de l'utilisateur.
	$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
	$user_group = $user_rank;
	$user_rank_icon = '';
	if ($row['level'] === '2') //Rang spécial (admins).
	{
		$user_rank = $ranks_cache[-2]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-2]['icon'];
	}
	elseif ($row['level'] === '1') //Rang spécial (modos).
	{
		$user_rank = $ranks_cache[-1]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-1]['icon'];
	}
	else
	{
		foreach ($ranks_cache as $msg => $ranks_info)
		{
			if ($msg >= 0 && $msg <= $row['posted_msg'])
			{
				$user_rank = $ranks_info['name'];
				$user_rank_icon = $ranks_info['icon'];
			}
		}
	}

	$theme = AppContext::get_current_user()->get_theme();
	//Image associée au rang.

	//Chemin relatif pour faire fonctionner file_exists
	$img = PATH_TO_ROOT . '/templates/' . $theme . '/modules/forum/images/ranks/' . $user_rank_icon;
	if ( file_exists($img) )
		$rank_img = $img;
	else
		$rank_img = TPL_PATH_TO_ROOT . '/forum/templates/images/ranks/' . $user_rank_icon;

	$user_accounts_config = UserAccountsConfig::load();
	$user_group_color     = User::get_group_color($row['groups'], $row['level']);

	$user_sign_field = $extended_fields_cache->get_extended_field_by_field_name('user_sign');

	$topic_date           = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);
	$topic_edit_date      = new Date($row['timestamp_edit'], Timezone::SERVER_TIMEZONE);
	$user_registered_date = new Date($row['registered'], Timezone::SERVER_TIMEZONE);

	$tpl->assign_block_vars('msg', array_merge(
		Date::get_array_tpl_vars($topic_date,'TOPIC_DATE'),
		Date::get_array_tpl_vars($topic_edit_date,'TOPIC_EDIT_DATE'),
		Date::get_array_tpl_vars($user_registered_date,'USER_REGISTERED_DATE'), array(
		'C_CURRENT_USER_MESSAGE' 	  => AppContext::get_current_user()->get_display_name() == $row['login'],
		'ID'                          => $row['id'],
		'CLASS_COLOR'                 => ($j%2 == 0) ? '' : 2,
		'FORUM_USER_LOGIN'            => $row['login'],
		'FORUM_MSG_CONTENTS'          => FormatingHelper::second_parse(stripslashes($row['contents'])),
		'FORUM_USER_EDITOR_LOGIN'     => $row['login_edit'],
		'FORUM_USER_LEVEL'            => UserService::get_level_class($row['level']),
		'C_FORUM_USER_GROUP_COLOR'    => !empty($user_group_color),
		'FORUM_USER_GROUP_COLOR'      => $user_group_color,
		'C_USER_RANK'                 => ($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0),
		'USER_RANK'                   => $user_rank,
		'USER_IMG_ASSOC'              => $rank_img,
		'C_USER_IMG_ASSOC'            => !empty($rank_img),
		'C_USER_AVATAR'               => !empty($row['user_avatar']),
		'U_USER_AVATAR'               => Url::to_rel($row['user_avatar']),
		'U_DEFAULT_AVATAR'            => '../templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name(),
		'C_USER_GROUPS'               => $row['groups'],
		'C_IS_USER'                   => !$is_guest,
		'C_USER_MSG'                  => $row['posted_msg'] >= 1,
		'U_USER_MSG'                  => UserUrlBuilder::messages($row['user_id'])->rel(),
		'U_USER_MEMBERMSG'            => PATH_TO_ROOT . '/forum/membermsg' . url('.php?id=' . $row['user_id'], ''),
		'USER_MSG'                    => $row['posted_msg'],
		'C_USER_MAIL'                 => (!empty($row['email']) && ($row['show_email'] == '1' ) ),
		'U_USER_MAIL'                 => 'mailto:' . $row['email'],
		'C_USER_SIGN'                 => (!empty($row['user_sign']) && !empty($user_sign_field) && $user_sign_field['display']),
		'USER_SIGN'                   => FormatingHelper::second_parse($row['user_sign']),
		'USER_WARNING'                => $row['warning_percentage'],
		'L_FORUM_QUOTE_LAST_MSG'      => ($quote_last_msg == 1 && $i == 0) ? $LANG['forum_quote_last_msg'] : '', //Reprise du dernier message de la page précédente.
		'C_USER_ONLINE'               => !empty($row['connect']),
		'C_FORUM_USER_LOGIN'          => !empty($row['login']),
		'C_FORUM_MSG_EDIT'            => $edit,
		'C_FORUM_MSG_DEL'             => $del,
		'C_FORUM_MSG_DEL_MSG'         => (!$first_message),
		'C_FORUM_MSG_CUT'             => $cut,
		'C_FORUM_USER_EDITOR'         => ($row['timestamp_edit'] > 0 && $config->is_edit_mark_enabled()), //Ajout du marqueur d'édition si activé.
		'C_FORUM_USER_EDITOR_LOGIN'   => !empty($row['login_edit']),
		'C_FORUM_MODERATOR'           => $moderator,
		'U_FORUM_USER_PROFILE'        => UserUrlBuilder::profile($row['user_id'])->rel(),
		'U_FORUM_MSG_EDIT'            => url('.php?new=msg&amp;idm=' . $row['id'] . '&amp;id=' . $topic['id_category'] . '&amp;idt=' . $id_get),
		'U_FORUM_USER_EDITOR_PROFILE' => UserUrlBuilder::profile($row['user_id_edit'])->rel(),
		'U_FORUM_MSG_DEL'             => url('.php?del=1&amp;idm=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_FORUM_WARNING'             => url('.php?action=warning&amp;id=' . $row['user_id']),
		'U_FORUM_PUNISHEMENT'         => url('.php?action=punish&amp;id=' . $row['user_id']),
		'U_FORUM_MSG_CUT'             => url('.php?idm=' . $row['id']),
		'U_VARS_ANCRE'                => url('.php?id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '') . $rewrited_title . '.php'),
		'U_VARS_QUOTE'                => url('.php?quote=' . $row['id'] . '&amp;id=' . $id_get . (!empty($page) ? '&amp;pt=' . $page : ''), '-' . $id_get . (!empty($page) ? '-' . $page : '-0') . '-0-' . $row['id'] . $rewrited_title . '.php'),
		'C_USER_PM'                   => !$is_guest && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'U_USER_PM'                   => UserUrlBuilder::personnal_message($row['user_id'])->rel()
	)));

	//Affichage des groupes du membre.
	if (!empty($row['groups']))
	{
		$user_groups = '';
		$array_user_groups = explode('|', $row['groups']);
		foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
		{
			$group_color = User::get_group_color($idgroup);

			if (is_numeric(array_search($idgroup, $array_user_groups)))
			{
				$tpl->assign_block_vars('msg.usergroups', array(
					'C_IMG_USERGROUP'   => !empty($array_group_info['img']),
					'U_IMG_USERGROUP'   => $array_group_info['img'],
					'U_USERGROUP'       => UserUrlBuilder::group($idgroup)->rel(),
					'C_USERGROUP_COLOR' => !empty($group_color),
					'L_USER_GROUP'      => $LANG['group'],
					'USERGROUP_COLOR'   => $group_color,
					'USERGROUP_NAME'    => $array_group_info['name'],
					'USERGROUP_ID'      => $idgroup
				));
			}
		}
	}

	foreach ($displayed_extended_fields as $field_type)
	{
		$field = $extended_fields_cache->get_extended_field_by_field_name($field_type);

		if (!empty($row[$field_type]) && !empty($field) && $field['display'])
		{
			$button = '';
			$icon_fa = '';
			$title = '';
			$unknown_field = true;

			if ($field['regex'] == 4)
			{
				foreach (MemberShortTextExtendedField::$brands_pictures_list as $id => $parameters)
				{
					if (TextHelper::strstr($row[$field_type], $id))
					{
						$button = '<a href="mailto:' . $row[$field_type] . '" class="button small"><i class="fa ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
						$title = $parameters['title'];
						$icon_fa = $parameters['picture'];
						$unknown_field = false;
					}
				}
				if ($title == '')
				{
					$title = LangLoader::get_message('regex.mail', 'admin-user-common');
					$icon_fa = 'fa-mail';
				}
			}
			else if ($field['regex'] == 5)
			{
				$button = '<a href="' . $row[$field_type] . '" class="button submit smaller user-website">' . LangLoader::get_message('regex.website', 'admin-user-common') . '</a>';

				foreach (MemberShortTextExtendedField::$brands_pictures_list as $id => $parameters)
				{
					if (TextHelper::strstr($row[$field_type], $id))
					{
						$button = '<a href="' . $row[$field_type] . '" class="button small"><i class="fa ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
						$title = $parameters['title'];
						$icon_fa = $parameters['picture'];
						$unknown_field = false;
					}
				}
				if ($title == '')
				{
					$title = LangLoader::get_message('regex.website', 'admin-user-common');
					$icon_fa = 'fa-website';
				}
			}

			$tpl->assign_block_vars('msg.ext_fields', array(
				'BUTTON'     => $button,
				'U_URL'      => $row[$field_type],
				'NAME'       => $field['name'],
				'ID'         => str_replace('_', '-', $field['field_name']),
				'IS_MAIL'    => ($field['regex'] == 4) ? true : false,
				'IS_UNKNOWN' => $unknown_field,
				'TITLE'      => $title,
				'ICON_FA'    => $icon_fa
			));
		}
	}

	//Marqueur de suivis du sujet.
	if (!empty($row['trackid']))
	{
		$track = ($row['track']);
		$track_pm = ($row['trackpm']);
		$track_mail = ($row['trackmail']);
	}
	$j++;
	$i++;
}
$result->dispose();

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" . url('/forum/topic.php?id=' . $id_get, '/forum/topic-' . $id_get) ."%'");

//Liste des catégories.
$search_category_children_options = new SearchCategoryChildrensOptions();
$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
$categories_tree = CategoriesService::get_categories_manager()->get_select_categories_form_field('cats', '', $topic['id_category'], $search_category_children_options);
$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
$method->setAccessible(true);
$categories_tree_options = $method->invoke($categories_tree);
$cat_list = '';
foreach ($categories_tree_options as $option)
{
	if ($option->get_raw_value())
	{
		$cat = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
		if (!$cat->get_url())
			$cat_list .= $option->display()->render();
	}
}

$vars_tpl = array_merge($vars_tpl, array(
	'C_USER_CONNECTED'       => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'TOTAL_ONLINE'           => $total_online,
	'C_NO_USER_ONLINE'       => (($total_online - $total_visit) == 0),
	'USERS_ONLINE'           => $users_list,
	'ADMIN'                  => $total_admin,
	'MODO'                   => $total_modo,
	'MEMBER'                 => $total_member,
	'GUEST'                  => $total_visit,
	'SELECT_CAT'             => $cat_list, //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'IS_TRACK'               => $track ? 'true' : 'false',
	'IS_TRACK_PM'            => $track_pm ? 'true' : 'false',
	'IS_TRACK_MAIL'          => $track_mail ? 'true' : 'false',
	'IS_CHANGE'              => $topic['display_msg'] ? 'true' : 'false',
	'U_ALERT'                => url('.php?id=' . $id_get),
	'L_TRACK_DEFAULT'        => ($track) ? $LANG['untrack_topic'] : $LANG['track_topic'],
	'L_SUBSCRIBE_DEFAULT'    => ($track_mail) ? $LANG['untrack_topic_mail'] : $LANG['track_topic_mail'],
	'L_SUBSCRIBE_PM_DEFAULT' => ($track_pm) ? $LANG['untrack_topic_pm'] : $LANG['track_topic_pm'],
	'L_TRACK'                => $LANG['track_topic'],
	'L_UNTRACK'              => $LANG['untrack_topic'],
	'L_SUBSCRIBE_PM'         => $LANG['track_topic_pm'],
	'L_UNSUBSCRIBE_PM'       => $LANG['untrack_topic_pm'],
	'L_SUBSCRIBE'            => $LANG['track_topic_mail'],
	'L_UNSUBSCRIBE'          => $LANG['untrack_topic_mail'],
	'L_ALERT'                => $LANG['alert_topic'],
	'L_USER'                 => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN'                => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO'                 => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER'               => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST'                => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND'                  => $LANG['and'],
	'L_ONLINE'               => TextHelper::strtolower($LANG['online']),
));

//Récupération du message quoté.
$contents = '';
if (!empty($quote_get))
{
	try {
		$quote_msg = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_msg', array('user_id', 'contents'), 'WHERE id=:id', array('id' => $quote_get));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$pseudo = '';
	try {
		$pseudo = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'display_name', 'WHERE user_id=:id', array('id' => $quote_msg['user_id']));
	} catch (RowNotFoundException $e) {}

	$contents = '[quote' . (!empty($pseudo) ? '=' . $pseudo : '') . ']' . FormatingHelper::unparse(stripslashes($quote_msg['contents'])) . '[/quote]';
}

//Formulaire de réponse, non présent si verrouillé.
if ($topic['status'] == '0' && !$check_group_edit_auth)
{
	$tpl->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e.forum.topic.locked']
	));
}
elseif (!ForumAuthorizationsService::check_authorizations($topic['id_category'])->write()) //On vérifie si l'utilisateur a les droits d'écritures.
{
	$tpl->put_all(array(
		'C_ERROR_AUTH_WRITE' => true,
		'L_ERROR_AUTH_WRITE' => $LANG['e.category.right']
	));
}
else
{
	$img_track_display = $track ? 'heart-broken' : 'heart error';
	$img_track_pm_display = $track_pm ? 'envelope error' : 'envelope-open-text success';
	$img_track_mail_display = $track_mail ? 'at error' : 'at success';

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$vars_tpl = array_merge($vars_tpl, array(
		'C_AUTH_POST'         => true,
		'CONTENTS'            => $contents,
		'KERNEL_EDITOR'       => $editor->display(),
		'ICON_TRACK'          => $img_track_display,
		'ICON_SUBSCRIBE_PM'   => $img_track_pm_display,
		'ICON_SUBSCRIBE'      => $img_track_mail_display,
		'U_FORUM_ACTION_POST' => url('.php?idt=' . $id_get . '&amp;id=' . $topic['id_category'] . '&amp;new=n_msg&amp;token=' . AppContext::get_session()->get_token()),
	));

	//Affichage du lien pour changer le display_msg du topic et autorisation d'édition du statut.
	if ($config->is_message_before_topic_title_displayed() && ($check_group_edit_auth || AppContext::get_current_user()->get_id() == $topic['user_id']))
	{
		$img_msg_display = $topic['display_msg'] ? 'times error' : 'check success';
		$tpl_bottom->put_all(array(
			'C_DISPLAY_MSG'                 => true,
			'C_ICON_DISPLAY_MSG'            => $config->is_message_before_topic_title_icon_displayed(),
			'ICON_DISPLAY_MSG'              => $img_msg_display,
			'L_DISPLAY_MSG'                 => $config->get_message_before_topic_title(),
			'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $config->get_message_when_topic_is_solved() : $config->get_message_when_topic_is_unsolved(),
			'L_EXPLAIN_DISPLAY_MSG'         => $config->get_message_when_topic_is_unsolved(),
			'L_EXPLAIN_DISPLAY_MSG_BIS'     => $config->get_message_when_topic_is_solved(),
		));
		$tpl->put_all(array(
			'C_DISPLAY_MSG'                 => true,
			'C_ICON_DISPLAY_MSG'            => $config->is_message_before_topic_title_icon_displayed(),
			'ICON_DISPLAY_MSG'              => $img_msg_display,
			'L_DISPLAY_MSG'                 => $config->get_message_before_topic_title(),
			'L_EXPLAIN_DISPLAY_MSG_DEFAULT' => $topic['display_msg'] ? $config->get_message_when_topic_is_solved() : $config->get_message_when_topic_is_unsolved(),
			'L_EXPLAIN_DISPLAY_MSG'         => $config->get_message_when_topic_is_unsolved(),
			'L_EXPLAIN_DISPLAY_MSG_BIS'     => $config->get_message_when_topic_is_solved(),
		));
	}
}

$tpl->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);

$tpl->put('forum_top', $tpl_top);
$tpl->put('forum_bottom', $tpl_bottom);
$tpl->display();

include('../kernel/footer.php');

?>
