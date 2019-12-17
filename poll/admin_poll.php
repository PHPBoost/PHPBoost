<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 06 22
 * @since       PHPBoost 1.2 - 2005 06 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

//On recupère les variables.
$id      = $request->get_getint('id', 0);
$id_post = $request->get_postint('id', 0);
$del     = $request->get_getint('delete', 0);
$valid   = $request->get_postvalue('valid', false);

$poll_config = PollConfig::load();

//Liste des sondages affichés dans le mini module
$config_displayed_in_mini_module_list = $poll_config->get_displayed_in_mini_module_list();

if ($del && !empty($id)) //Suppresion poll
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	//On supprime des tables config et reponses des polls.
	PersistenceContext::get_querier()->delete(PREFIX . 'poll', 'WHERE id=:id', array('id' => $id));

	###### Régénération du cache si le sondage fait parti de la liste des sondages affichés dans le mini-module #######
	if (in_array($id, $config_displayed_in_mini_module_list))
	{
		$displayed_in_mini_module_list = $config_displayed_in_mini_module_list;
		unset($displayed_in_mini_module_list[array_search($id, $displayed_in_mini_module_list)]);

		$poll_config->set_displayed_in_mini_module_list($displayed_in_mini_module_list);

		PollConfig::save();

		PollMiniMenuCache::invalidate();
	}
	AppContext::get_response()->redirect('/poll/admin_poll.php');
}
elseif ($valid && !empty($id_post)) //inject
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$question     = retrieve(POST, 'question', '');
	$type         = (int)retrieve(POST, 'type', 0);
	$archive      = (int)retrieve(POST, 'archive', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start        = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end          = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour         = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min          = retrieve(POST, 'min', '', TSTRING_UNCHANGE);
	$get_visible  = (int)retrieve(POST, 'visible', 0);

	//On verifie les conditions!
	if (!empty($question) && !empty($id_post))
	{
		$start_date = new Date($start);
		$end_date = new Date($end);

		$start_timestamp = $start_date->get_timestamp();
		$end_timestamp = $end_date->get_timestamp();

		$visible = 1;
		if ($get_visible == 2)
		{
			if ($start_timestamp > time())
				$visible = 2;
			elseif ($start_timestamp == 0)
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if ($end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0)
				$visible = 2;
			elseif ($start_timestamp != 0) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
		{
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}

		$date = new Date($current_date);
		$timestamp = $date->get_timestamp();
		if ($timestamp > 0)
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
		else
			$timestamp = time();

		$answers = '';
		$votes = '';
		for ($i = 0; $i < 20; $i++)
		{
			if ($request->has_postparameter('a'.$i))
			{
				if ($request->get_postvalue('a'.$i, ''))
				{
					$answers .= str_replace('|', '', $request->get_postvalue('a'.$i, '')) . '|';
					$votes .= str_replace('|', '', $request->get_postvalue('v'.$i, 0)) . '|';
				}
			}
		}
		$votes = trim($votes, '|');

		PersistenceContext::get_querier()->update(PREFIX . "poll", array('question' => $question, 'answers' => TextHelper::substr($answers, 0, TextHelper::strlen($answers) - 1), 'votes' => $votes, 'type' => $type, 'archive' => $archive, 'visible' => $visible, 'start' => $start_timestamp, 'end' => $start_timestamp, 'timestamp' => $timestamp), 'WHERE id = :id', array('id' => $id_post));

		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	}
	else
		AppContext::get_response()->redirect('/poll/admin_poll.php?id= ' . $id_post . '&error=incomplete#message_helper');
}
elseif (!empty($id))
{
	$tpl = new FileTemplate('poll/admin_poll_management2.tpl');

	try {
		$row = PersistenceContext::get_querier()->select_single_row(PREFIX . 'poll', array('*'), 'WHERE id=:id', array('id' => $id));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$calendar_start = new MiniCalendar('start', !empty($row['start']) ? new Date($row['start'], Timezone::SERVER_TIMEZONE) : null);
	$calendar_end = new MiniCalendar('end', !empty($row['end']) ? new Date($row['end'], Timezone::SERVER_TIMEZONE) : null);
	$calendar_current_date = new MiniCalendar('current_date', !empty($row['timestamp']) ? new Date($row['timestamp'], Timezone::SERVER_TIMEZONE) : new Date());

	$tpl->put_all(array(
		'C_TYPE_UNIQUE'         => ($row['type'] == '1'),
		'C_TYPE_MULTIPLE'       => ($row['type'] == '0'),
		'C_ARCHIVES_ENABLED'    => ($row['archive'] == '1'),
		'C_ARCHIVES_DISABLED'   => ($row['archive'] == '0'),
		'C_VISIBLE_WAITING'     => ($row['visible'] == 2 || !empty($row['end'])),
		'C_VISIBLE_ENABLED'     => ($row['visible'] == 1 && empty($row['end'])),
		'C_VISIBLE_UNAPROB'     => ($row['visible'] == 0),
		'IDPOLL'                => $row['id'],
		'QUESTIONS'             => stripslashes($row['question']),
		'CALENDAR_START'        => $calendar_start->display(),
		'CALENDAR_END'          => $calendar_end->display(),
		'CALENDAR_CURRENT_DATE' => $calendar_current_date->display(),
		'HOUR'                  => Date::to_format($row['timestamp'], 'H'),
		'MIN'                   => Date::to_format($row['timestamp'], 'i'),
		'DATE'                  => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),
		'L_REQUIRE_QUESTION'    => $LANG['require_question'],
		'L_REQUIRE_ANSWER'      => $LANG['require_answer'],
		'L_REQUIRE_ANSWER_TYPE' => $LANG['require_answer_type'],
		'L_POLL_MANAGEMENT'     => $LANG['poll_management'],
		'L_POLL_ADD'            => $LANG['poll_add'],
		'L_POLL_CONFIG'         => $LANG['poll_config'],
		'L_REQUIRE'             => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_QUESTION'            => $LANG['question'],
		'L_ANSWER_TYPE'         => $LANG['answer_type'],
		'L_ANSWERS'             => $LANG['answers'],
		'L_ARCHIVES'            => $LANG['archives'],
		'L_SINGLE'              => $LANG['single'],
		'L_MULTIPLE'            => $LANG['multiple'],
		'L_NUMBER_VOTE'         => $LANG['number_vote'],
		'L_DATE'                => LangLoader::get_message('date', 'date-common'),
		'L_POLL_DATE'           => $LANG['poll_date'],
		'L_RELEASE_DATE'        => $LANG['release_date'],
		'L_IMMEDIATE'           => $LANG['immediate'],
		'L_UNAPROB'             => $LANG['unaprob'],
		'L_UNTIL'               => $LANG['until'],
		'L_UPDATE'              => $LANG['update'],
		'L_RESET'               => $LANG['reset']
	));

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['incomplete'], MessageHelper::NOTICE));

	$array_answer = explode('|', $row['answers']);
	$array_vote = explode('|', $row['votes']);

	$sum_vote = array_sum($array_vote);
	$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.

	//Liste des choix des sondages => 20 maxi
	$i = 0;
	$array_poll = array_combine($array_answer, $array_vote);
	foreach ($array_poll as $answer => $nbrvote)
	{
		$percent = NumberHelper::round(($nbrvote * 100 / $sum_vote), 1);
		$tpl->assign_block_vars('answers', array(
			'ID'     => $i,
			'ANSWER' => !empty($answer) ? $answer : ''
		));
		$tpl->assign_block_vars('votes', array(
			'ID'      => $i,
			'VOTES'   => isset($nbrvote) ? $nbrvote : '',
			'PERCENT' => isset($percent) ? $percent . '%' : ''
		));
		$i++;
	}

	$tpl->put_all(array(
		'MAX_ID' => $i
	));

	$tpl->display();
}
else
{
	$_NBR_ELEMENTS_PER_PAGE = 20;

	$tpl = new FileTemplate('poll/admin_poll_management.tpl');

	$nbr_poll = PersistenceContext::get_querier()->count(PREFIX . 'poll');

	//On crée une pagination si le nombre de sondages est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_poll, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/poll/admin_poll.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$tpl->put_all(array(
		'C_PAGINATION'      => $pagination->has_several_pages(),
		'PAGINATION'        => $pagination->display(),
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD'        => $LANG['poll_add'],
		'L_POLL_CONFIG'     => $LANG['poll_config'],
		'L_REQUIRE'         => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_QUESTION'        => $LANG['question'],
		'L_POLLS'           => $LANG['polls'],
		'L_DATE'            => LangLoader::get_message('date', 'date-common'),
		'L_PSEUDO'          => LangLoader::get_message('author', 'common'),
		'L_APROB'           => $LANG['aprob'],
		'L_UPDATE'          => $LANG['update']
	));

	$result = PersistenceContext::get_querier()->select("SELECT p.id, p.question, p.archive, p.timestamp, p.visible, p.start, p.end, p.user_id, m.display_name, m.groups, m.level
	FROM " . PREFIX . "poll p
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON p.user_id = m.user_id
	ORDER BY p.timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from",
		array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)
	);
	while ($row = $result->fetch())
	{
		if ($row['visible'] == 2)
			$aprob = $LANG['waiting'];
		elseif ($row['visible'] == 1)
			$aprob = LangLoader::get_message('yes', 'common');
		else
			$aprob = LangLoader::get_message('no', 'common');

		$archive = ( $row['archive'] == 1) ?  LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common');

		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$question = stripslashes(TextHelper::strlen($row['question']) > 45 ? TextHelper::substr($row['question'], 0, 45) . '...' : $row['question']);

		$visible = '';
		if ($row['start'] > 0)
			$visible .= Date::to_format($row['start'], Date::FORMAT_DAY_MONTH_YEAR);
		if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . TextHelper::strtolower($LANG['until']) . ' ' . Date::to_format($row['end'], Date::FORMAT_DAY_MONTH_YEAR);
		elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . Date::to_format($row['end'], Date::FORMAT_DAY_MONTH_YEAR);

		$group_color = User::get_group_color($row['groups'], $row['level']);

		$date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

		$tpl->assign_block_vars('questions', array_merge(
			Date::get_array_tpl_vars($date,'date'),
			array(
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'QUESTIONS'          => $question,
			'IDPOLL'             => $row['id'],
			'U_POLL'             => PATH_TO_ROOT . "/poll/poll" . (( $row['archive'] == 1) ? url('.php?archives=' . $row['id']) : url('.php?id=' . $row['id'])),
			'U_EDIT'             => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?id=' . $row['id']),
			'U_DELETE'           => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?delete=1&amp;id=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'PSEUDO'             => $row['display_name'],
			'USER_GROUP_COLOR'   => $group_color,
			'USER_LEVEL_CLASS'   => UserService::get_level_class($row['level']),
			'ARCHIVES'           => $archive,
			'APROBATION'         => $aprob,
			'VISIBLE'            => ((!empty($visible)) ? '(' . $visible . ')' : ''),
			'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($row['user_id'])->rel()
		)));
	}
	$result->dispose();

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>
