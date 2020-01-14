<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 16
 * @since       PHPBoost 2.0 - 2008 07 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor ph-7 <me@ph7.me>
*/

require_once('../kernel/begin.php');

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Si il n'est pas member (les invités n'ont rien à faire ici)
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

	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($contribution_id)) == null || (!AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) && $contribution->get_poster_id() != AppContext::get_current_user()->get_id()))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$Bread_crumb->add($LANG['user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));

	define('TITLE', $LANG['contribution_panel'] . ' - ' . $contribution->get_entitled());
}
//Modification d'une contribution
elseif ($id_update > 0)
{
	$contribution = new Contribution();

	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_update)) == null || !AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
    	$error_controller = PHPBoostErrors::unexisting_page();
	   DispatchManager::redirect($error_controller);
    }

	$Bread_crumb->add($LANG['user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));
	$Bread_crumb->add($LANG['contribution_edition'], url('contribution_panel.php?edit=' . $id_update));

	define('TITLE', $LANG['contribution_panel'] . ' - ' . $LANG['contribution_edition']);
}
//Enregistrement de la modification d'une contribution
elseif ($id_to_update > 0)
{
	$contribution = new Contribution();

	if (($contribution = ContributionService::find_by_id($id_to_update)) == null || !AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
	   $error_controller = PHPBoostErrors::unexisting_page();
	   DispatchManager::redirect($error_controller);
    }

	//Récupération des éléments de la contribution
	$entitled = retrieve(POST, 'entitled', '', TSTRING_UNCHANGE);
	$description = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));
	$status = retrieve(POST, 'status', Event::EVENT_STATUS_UNREAD);

	//Si le titre n'est pas vide
	if (!empty($entitled))
	{
		//Mise à jour de l'objet contribution
		$contribution->set_entitled($entitled);
		$contribution->set_description($description);

		//Changement de statut ? On regarde si la contribution a été réglée
		if ($status == Event::EVENT_STATUS_PROCESSED && $contribution->get_status() != Event::EVENT_STATUS_PROCESSED)
		{
			$contribution->set_fixer_id(AppContext::get_current_user()->get_id());
			$contribution->set_fixing_date(new Date());
		}

		$contribution->set_status($status);

		//Enregistrement en base de données
		ContributionService::save_contribution($contribution);

		AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel($contribution->get_id()));
	}
	//Erreur
	else
		AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel());
}
//Suppression d'une contribution
elseif ($id_to_delete > 0)
{
	//Vérification de la validité du jeton
	AppContext::get_session()->csrf_get_protect();

	$contribution = new Contribution();

	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_to_delete)) == null || (!AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT)))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	CommentsService::delete_comments_topic_module('user', $id_to_delete);
	ContributionService::delete_contribution($contribution);

	AppContext::get_response()->redirect(UserUrlBuilder::contribution_panel());
}
else
{
	$Bread_crumb->add($LANG['user'], UserUrlBuilder::home()->rel());
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	define('TITLE', $LANG['contribution_panel']);
}

require_once('../kernel/header.php');

$template = new FileTemplate('user/contribution_panel.tpl');

