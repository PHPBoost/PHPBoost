<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 30
 * @since       PHPBoost 1.6 - 2007 04 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get_all_langs('forum');

$member = UserService::get_user(AppContext::get_request()->get_getint('id', AppContext::get_current_user()->get_id()));
if (!$member)
	DispatchManager::redirect(PHPBoostErrors::unexisting_element());

$page_title = $member->get_id() == AppContext::get_current_user()->get_id() ? $lang['forum.my.items'] : $lang['forum.member.items'] . ' ' . $member->get_display_name();

$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($page_title, 'membermsg.php?id=' . $member->get_id());

define('TITLE', $page_title);
define('DESCRIPTION', StringVars::replace_vars($lang['forum.member.messages.seo'], array('author' => $member->get_display_name())));
require_once('../kernel/header.php');
$request = AppContext::get_request();

if (!AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$ranks_cache = ForumRanksCache::load()->get_ranks(); // Loads all ranks from cache
$extended_fields_cache = ExtendedFieldsCache::load();
$displayed_extended_fields = $extended_fields_cache->get_websites_or_emails_extended_field_field_types();
$extended_fields_to_recover_list = '';
foreach ($displayed_extended_fields as $field_type)
{
	$extended_fields_to_recover_list .= 'ext_field.' . $field_type . ', ';
}

$view = new FileTemplate('forum/forum_membermsg.tpl');
$view->add_lang($lang);

$authorized_categories = CategoriesService::get_authorized_categories();

$nbr_msg = 0;

if (ForumAuthorizationsService::check_authorizations()->read_topics_content())
{
	try {
		$row = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as nbr_msg
		FROM " . PREFIX . "forum_msg msg
		LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
		WHERE msg.user_id = :user_id AND t.id_category IN :authorized_categories", array(
			'authorized_categories' => $authorized_categories,
			'user_id' => $member->get_id()
		));
		$nbr_msg = $row['nbr_msg'];
	} catch (RowNotFoundException $e) {}

	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_msg, $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
	$pagination->set_url(new Url('/forum/membermsg.php?id=' . $member->get_id() . '&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	$view->put_all(array(
		'C_ITEMS'         => $nbr_msg > 0,
		'C_PAGINATION'    => $pagination->has_several_pages(),

		'FORUM_NAME'      => $config->get_forum_name(),
		'PAGINATION'      => $pagination->display(),

		'L_VIEW_MSG_USER' => $page_title,
	));

	$result = PersistenceContext::get_querier()->select("SELECT
		msg.id, msg.user_id, msg.idtopic, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, msg.content,
		m2.display_name AS login_edit,
		m.user_groups, m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, m.posted_msg, m.warning_percentage, m.delay_banned, m.posted_msg,
		t.title, t.status, t.id_category, t.nbr_msg,
		c.name,
		s.user_id AS connect,
		ext_field.user_avatar, ext_field.user_sign,
		" . $extended_fields_to_recover_list . "m.warning_percentage, m.delay_readonly, m.delay_banned
	FROM " . PREFIX . "forum_msg msg
	LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
	LEFT JOIN " . ForumSetup::$forum_cats_table . " c ON c.id = t.id_category
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = :user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = msg.user_id_edit
	LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
	LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > :timestamp
	WHERE msg.user_id = :user_id AND t.id_category IN :authorized_categories
	ORDER BY msg.id DESC
	LIMIT :number_items_per_page OFFSET :display_from", array(
		'user_id' => $member->get_id(),
		'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration()),
		'authorized_categories' => $authorized_categories,
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	));
	while ($row = $result->fetch())
	{
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_cat_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['name']) : '';
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['title']) : '';

		//Ajout du marqueur d'édition si activé.
		$edit_mark = ($row['timestamp_edit'] > 0 && $config->is_edit_mark_enabled()) ? '<span class="edit-pseudo">' . $lang['forum.edited.by'] . ' <a href="'. UserUrlBuilder::profile($row['user_id_edit'])->rel() .'" class="offload">' . $row['login_edit'] . '</a> ' . $lang['common.on.date'] . ' ' . Date::to_format($row['timestamp_edit'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '</span><br />' : '';

		$group_color = User::get_group_color($row['user_groups'], $row['level']);

		//Rang de l'utilisateur.
		$user_rank = ($row['level'] === '0') ? $lang['user.member'] : $lang['user.guest'];
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
		if (file_exists(TPL_PATH_TO_ROOT . '/templates/' . $theme . '/modules/forum/images/ranks/' . $user_rank_icon))
			$rank_img = TPL_PATH_TO_ROOT . '/templates/' . $theme . '/modules/forum/images/ranks/' . $user_rank_icon;
		else
			$rank_img = TPL_PATH_TO_ROOT . '/forum/templates/images/ranks/' . $user_rank_icon;

		$user_accounts_config = UserAccountsConfig::load();

		$user_sign_field = $extended_fields_cache->get_extended_field_by_field_name('user_sign');

		$topic_date           = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);
		$user_registered_date = new Date($row['registered'], Timezone::SERVER_TIMEZONE);

		$topic_page = 1;
		if ($row['nbr_msg'] > $config->get_number_messages_per_page())
		{
			$msg_id_result = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "forum_msg
			WHERE idtopic = :idtopic
			ORDER BY id ASC", array(
				'idtopic' => $row['idtopic']
			));

			$current_message = 0;
			while ($msg_id_row = $msg_id_result->fetch())
			{
				if ($msg_id_row['id'] <= $row['id'])
				{
					$current_message++;
					if ($current_message == $config->get_number_messages_per_page())
					{
						$topic_page++;
						$current_message = 0;
					}
				}
			}
			$msg_id_result->dispose();
		}

		$view->assign_block_vars('list', array_merge(
			Date::get_array_tpl_vars($topic_date,'TOPIC_DATE'),
			Date::get_array_tpl_vars($user_registered_date,'USER_REGISTERED_DATE'), array(
			'C_USER_RANK'      => ($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0),
			'C_USER_RANK_ICON' => !empty($user_rank_icon),
			'C_USER_AVATAR'    => $row['user_avatar'] || $user_accounts_config->is_default_avatar_enabled(),
			'C_USER_GROUPS'    => !empty($row['user_groups']),
			'C_USER_MSG'       => $row['posted_msg'] >= 1,
			'C_USER_EMAIL'     => (!empty($row['email']) && ($row['show_email'] == '1')),
			'C_USER_SIGN'      => (!empty($row['user_sign']) && !empty($user_sign_field) && $user_sign_field['display']),
			'C_USER_PM'        => !$is_guest && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
			'C_USER_ONLINE'    => !empty($row['connect']),
			'C_GUEST'          => empty($row['display_name']),
			'C_GROUP_COLOR'    => !empty($group_color),

			'CATEGORY_NAME'    => $row['name'],
			'CATEGORY_ID'      => $row['id_category'],
			'CONTENT'          => FormatingHelper::second_parse(stripslashes($row['content'])),
			'ID'               => $row['id'],
			'TITLE_T'          => stripslashes($row['title']),
			'USER_RANK'        => $user_rank,
			'USER_RANK_ICON'   => $rank_img,
			'USER_MSG'         => $row['posted_msg'],
			'USER_SIGN'        => FormatingHelper::second_parse(MemberExtendedFieldsService::unset_protection_for_serialized_string($row['user_sign'])),
			'USER_WARNING'     => $row['warning_percentage'],
			'USER_PSEUDO'      => TextHelper::utf8_wordwrap(TextHelper::html_entity_decode($row['display_name']), 13, '<br />'),
			'LEVEL_CLASS'      => UserService::get_level_class($row['level']),
			'GROUP_COLOR'      => $group_color,

			'U_USER_AVATAR'    	  => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar(),
			'U_USER_MSG'       	  => UserUrlBuilder::publications($row['user_id'])->rel(),
			'U_USER_MEMBERMSG' 	  => PATH_TO_ROOT . '/forum/membermsg' . url('.php?id=' . $row['user_id'], ''),
			'U_USER_EMAIL'     	  => 'mailto:' . $row['email'],
			'U_USER_PM'        	  => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
			'U_USER_PROFILE'   	  => UserUrlBuilder::profile($row['user_id'])->rel(),
			'U_FORUM_WARNING'     => url('.php?action=warning&amp;id=' . $row['user_id']),
			'U_FORUM_PUNISHEMENT' => url('.php?action=punish&amp;id=' . $row['user_id']),
			'U_VARS_ANCHOR'    	  => url('.php?' . ($topic_page > 1 ? 'pt=' . $topic_page . '&amp;' : '') . 'id=' . $row['idtopic'], '-' . $row['idtopic'] . ($topic_page > 1 ? '-' . $topic_page : '') . $rewrited_title . '.php'),
			'U_CATEGORY'       	  => PATH_TO_ROOT . '/forum/forum' . url('.php?id=' . $row['id_category'], '-' . $row['id_category'] . $rewrited_cat_title . '.php'),
			'U_TITLE_T'        	  => PATH_TO_ROOT . '/forum/topic' . url('.php?' . ($topic_page > 1 ? 'pt=' . $topic_page . '&amp;' : '') . 'id=' . $row['idtopic'], '-' . $row['idtopic'] . ($topic_page > 1 ? '-' . $topic_page : '') . $rewrited_title . '.php')
		)));

		//Affichage des groupes du membre.
		if (!empty($row['user_groups']))
		{
			$user_groups = '';
			$array_user_groups = explode('|', $row['user_groups']);
			foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
			{
				$group_color = User::get_group_color($idgroup);

				if (is_numeric(array_search($idgroup, $array_user_groups)))
				{
					$view->assign_block_vars('list.usergroups', array(
						'C_IMG_USERGROUP'   => !empty($array_group_info['img']),
						'C_USERGROUP_COLOR' => !empty($group_color),

						'USERGROUP_COLOR'   => $group_color,
						'USERGROUP_NAME'    => $array_group_info['name'],
						'USERGROUP_ID'      => $idgroup,

						'U_IMG_USERGROUP'   => $array_group_info['img'],
						'U_USERGROUP'       => UserUrlBuilder::group($idgroup)->rel()
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
							$button = '<a href="mailto:' . $row[$field_type] . '" class="button alt-button smaller"><i class="fa ' . $parameters['picture'] . '" aria-hidden="true"></i> ' . $parameters['title'] . '</a>';
							$title = $parameters['title'];
							$icon_fa = $parameters['picture'];
							$unknown_field = false;
						}
					}
					if ($title == '')
					{
						$title = $lang['form.email'];
						$icon_fa = 'iboot fa-iboost-email';
					}
				}
				else if ($field['regex'] == 5)
				{
					$button = '<a href="' . $row[$field_type] . '" class="button alt-button smaller offload">' . $lang['form.website'] . '</a>';

					foreach (MemberShortTextExtendedField::$brands_pictures_list as $id => $parameters)
					{
						if (TextHelper::strstr($row[$field_type], $id))
						{
							$button = '<a href="' . $row[$field_type] . '" class="button alt-button smaller offload"><i class="fa ' . $parameters['picture'] . '" aria-hidden="true"></i> ' . $parameters['title'] . '</a>';
							$title = $parameters['title'];
							$icon_fa = $parameters['picture'];
							$unknown_field = false;
						}
					}
					if ($title == '')
					{
						$title = $lang['form.website'];
						$icon_fa = 'fa-globe';
					}
				}

				$view->assign_block_vars('list.ext_fields', array(
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
	
		$user_additional_informations = HooksService::execute_hook_display_user_additional_informations_action('forum', $row);

		foreach ($user_additional_informations as $info)
		{
			$view->assign_block_vars('list.additional_informations', array(
				'VALUE' => $info
			));
		}
	}
	$result->dispose();
}
else
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" ."/forum/membermsg.php%'");

$vars_tpl = array(
	'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),

	'TOTAL_ONLINE'          => $total_online,
	'ONLINE_USERS_LIST'     => $users_list,
	'ADMINISTRATORS_NUMBER' => $total_admin,
	'MODERATORS_NUMBER'     => $total_modo,
	'MEMBERS_NUMBER'        => $total_member,
	'GUESTS_NUMBER'         => $total_visit,
	'FORUM_NAME'            => $config->get_forum_name(),

	'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
	'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
	'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators']    : $lang['user.moderator'],
	'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
	'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
);

$view->put_all($vars_tpl);
$top_view->put_all($vars_tpl);
$bottom_view->put_all($vars_tpl);

$view->put('FORUM_TOP', $top_view);
$view->put('FORUM_BOTTOM', $bottom_view);

$view->display();

require_once('../kernel/footer.php');

?>
