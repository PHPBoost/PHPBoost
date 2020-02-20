<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 20
 * @since       PHPBoost 1.6 - 2007 04 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
define('TITLE', $LANG['title_forum']);
define('DESCRIPTION', $LANG['member_msg_seo']);
require_once('../kernel/header.php');
$request = AppContext::get_request();

$view_msg = $request->get_getint('id', 0);
if (!empty($view_msg)) // Display all user's messages
{
	$_NBR_ELEMENTS_PER_PAGE = 10;
	$ranks_cache = ForumRanksCache::load()->get_ranks(); // Loads all ranks from cache
	$extended_fields_cache = ExtendedFieldsCache::load();
	$displayed_extended_fields = $extended_fields_cache->get_websites_or_emails_extended_field_field_types();
	$extended_fields_to_recover_list = '';
	foreach ($displayed_extended_fields as $field_type)
	{
		$extended_fields_to_recover_list .= 'ext_field.' . $field_type . ', ';
	}

	$tpl = new FileTemplate('forum/forum_membermsg.tpl');

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
				'user_id' => $view_msg
			));
			$nbr_msg = $row['nbr_msg'];
		} catch (RowNotFoundException $e) {}

		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_msg, $_NBR_ELEMENTS_PER_PAGE, Pagination::LIGHT_PAGINATION);
		$pagination->set_url(new Url('/forum/membermsg.php?id=' . $view_msg . '&amp;p=%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		$tpl->put_all(array(
			'C_PAGINATION'     => $pagination->has_several_pages(),
			'FORUM_NAME'       => $config->get_forum_name(),
			'PAGINATION'       => $pagination->display(),
			'L_BACK'           => $LANG['back'],
			'L_VIEW_MSG_USER'  => $LANG['show_member_msg'],
			'L_FORUM_INDEX'    => $LANG['forum_index'],
			'U_FORUM_VIEW_MSG' => url('.php?id=' . $view_msg)
		));

		$result = PersistenceContext::get_querier()->select("SELECT
			msg.id, msg.user_id, msg.idtopic, msg.timestamp, msg.timestamp_edit, msg.user_id_edit, msg.contents,
			m2.display_name AS login_edit,
			m.groups, m.display_name, m.level, m.email, m.show_email, m.registration_date AS registered, m.posted_msg, m.warning_percentage, m.delay_banned,
			t.title, t.status, t.id_category,
			c.name,
			s.user_id AS connect,
			ext_field.user_avatar, m.posted_msg, ext_field.user_sign,
			" . $extended_fields_to_recover_list . "m.warning_percentage, m.delay_readonly, m.delay_banned
		FROM " . PREFIX . "forum_msg msg
		LEFT JOIN " . PREFIX . "forum_topics t ON msg.idtopic = t.id
		LEFT JOIN " . ForumSetup::$forum_cats_table . " c ON c.id = t.id_category
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = :user_id
		LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = msg.user_id_edit
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > :timestamp
		WHERE msg.user_id = :id AND t.id_category IN :authorized_categories
		ORDER BY msg.id DESC
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'id' => $view_msg,
			'user_id' => $view_msg,
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
			$edit_mark = ($row['timestamp_edit'] > 0 && $config->is_edit_mark_enabled()) ? '<span class="edit-pseudo">' . $LANG['edit_by'] . ' <a href="'. UserUrlBuilder::profile($row['user_id_edit'])->rel() .'">' . $row['login_edit'] . '</a> ' . $LANG['on'] . ' ' . Date::to_format($row['timestamp_edit'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '</span><br />' : '';

			$group_color = User::get_group_color($row['groups'], $row['level']);

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
			if (file_exists(TPL_PATH_TO_ROOT . '/templates/' . $theme . '/modules/forum/images/ranks/' . $user_rank_icon))
				$rank_img = TPL_PATH_TO_ROOT . '/templates/' . $theme . '/modules/forum/images/ranks/' . $user_rank_icon;
			else
				$rank_img = TPL_PATH_TO_ROOT . '/forum/templates/images/ranks/' . $user_rank_icon;

			$user_accounts_config = UserAccountsConfig::load();

			$user_sign_field = $extended_fields_cache->get_extended_field_by_field_name('user_sign');

			$topic_date           = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);
			$user_registered_date = new Date($row['registered'], Timezone::SERVER_TIMEZONE);

			$tpl->assign_block_vars('list', array_merge(
				Date::get_array_tpl_vars($topic_date,'TOPIC_DATE'),
				Date::get_array_tpl_vars($user_registered_date,'USER_REGISTERED_DATE'), array(

				'CONTENTS'         => FormatingHelper::second_parse(stripslashes($row['contents'])),
				'ID'               => $row['id'],
				'C_USER_RANK'      => ($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0),
				'USER_RANK'        => $user_rank,
				'C_USER_IMG_ASSOC' => !empty($user_rank_icon),
				'USER_IMG_ASSOC'   => $rank_img,
				'C_USER_AVATAR'    => !empty($row['user_avatar']),
				'U_USER_AVATAR'    => Url::to_rel($row['user_avatar']),
				'U_DEFAULT_AVATAR' => '../templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name(),
				'C_USER_GROUPS'    => !empty($row['groups']),
				'C_IS_USER'        => !$is_guest,
				'C_USER_MSG'       => $row['posted_msg'] >= 1,
				'U_USER_MSG'       => UserUrlBuilder::messages($row['user_id'])->rel(),
				'U_USER_MEMBERMSG' => PATH_TO_ROOT . '/forum/membermsg' . url('.php?id=' . $row['user_id'], ''),
				'USER_MSG'         => $row['posted_msg'],
				'C_USER_MAIL'      => (!empty($row['email']) && ($row['show_email'] == '1')),
				'U_USER_MAIL'      => 'mailto:' . $row['email'],
				'C_USER_SIGN'      => (!empty($row['user_sign']) && !empty($user_sign_field) && $user_sign_field['display']),
				'USER_SIGN'        => FormatingHelper::second_parse($row['user_sign']),
				'USER_WARNING'     => $row['warning_percentage'],
				'C_USER_PM'        => !$is_guest && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
				'U_USER_PM'        => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
				'C_USER_ONLINE'    => !empty($row['connect']),
				'C_GUEST'          => empty($row['display_name']),
				'USER_PSEUDO'      => TextHelper::utf8_wordwrap(TextHelper::html_entity_decode($row['display_name']), 13, '<br />'),
				'LEVEL_CLASS'      => UserService::get_level_class($row['level']),
				'C_GROUP_COLOR'    => !empty($group_color),
				'GROUP_COLOR'      => $group_color,
				'CATEGORY_ID'	   => $row['id_category'],
				'U_USER_PROFILE'   => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_VARS_ANCRE'     => url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php'),
				'U_FORUM_CAT'      => PATH_TO_ROOT . '/forum/forum' . url('.php?id=' . $row['id_category'], '-' . $row['id_category'] . $rewrited_cat_title . '.php'),
				'FORUM_CAT'        => $row['name'],
				'U_TITLE_T'        => PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . $rewrited_title . '.php'),
				'TITLE_T'          => stripslashes($row['title'])
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
						$tpl->assign_block_vars('list.usergroups', array(
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
								$button = '<a href="mailto:' . $row[$field_type] . '" class="button alt-button smaller"><i class="fa ' . $parameters['picture'] . '" aria-hidden="true"></i> ' . $parameters['title'] . '</a>';
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
						$button = '<a href="' . $row[$field_type] . '" class="button alt-button smaller">' . LangLoader::get_message('regex.website', 'admin-user-common') . '</a>';

						foreach (MemberShortTextExtendedField::$brands_pictures_list as $id => $parameters)
						{
							if (TextHelper::strstr($row[$field_type], $id))
							{
								$button = '<a href="' . $row[$field_type] . '" class="button alt-button smaller"><i class="fa ' . $parameters['picture'] . '" aria-hidden="true"></i> ' . $parameters['title'] . '</a>';
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

					$tpl->assign_block_vars('list.ext_fields', array(
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
		'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
		'TOTAL_ONLINE'     => $total_online,
		'C_NO_USER_ONLINE' => (($total_online - $total_visit) == 0),
		'USERS_ONLINE'     => $users_list,
		'ADMIN'            => $total_admin,
		'MODO'             => $total_modo,
		'MEMBER'           => $total_member,
		'GUEST'            => $total_visit,
		'FORUM_NAME'       => $config->get_forum_name(),
		'L_FORUM_INDEX'    => $LANG['forum_index'],
		'L_USER'           => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
		'L_ADMIN'          => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
		'L_MODO'           => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
		'L_MEMBER'         => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
		'L_GUEST'          => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
		'L_AND'            => $LANG['and'],
		'L_ONLINE'         => TextHelper::strtolower($LANG['online'])
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

require_once('../kernel/footer.php');

?>