if ($contribution_id > 0)
{
	$template->put_all(array(
		'C_CONSULT_CONTRIBUTION' => true
	));

	$comments_topic = new UserEventsCommentsTopic();
	$comments_topic->set_id_in_module($contribution_id);
	$comments_topic->set_url(new Url('/user/contribution_panel.php?id='. $contribution_id));

	$contributor = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . DB_TABLE_MEMBER . ' member
		WHERE user_id = :user_id', array('user_id' => $contribution->get_poster_id()))->fetch();

	$contributor_group_color = User::get_group_color($contributor['groups'], $contributor['level']);

	$template->put_all(array(
		'C_WRITE_AUTH' => AppContext::get_current_user()->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT),
		'C_UNPROCESSED_CONTRIBUTION' => $contribution->get_status() != Event::EVENT_STATUS_PROCESSED,
		'C_CONTRIBUTOR_GROUP_COLOR' => !empty($contributor_group_color),
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => FormatingHelper::second_parse($contribution->get_description()),
		'STATUS' => $contribution->get_status_name(),
		'CONTRIBUTOR' => $contributor['display_name'],
		'CONTRIBUTOR_LEVEL_CLASS' => UserService::get_level_class($contributor['level']),
		'CONTRIBUTOR_GROUP_COLOR' => $contributor_group_color,
		'COMMENTS' => CommentsService::display($comments_topic)->render(),
		'CREATION_DATE' => $contribution->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
		'MODULE' => $contribution->get_module_name(),
		'U_CONTRIBUTOR_PROFILE' => UserUrlBuilder::profile($contribution->get_poster_id())->rel(),
		'FIXING_URL' => Url::to_rel($contribution->get_fixing_url())
	));

	//Si la contribution a été traitée
	if ($contribution->get_status() == Event::EVENT_STATUS_PROCESSED)
	{
		$fixer = PersistenceContext::get_querier()->select('SELECT *
			FROM ' . DB_TABLE_MEMBER . ' member
			WHERE user_id = :user_id', array('user_id' => $contribution->get_fixer_id()))->fetch();

		$fixer_group_color = User::get_group_color($fixer['groups'], $fixer['level']);

		$template->put_all(array(
			'C_CONTRIBUTION_FIXED' => true,
			'C_FIXER_GROUP_COLOR' => !empty($fixer_group_color),
			'FIXER' => $fixer['display_name'],
			'FIXER_LEVEL_CLASS' => UserService::get_level_class($fixer['level']),
			'FIXER_GROUP_COLOR' => $fixer_group_color,
			'FIXING_DATE' => $contribution->get_fixing_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
			'U_FIXER_PROFILE' => UserUrlBuilder::profile($contribution->get_fixer_id())->rel()
		));
	}

	$template->put_all(array(
		'L_CONTRIBUTION' => $LANG['contribution'],
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_DESCRIPTION' => $LANG['contribution_description'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_CONTRIBUTOR' => $LANG['contributor'],
		'L_CREATION_DATE' => $LANG['contribution_creation_date'],
		'L_FIXER' => $LANG['contribution_fixer'],
		'L_FIXING_DATE' => $LANG['contribution_fixing_date'],
		'L_MODULE' => $LANG['contribution_module'],
		'L_PROCESS_CONTRIBUTION' => $LANG['process_contribution'],
		'L_CONFIRM_DELETE_CONTRIBUTION' => $LANG['confirm_delete_contribution'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_UPDATE' => $LANG['update'],
		'U_UPDATE' => url('contribution_panel.php?edit=' . $contribution_id),
		'U_DELETE' => url('contribution_panel.php?del=' . $contribution_id . '&amp;token=' . AppContext::get_session()->get_token())
	));
}
//Modification d'une contribution
elseif ($id_update > 0)
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$template->put_all(array(
		'C_EDIT_CONTRIBUTION' => true,
		'EDITOR' => $editor->display(),
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => FormatingHelper::unparse($contribution->get_description()),
		'CONTRIBUTION_ID' => $contribution->get_id(),
		'EVENT_STATUS_UNREAD_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_UNREAD ? ' selected="selected"' : '',
		'EVENT_STATUS_BEING_PROCESSED_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED ? ' selected="selected"' : '',
		'EVENT_STATUS_PROCESSED_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_PROCESSED ? ' selected="selected"' : '',
		'L_CONTRIBUTION_STATUS_UNREAD' => $LANG['contribution_status_unread'],
		'L_CONTRIBUTION_STATUS_BEING_PROCESSED' => $LANG['contribution_status_being_processed'],
		'L_CONTRIBUTION_STATUS_PROCESSED' => $LANG['contribution_status_processed'],
		'L_CONTRIBUTION' => $LANG['contribution'],
		'L_DESCRIPTION' => $LANG['contribution_description'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));
}
else
{
	$template->put_all(array(
		'C_CONTRIBUTION_LIST' => true
	));

	//Nombre de contributions
	$num_contributions = 1;
	define('CONTRIBUTIONS_PER_PAGE', 20);

	$page = AppContext::get_request()->get_getint('p', 1);

	//Gestion des critères de tri
	$criteria = retrieve(GET, 'criteria', 'current_status');
	$order = retrieve(GET, 'order', 'asc');

	if (!in_array($criteria, array('entitled', 'module', 'status', 'creation_date', 'fixing_date', 'poster_id', 'fixer_id')))
		$criteria = 'current_status';
	$order = $order == 'desc' ? 'desc' : 'asc';

	//On liste les contributions
	foreach (ContributionService::get_all_contributions($criteria, $order) as $this_contribution)
	{
		//Obligé de faire une variable temp à cause de php4.
		$creation_date = $this_contribution->get_creation_date();
		$fixing_date = $this_contribution->get_fixing_date();

		//Affichage des contributions du membre
		if (AppContext::get_current_user()->check_auth($this_contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) || AppContext::get_current_user()->get_id() == $this_contribution->get_poster_id())
		{
			//On affiche seulement si on est dans le bon cadre d'affichage
			if ($num_contributions > CONTRIBUTIONS_PER_PAGE * ($page - 1) && $num_contributions <= CONTRIBUTIONS_PER_PAGE * $page)
			{
				$poster_group_color = User::get_group_color($this_contribution->get_poster_groups(), $this_contribution->get_poster_level());
				$fixer_group_color = User::get_group_color($this_contribution->get_fixer_groups(), $this_contribution->get_fixer_level());

				$template->assign_block_vars('contributions', array(
					'C_POSTER_GROUP_COLOR' => !empty($poster_group_color),
					'C_FIXER_GROUP_COLOR' => !empty($fixer_group_color),
					'ENTITLED' => $this_contribution->get_entitled(),
					'MODULE' => $this_contribution->get_module_name(),
					'STATUS' => $this_contribution->get_status_name(),
					'CREATION_DATE' => $creation_date->format(Date::FORMAT_DAY_MONTH_YEAR),
					'FIXING_DATE' => $fixing_date->format(Date::FORMAT_DAY_MONTH_YEAR),
					'POSTER' => $this_contribution->get_poster_login(),
					'POSTER_LEVEL_CLASS' => UserService::get_level_class($this_contribution->get_poster_level()),
					'POSTER_GROUP_COLOR' => $poster_group_color,
					'FIXER' => $this_contribution->get_fixer_login(),
					'FIXER_LEVEL_CLASS' => UserService::get_level_class($this_contribution->get_fixer_level()),
					'FIXER_GROUP_COLOR' => $fixer_group_color,
					'ACTIONS' => '',
					'U_FIXER_PROFILE' => UserUrlBuilder::profile($this_contribution->get_fixer_id())->rel(),
					'U_POSTER_PROFILE' => UserUrlBuilder::profile($this_contribution->get_poster_id())->rel(),
					'U_CONSULT' => PATH_TO_ROOT . '/user/' . url('contribution_panel.php?id=' . $this_contribution->get_id()),
					'C_FIXED' => $this_contribution->get_status() == Event::EVENT_STATUS_PROCESSED,
					'C_PROCESSING' => $this_contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED
				));
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
		$template->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));
	else
		$template->put_all(array(
			'C_NO_CONTRIBUTION' => true,
			'L_NO_CONTRIBUTION_TO_DISPLAY' => LangLoader::get_message('no_item_now', 'common')
		));

	//Liste des modules proposant de contribuer
	define('NUMBER_OF_MODULES_PER_LINE', 4);
	$i_module = 0;
    $modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();
	foreach ($modules as $name => $module)
	{
		$contribution_interface = $module->get_configuration()->get_contribution_interface();

		$authorized = true;
		$authorizations_class = TextHelper::ucfirst($module->get_id()) . 'AuthorizationsService';
		if (class_exists($authorizations_class) && method_exists($authorizations_class, 'check_authorizations') && method_exists($authorizations_class, 'contribution') && !$authorizations_class::check_authorizations()->contribution())
			$authorized = false;

		if (!empty($contribution_interface) && $authorized)
		{
			if ($i_module % NUMBER_OF_MODULES_PER_LINE == 0)
			{
				$template->assign_block_vars('row', array(
					'MODULES_PER_ROW' => NUMBER_OF_MODULES_PER_LINE,
				));
			}

			$template->assign_block_vars('row.module', array(
				'U_MODULE_LINK' => PATH_TO_ROOT . '/' . $module->get_id() . '/' . url($contribution_interface),
				'MODULE_ID' => $module->get_id(),
				'MODULE_NAME' => $module->get_configuration()->get_name(),
				'LINK_TITLE' => sprintf($LANG['contribute_in_module_name'], $module->get_configuration()->get_name())
			));
			$i_module++;
		}
	}

	$template->put_all(array(
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_POSTER' => $LANG['contributor'],
		'L_CREATION_DATE' => $LANG['contribution_creation_date'],
		'L_FIXER' => $LANG['contribution_fixer'],
		'L_FIXING_DATE' => $LANG['contribution_fixing_date'],
		'L_MODULE' => $LANG['contribution_module'],
		'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel'],
		'L_CONTRIBUTION_LIST' => $LANG['contribution_list'],
		'L_CONTRIBUTE' => $LANG['contribute'],
		'L_CONTRIBUTE_EXPLAIN' => $LANG['contribute_in_modules_explain'],
		'L_NO_MODULE_IN_WHICH_CONTRIBUTE' => $LANG['no_module_to_contribute'],
		'C_NO_MODULE_IN_WHICH_CONTRIBUTE' => $i_module == 0
	));

	//Gestion du tri
	$template->put_all(array(
		'C_ORDER_ENTITLED_ASC' => $criteria == 'entitled' && $order == 'asc',
		'U_ORDER_ENTITLED_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=entitled&amp;order=asc'),
		'C_ORDER_ENTITLED_DESC' => $criteria == 'entitled' && $order == 'desc',
		'U_ORDER_ENTITLED_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=entitled&amp;order=desc'),
		'C_ORDER_MODULE_ASC' => $criteria == 'module' && $order == 'asc',
		'U_ORDER_MODULE_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=module&amp;order=asc'),
		'C_ORDER_MODULE_DESC' => $criteria == 'module' && $order == 'desc',
		'U_ORDER_MODULE_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=module&amp;order=desc'),
		'C_ORDER_STATUS_ASC' => $criteria == 'current_status' && $order == 'asc',
		'U_ORDER_STATUS_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=current_status&amp;order=asc'),
		'C_ORDER_STATUS_DESC' => $criteria == 'current_status' && $order == 'desc',
		'U_ORDER_STATUS_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=current_status&amp;order=desc'),
		'C_ORDER_CREATION_DATE_ASC' => $criteria == 'creation_date' && $order == 'asc',
		'U_ORDER_CREATION_DATE_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=creation_date&amp;order=asc'),
		'C_ORDER_CREATION_DATE_DESC' => $criteria == 'creation_date' && $order == 'desc',
		'U_ORDER_CREATION_DATE_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=creation_date&amp;order=desc'),
		'C_ORDER_FIXING_DATE_ASC' => $criteria == 'fixing_date' && $order == 'asc',
		'U_ORDER_FIXING_DATE_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixing_date&amp;order=asc'),
		'C_ORDER_FIXING_DATE_DESC' => $criteria == 'fixing_date' && $order == 'desc',
		'U_ORDER_FIXING_DATE_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixing_date&amp;order=desc'),
		'C_ORDER_POSTER_ASC' => $criteria == 'poster_id' && $order == 'asc',
		'U_ORDER_POSTER_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=poster_id&amp;order=asc'),
		'C_ORDER_POSTER_DESC' => $criteria == 'poster_id' && $order == 'desc',
		'U_ORDER_POSTER_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=poster_id&amp;order=desc'),
		'C_ORDER_FIXER_ASC' => $criteria == 'fixer_id' && $order == 'asc',
		'U_ORDER_FIXER_ASC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixer_id&amp;order=asc'),
		'C_ORDER_FIXER_DESC' => $criteria == 'fixer_id' && $order == 'desc',
		'U_ORDER_FIXER_DESC' => url('contribution_panel.php?p=' . $page . '&amp;criteria=fixer_id&amp;order=desc')
	));
}

$template->display();

require_once('../kernel/footer.php');
?>
