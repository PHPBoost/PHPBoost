<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 07
 * @since       PHPBoost 2.0 - 2008 07 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor ph-7 <me@ph7.me>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
$lang = LangLoader::get_all_langs();

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) // If user is not member (guests have nothing to do here)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$contribution_id = (int)retrieve(GET, 'id', 0);
$id_to_delete = (int)retrieve(GET, 'del', 0);
$id_to_update = (int)retrieve(POST, 'idedit', 0);
$id_update = (int)retrieve(GET, 'edit', 0);

if ($contribution_id > 0)
{
	$contribution = new Contribution();

	// Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($contribution_id)) == null || (!AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) && $contribution->get_poster_id() != AppContext::get_current_user()->get_id()))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$Bread_crumb->add($lang['user.user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($lang['contribution.panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));

	define('TITLE', $lang['contribution.panel'] . ' - ' . $contribution->get_entitled());
}

// Contribution modification
elseif ($id_update > 0)
{
	$contribution = new Contribution();

	// Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_update)) == null || !AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
    }

	$Bread_crumb->add($lang['user.user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($lang['contribution.panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));
	$Bread_crumb->add($lang['contribution.edition'], url('contribution_panel.php?edit=' . $id_update));

	define('TITLE', $lang['contribution.panel'] . ' - ' . $lang['contribution.edition']);
}
// Saving contribution modification
elseif ($id_to_update > 0)
{
	$contribution = new Contribution();

	if (($contribution = ContributionService::find_by_id($id_to_update)) == null || !AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
    }

	// Retriving parts of contribution
	$entitled = retrieve(POST, 'entitled', '', TSTRING_UNCHANGE);
	$description = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));
	$status = retrieve(POST, 'status', Event::EVENT_STATUS_UNREAD);

	// If title is not empty
	if (!empty($entitled))
	{
		// Object Contribution update
		$contribution->set_entitled($entitled);
		$contribution->set_description($description);

		// Status change ? Checking if contribution has been validated
		if ($status == Event::EVENT_STATUS_PROCESSED && $contribution->get_status() != Event::EVENT_STATUS_PROCESSED)
		{
			$contribution->set_fixer_id(AppContext::get_current_user()->get_id());
			$contribution->set_fixing_date(new Date());
		}

		$contribution->set_status($status);

		// Saving in database
		ContributionService::save_contribution($contribution);
		HooksService::execute_hook_action($status == Event::EVENT_STATUS_PROCESSED ? 'process_contribution' : 'edit_contribution', $contribution->get_module(), array_merge($contribution->get_properties(), array('title' => $contribution->get_entitled(), 'url' => $contribution->get_fixing_url())));

		AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel($contribution->get_id()));
	}

	// Error
	else
		AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel());
}

