<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 23
 * @since       PHPBoost 1.2 - 2005 06 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);

$tpl = new FileTemplate('poll/admin_poll_add.tpl');

if ($valid)
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$question = retrieve(POST, 'question', '');
	$type = (int)retrieve(POST, 'type', 1);
	$archive = (int)retrieve(POST, 'archive', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);
	$get_visible = (int)retrieve(POST, 'visible', 0);
	$poll_type = (int)retrieve(POST, 'poll_type', 0);

	//On verifie les conditions!
	if (!empty($question))
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
			$timestamp += ($hour * 3600) + ($min * 60);
		else //Ajout des heures et minutes
			$timestamp = time();

		$poll_type = NumberHelper::numeric($poll_type);
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

		PersistenceContext::get_querier()->insert(PREFIX . "poll", array('question' => $question, 'answers' => TextHelper::substr($answers, 0, TextHelper::strlen($answers) - 1), 'votes' => TextHelper::substr($votes, 0, TextHelper::strlen($votes) - 1), 'type' => $type, 'archive' => $archive, 'timestamp' => $timestamp, 'visible' => $visible, 'start' => $start_timestamp, 'end' => $start_timestamp, 'user_id' => AppContext::get_current_user()->get_id()));

		AppContext::get_response()->redirect('/poll/admin_poll.php');
	}
	else
		$tpl->put('message_helper', MessageHelper::display($LANG['incomplete'], MessageHelper::NOTICE));
}

$calendar_start = new MiniCalendar('start');
$calendar_end = new MiniCalendar('end');
$calendar_current_date = new MiniCalendar('current_date', new Date());

$tpl->put_all(array(
	'VISIBLE_ENABLED'       => 'checked="checked"',
	'CALENDAR_START'        => $calendar_start->display(),
	'CALENDAR_END'          => $calendar_end->display(),
	'CALENDAR_CURRENT_DATE' => $calendar_current_date->display(),
	'L_REQUIRE_QUESTION'    => $LANG['require_question'],
	'L_REQUIRE_ANSWER'      => $LANG['require_answer'],
	'L_POLL_MANAGEMENT'     => $LANG['poll_management'],
	'L_POLL_ADD'            => $LANG['poll_add'],
	'L_POLL_CONFIG'         => $LANG['poll_config'],
	'L_REQUIRE'             => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
	'L_QUESTION'            => $LANG['question'],
	'L_ANSWERS_TYPE'        => $LANG['answer_type'],
	'L_YES'                 => LangLoader::get_message('yes', 'common'),
	'L_NO'                  => LangLoader::get_message('no', 'common'),
	'L_ARCHIVES'            => $LANG['archives'],
	'L_SINGLE'              => $LANG['single'],
	'L_MULTIPLE'            => $LANG['multiple'],
	'L_ANSWERS'             => $LANG['answers'],
	'L_NUMBER_VOTE'         => $LANG['number_vote'],
	'L_DATE'                => LangLoader::get_message('date', 'date-common'),
	'L_UNTIL'               => $LANG['until'],
	'L_RELEASE_DATE'        => $LANG['release_date'],
	'L_IMMEDIATE'           => $LANG['immediate'],
	'L_UNAPROB'             => $LANG['unaprob'],
	'L_POLL_DATE'           => $LANG['poll_date'],
	'L_SUBMIT'              => $LANG['submit'],
	'L_RESET'               => $LANG['reset']
));

$tpl->display();

require_once('../admin/admin_footer.php');
?>