// Delete contribution
elseif ($id_to_delete > 0)
{
	// Checking if session is valid
	AppContext::get_session()->csrf_get_protect();

	$contribution = new Contribution();

	// Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_to_delete)) == null || (!AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT)))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	CommentsService::delete_comments_topic_module('user', $id_to_delete);
	ContributionService::delete_contribution($contribution);
	HooksService::execute_hook_action('delete_contribution', $contribution->get_module(), array_merge($contribution->get_properties(), array('title' => $contribution->get_entitled(), 'url' => $contribution->get_fixing_url())));

	AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel());
}
else
{
	$Bread_crumb->add($lang['user.user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($lang['contribution.panel'], url('contribution_panel.php'));
	define('TITLE', $lang['contribution.panel']);
}

require_once('../kernel/header.php');

$view = new FileTemplate('user/contribution_panel.tpl');
$view->add_lang($lang);

if ($contribution_id > 0)
{
	$view->put_all(array(
		'C_CONSULT_CONTRIBUTION' => true
	));

	$comments_topic = new UserEventsCommentsTopic();
	$comments_topic->set_id_in_module($contribution_id);
	$comments_topic->set_url(new Url('/user/contribution_panel.php?id='. $contribution_id));

	$contributor = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . DB_TABLE_MEMBER . ' member
		WHERE user_id = :user_id', array('user_id' => $contribution->get_poster_id()))->fetch();

	$contributor_group_color = User::get_group_color($contributor['user_groups'], $contributor['level']);

	$view->put_all(array_merge(
		Date::get_array_tpl_vars($contribution->get_creation_date(), 'creation_date'),
		array(
		'C_WRITE_AUTH'               => AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT),
		'C_UNPROCESSED_CONTRIBUTION' => $contribution->get_status() != Event::EVENT_STATUS_PROCESSED,
		'C_CONTRIBUTOR_GROUP_COLOR'  => !empty($contributor_group_color),

		'ENTITLED'                => $contribution->get_entitled(),
		'DESCRIPTION'             => FormatingHelper::second_parse($contribution->get_description()),
		'STATUS'                  => $contribution->get_status_name(),
		'CONTRIBUTOR'             => $contributor['display_name'],
		'CONTRIBUTOR_LEVEL_CLASS' => UserService::get_level_class($contributor['level']),
		'CONTRIBUTOR_GROUP_COLOR' => $contributor_group_color,
		'COMMENTS'                => CommentsService::display($comments_topic)->render(),
		'MODULE'                  => $contribution->get_module_name(),

		'U_CONTRIBUTOR_PROFILE' => UserUrlBuilder::profile($contribution->get_poster_id())->rel(),
		'FIXING_URL'            => Url::to_rel($contribution->get_fixing_url())
	)));

	// If contribution has been validated
	if ($contribution->get_status() == Event::EVENT_STATUS_PROCESSED)
	{
		$fixer = PersistenceContext::get_querier()->select('SELECT *
			FROM ' . DB_TABLE_MEMBER . ' member
			WHERE user_id = :user_id', array('user_id' => $contribution->get_fixer_id()))->fetch();

		$fixer_group_color = User::get_group_color($fixer['user_groups'], $fixer['level']);

		$view->put_all(array_merge(
			Date::get_array_tpl_vars($contribution->get_fixing_date(), 'fixing_date'),
			array(
			'C_CONTRIBUTION_FIXED'  => true,
			'C_REFEREE_GROUP_COLOR' => !empty($fixer_group_color),
			'FIXER'                 => $fixer['display_name'],
			'REFEREE_LEVEL_CLASS'   => UserService::get_level_class($fixer['level']),
			'REFEREE_GROUP_COLOR'   => $fixer_group_color,
			'U_REFEREE_PROFILE'     => UserUrlBuilder::profile($contribution->get_fixer_id())->rel()
		)));
	}

	$view->put_all(array(
		'U_EDIT'   => url('contribution_panel.php?edit=' . $contribution_id),
		'U_DELETE' => url('contribution_panel.php?del=' . $contribution_id . '&amp;token=' . AppContext::get_session()->get_token())
	));
}

// Contribution modification
elseif ($id_update > 0)
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$view->put_all(array(
		'C_EDIT_CONTRIBUTION' => true,

		'KERNEL_EDITOR'                         => $editor->display(),
		'ENTITLED'                              => $contribution->get_entitled(),
		'DESCRIPTION'                           => FormatingHelper::unparse($contribution->get_description()),
		'CONTRIBUTION_ID'                       => $contribution->get_id(),
		'EVENT_STATUS_UNREAD_SELECTED'          => $contribution->get_status() == Event::EVENT_STATUS_UNREAD ? ' selected="selected"' : '',
		'EVENT_STATUS_BEING_PROCESSED_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED ? ' selected="selected"' : '',
		'EVENT_STATUS_PROCESSED_SELECTED'       => $contribution->get_status() == Event::EVENT_STATUS_PROCESSED ? ' selected="selected"' : '',
	));
}
else
{
	$view->put_all(array(
		'C_CONTRIBUTION_LIST' => true
	));

	// Contributions number
	$num_contributions = 1;
	define('CONTRIBUTIONS_PER_PAGE', 20);

	$page = AppContext::get_request()->get_getint('p', 1);

	// Filters management
	$criteria = retrieve(GET, 'criteria', 'current_status');
	$order = retrieve(GET, 'order', 'asc');

	if (!in_array($criteria, array('entitled', 'module', 'status', 'creation_date', 'fixing_date', 'poster_id', 'fixer_id')))
		$criteria = 'current_status';
	$order = $order == 'desc' ? 'desc' : 'asc';

	// Listing contributions
	foreach (ContributionService::get_all_contributions($criteria, $order) as $contribution)
	{
		// Display of contribution member
		if (AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) || AppContext::get_current_user()->get_id() == $contribution->get_poster_id())
		{
			// Display conditions
			if ($num_contributions > CONTRIBUTIONS_PER_PAGE * ($page - 1) && $num_contributions <= CONTRIBUTIONS_PER_PAGE * $page)
			{
				$poster_group_color = User::get_group_color($contribution->get_poster_groups(), $contribution->get_poster_level());
				$fixer_group_color = User::get_group_color($contribution->get_fixer_groups(), $contribution->get_fixer_level());

				$view->assign_block_vars('contributions', array_merge(
					Date::get_array_tpl_vars($contribution->get_creation_date(), 'creation_date'),
					Date::get_array_tpl_vars($contribution->get_fixing_date(), 'fixing_date'),
					array(
					'C_AUTHOR_GROUP_COLOR'  => !empty($poster_group_color),
					'C_REFEREE_GROUP_COLOR' => !empty($fixer_group_color),

					'ENTITLED'            => $contribution->get_entitled(),
					'MODULE'              => $contribution->get_module_name(),
					'STATUS'              => $contribution->get_status_name(),
					'POSTER'              => $contribution->get_poster_login(),
					'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($contribution->get_poster_level()),
					'AUTHOR_GROUP_COLOR'  => $poster_group_color,
					'FIXER'               => $contribution->get_fixer_login(),
					'REFEREE_LEVEL_CLASS' => UserService::get_level_class($contribution->get_fixer_level()),
					'REFEREE_GROUP_COLOR' => $fixer_group_color,
					'ACTIONS'             => '',

					'U_REFEREE_PROFILE' => UserUrlBuilder::profile($contribution->get_fixer_id())->rel(),
					'U_AUTHOR_PROFILE'  => UserUrlBuilder::profile($contribution->get_poster_id())->rel(),
					'U_CONSULT'         => PATH_TO_ROOT . '/user/' . url('contribution_panel.php?id=' . $contribution->get_id()),
					'C_FIXED'           => $contribution->get_status() == Event::EVENT_STATUS_PROCESSED,
					'C_PROCESSING'      => $contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED
				)));
			}

			$num_contributions++;
		}
	}

	$pagination = new ModulePagination($page, $num_contributions, CONTRIBUTIONS_PER_PAGE);
	$pagination->set_url(new Url('/user/contribution_panel.php?p=%d&criteria=' . $criteria . '&order=' . $order));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if ($num_contributions > 1)
		$view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION'   => $pagination->display()
		));
	else
		$view->put_all(array(
			'C_NO_CONTRIBUTION' => true
		));

	// List of modules with contribution
	$i_module = 0;
	foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $name => $module)
	{
		$contribution_interface = $module->get_configuration()->get_contribution_interface();

		$authorized = true;
		$authorizations_class = TextHelper::ucfirst($module->get_id()) . 'AuthorizationsService';
		if (ClassLoader::is_class_registered_and_valid($authorizations_class) && method_exists($authorizations_class, 'check_authorizations') && method_exists($authorizations_class, 'contribution') && ($module->get_configuration()->has_categories() ? !$authorizations_class::check_authorizations(Category::ROOT_CATEGORY, $module->get_id())->contribution() : !$authorizations_class::check_authorizations($module->get_id())->contribution()))
			$authorized = false;

		if (!empty($contribution_interface) && $authorized)
		{
			$configuration = $module->get_configuration();
			$img_url = PATH_TO_ROOT . '/' . $module->get_id() . '/' . $module->get_id() . '.png';
			$img = new File($img_url);
			$thumbnail = $img->exists() ? $img_url : '';
			$fa_icon = $configuration->get_fa_icon();
			$hexa_icon = $configuration->get_hexa_icon();
			$view->assign_block_vars('module', array(
				'C_IMG'       => $img->exists(),
				'C_FA_ICON'   => !empty($fa_icon),
				'C_HEXA_ICON' => !empty($hexa_icon),

				'U_IMG'     => $img_url,
				'FA_ICON'   => $fa_icon,
				'HEXA_ICON' => $hexa_icon,

				'U_MODULE_LINK' => $contribution_interface,
				'MODULE_ID'     => $module->get_id(),
				'MODULE_NAME'   => $module->get_configuration()->get_name(),
				'LINK_TITLE'    => sprintf($lang['contribution.contribute.in.module.name'], $module->get_configuration()->get_name())
			));
			$i_module++;
		}
	}

	$view->put_all(array(
		'C_CONTRIBUTION_MODULE' => $i_module > 0
	));

	// Sorting management
	$view->put_all(array(
		'C_ORDER_ENTITLED_ASC'       => $criteria== 'entitled' && $order == 'asc',
		'U_ORDER_ENTITLED_ASC'       => url('contribution_panel.php?p=' . $page . '&amp;criteria=entitled&amp;order=asc'),
		'C_ORDER_ENTITLED_DESC'      => $criteria== 'entitled' && $order == 'desc',
		'U_ORDER_ENTITLED_DESC'      => url('contribution_panel.php?p=' . $page . '&amp;criteria=entitled&amp;order=desc'),
		'C_ORDER_MODULE_ASC'         => $criteria== 'module' && $order == 'asc',
		'U_ORDER_MODULE_ASC'         => url('contribution_panel.php?p=' . $page . '&amp;criteria=module&amp;order=asc'),
		'C_ORDER_MODULE_DESC'        => $criteria== 'module' && $order == 'desc',
		'U_ORDER_MODULE_DESC'        => url('contribution_panel.php?p=' . $page . '&amp;criteria=module&amp;order=desc'),
		'C_ORDER_STATUS_ASC'         => $criteria== 'current_status' && $order == 'asc',
		'U_ORDER_STATUS_ASC'         => url('contribution_panel.php?p=' . $page . '&amp;criteria=current_status&amp;order=asc'),
		'C_ORDER_STATUS_DESC'        => $criteria== 'current_status' && $order == 'desc',
		'U_ORDER_STATUS_DESC'        => url('contribution_panel.php?p=' . $page . '&amp;criteria=current_status&amp;order=desc'),
		'C_ORDER_CREATION_DATE_ASC'  => $criteria== 'creation_date' && $order == 'asc',
		'U_ORDER_CREATION_DATE_ASC'  => url('contribution_panel.php?p=' . $page . '&amp;criteria=creation_date&amp;order=asc'),
		'C_ORDER_CREATION_DATE_DESC' => $criteria== 'creation_date' && $order == 'desc',
		'U_ORDER_CREATION_DATE_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=creation_date&amp;order=desc'),
		'C_ORDER_FIXING_DATE_ASC'    => $criteria== 'fixing_date' && $order == 'asc',
		'U_ORDER_FIXING_DATE_ASC'    => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixing_date&amp;order=asc'),
		'C_ORDER_FIXING_DATE_DESC'   => $criteria== 'fixing_date' && $order == 'desc',
		'U_ORDER_FIXING_DATE_DESC'   => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixing_date&amp;order=desc'),
		'C_ORDER_AUTHOR_ASC'         => $criteria== 'poster_id' && $order == 'asc',
		'U_ORDER_AUTHOR_ASC'         => url('contribution_panel.php?p=' . $page . '&amp;criteria=poster_id&amp;order=asc'),
		'C_ORDER_AUTHOR_DESC'        => $criteria== 'poster_id' && $order == 'desc',
		'U_ORDER_AUTHOR_DESC'        => url('contribution_panel.php?p=' . $page . '&amp;criteria=poster_id&amp;order=desc'),
		'C_ORDER_REFEREE_ASC'        => $criteria== 'fixer_id' && $order == 'asc',
		'U_ORDER_REFEREE_ASC'        => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixer_id&amp;order=asc'),
		'C_ORDER_REFEREE_DESC'       => $criteria== 'fixer_id' && $order == 'desc',
		'U_ORDER_REFEREE_DESC'       => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixer_id&amp;order=desc')
	));
}

$view->display();

require_once('../kernel/footer.php');
?>
