<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 25
 * @since       PHPBoost 1.2 - 2005 07 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../poll/poll_begin.php');
require_once('../kernel/header.php');

$poll = array();
$request = AppContext::get_request();

$poll_id = $request->get_getint('id', 0);
$valid = $request->get_postvalue('valid_poll', false);

$now = new Date();

if (!empty($poll_id))
{
	try {
		$poll = PersistenceContext::get_querier()->select_single_row(PREFIX . 'poll', array('id', 'question', 'votes', 'answers', 'type', 'timestamp', 'end'), 'WHERE id=:id AND archive = 0 AND visible = 1 AND start <= :timestamp AND (end >= :timestamp OR end = 0)', array('id' => $poll_id, 'timestamp' => $now->get_timestamp()));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}

$archives = (bool)retrieve(GET, 'archives', false); //On vérifie si on est sur les archives
$show_result = (bool)retrieve(GET, 'r', false); //Affichage des résultats.
$now = new Date(Date::DATE_NOW, Timezone::USER_TIMEZONE);

//Récupération des éléments de configuration
$config_cookie_name = $poll_config->get_cookie_name();
$config_cookie_lenght = $poll_config->get_cookie_lenght_in_seconds();
$config_displayed_in_mini_module_list = $poll_config->get_displayed_in_mini_module_list();

if ($valid && !empty($poll['id']) && !$archives)
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}

	//Autorisation de voter
	if (PollAuthorizationsService::check_authorizations()->write())
	{
		//On note le passage du visiteur par un cookie.
		if (AppContext::get_request()->has_cookieparameter($config_cookie_name)) //Recherche dans le cookie existant.
		{
			$array_cookie = explode('/', AppContext::get_request()->get_cookie($config_cookie_name));
			if (in_array($poll['id'], $array_cookie))
				$check_cookie = true;
			else
			{
				$check_cookie = false;

				$array_cookie[] = $poll['id']; //Ajout nouvelle valeur.
				$value_cookie = implode('/', $array_cookie); //On retransforme le tableau en chaîne.

				AppContext::get_response()->set_cookie(new HTTPCookie($config_cookie_name, $value_cookie, time() + $config_cookie_lenght));
			}
		}
		else //Génération d'un cookie.
		{
			$check_cookie = false;
			AppContext::get_response()->set_cookie(new HTTPCookie($config_cookie_name, $poll['id'], time() + $config_cookie_lenght));
		}

		$check_bdd = true;
		if (Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $poll_config->get_authorizations(), PollAuthorizationsService::WRITE_AUTHORIZATIONS)) //Autorisé aux visiteurs, on filtre par ip => fiabilité moyenne.
		{
			//Injection de l'adresse ip du visiteur dans la bdd.
			$ip = PersistenceContext::get_querier()->count(PREFIX . "poll_ip", 'WHERE ip = :ip AND idpoll = :id', array('ip' => AppContext::get_request()->get_ip_address(), 'id' => $poll['id']));
			if (empty($ip))
			{
				//Insertion de l'adresse ip.
				PersistenceContext::get_querier()->insert(PREFIX . "poll_ip", array('ip' => AppContext::get_request()->get_ip_address(), 'user_id' => -1, 'idpoll' => $poll['id'], 'timestamp' => time()));
				$check_bdd = false;
			}
		}
		else //Autorisé aux membres, on filtre par le user_id => fiabilité 100%.
		{
			//Injection de l'adresse ip du visiteur dans la bdd.
			$nbr_votes = PersistenceContext::get_querier()->count(PREFIX . "poll_ip", 'WHERE user_id = :user_id AND idpoll = :id', array('user_id' => AppContext::get_current_user()->get_id(), 'id' => $poll['id']));
			if (empty($nbr_votes))
			{
				//Insertion de l'adresse ip.
				PersistenceContext::get_querier()->insert(PREFIX . "poll_ip", array('ip' => AppContext::get_request()->get_ip_address(), 'user_id' => AppContext::get_current_user()->get_id(), 'idpoll' => $poll['id'], 'timestamp' => time()));
				$check_bdd = false;
			}
		}

		//Si le cookie n'existe pas et l'ip n'est pas connue on enregistre.
		if ($check_bdd || $check_cookie)
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_already_vote', '-' . $poll['id'] . '.php?error=e_already_vote', '&') . '#message_helper');

		//Récupération du vote.
		$check_answer = false;
		$array_votes = explode('|', $poll['votes']);
		if ($poll['type'] == '1') //Réponse unique.
		{
			$id_answer = retrieve(POST, 'radio', -1);
			if (isset($array_votes[$id_answer]))
			{
				$array_votes[$id_answer]++;
				$check_answer = true;
			}
		}
		else //Réponses multiples.
		{
			//On boucle pour vérifier toutes les réponses du sondage.
			$nbr_answer = count($array_votes);
			for ($i = 0; $i < $nbr_answer; $i++)
			{
				if ($request->has_postparameter($i))
				{
					$array_votes[$i]++;
					$check_answer = true;
				}
			}
		}

		if ($check_answer) //Enregistrement vote du sondage
		{
			PersistenceContext::get_querier()->update(PREFIX . "poll", array('votes' => implode('|', $array_votes)), 'WHERE id = :id', array('id' => $poll['id']));

			if (in_array($poll['id'], $config_displayed_in_mini_module_list) ) //Vote effectué du mini poll => mise à jour du cache du mini poll.
				PollMiniMenuCache::invalidate();

			//Tout s'est bien déroulé, on redirige vers la page des resultats.
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'));
		}
		else //Vote blanc
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'));
	}
	else
		AppContext::get_response()->redirect(PATH_TO_ROOT . '/poll/poll' . url('.php?id=' . $poll['id'] . '&error=e_unauth_poll', '-' . $poll['id'] . '.php?error=e_unauth_poll', '&') . '#message_helper');
}
elseif (!empty($poll['id']) && !$archives) //Affichage du sondage.
{
	$tpl = new FileTemplate('poll/poll.tpl');

	$Bread_crumb->add($LANG['poll'], PATH_TO_ROOT . '/poll');
	$Bread_crumb->add(stripslashes($poll['question']), '');

	//Résultats
	$check_bdd = false;
	if (Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $poll_config->get_authorizations(), PollAuthorizationsService::WRITE_AUTHORIZATIONS)) //Autorisé aux visiteurs, on filtre par ip => fiabilité moyenne.
	{
		//Injection de l'adresse ip du visiteur dans la bdd.
		$ip = PersistenceContext::get_querier()->count(PREFIX . "poll_ip", 'WHERE ip = :ip AND idpoll = :id', array('ip' => AppContext::get_request()->get_ip_address(), 'id' => $poll['id']));
		if (!empty($ip))
			$check_bdd = true;
	}
	else //Autorisé aux membres, on filtre par le user_id => fiabilité 100%.
	{
		//Injection de l'adresse ip du visiteur dans la bdd.
		$nbr_votes = PersistenceContext::get_querier()->count(PREFIX . "poll_ip", 'WHERE user_id = :user_id AND idpoll = :id', array('user_id' => AppContext::get_current_user()->get_id(), 'id' => $poll['id']));
		if (!empty($nbr_votes))
			$check_bdd = true;
	}

	//Gestion des erreurs
	$get_error = retrieve(GET, 'error', '');
	switch ($get_error)
	{
		case 'e_already_vote':
		$errstr = $LANG['e_already_vote'];
		$type = MessageHelper::WARNING;
		break;
		case 'e_unauth_poll':
		$errstr = $LANG['e_unauth_poll'];
		$type = MessageHelper::WARNING;
		break;
		default:
		$errstr = '';
	}
	if (!empty($errstr))
		$tpl->put('message_helper', MessageHelper::display($errstr, $type));

	//Si le cookie existe, ou l'ip est connue on redirige vers les resulats, sinon on prend en compte le vote.
	$array_cookie = array();
	if (AppContext::get_request()->has_cookieparameter($config_cookie_name))
	{
		$array_cookie = explode('/', AppContext::get_request()->get_cookie($config_cookie_name));
	}
	if ($show_result || in_array($poll['id'], $array_cookie) === true || $check_bdd) //Résultats
	{
		$array_answer = explode('|', $poll['answers']);
		$array_vote = explode('|', $poll['votes']);
		$poll_creation_date = new Date($poll['timestamp'], Timezone::SERVER_TIMEZONE);
		$poll_end_date = new Date($poll['end'], Timezone::SERVER_TIMEZONE);

		$is_admin = AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
		$results_displayed = $poll_config->are_results_displayed_before_polls_end() && !empty($poll['end']) ? $now->get_timestamp() > $poll_end_date->get_timestamp : true;

		$sum_vote = array_sum($array_vote);
		$tpl->put_all(array_merge(
			Date::get_array_tpl_vars($poll_creation_date,'date'),
			array(
			'C_POLL_VIEW'                 => true,
			'C_POLL_RESULTS'              => true,
			'C_IS_ADMIN'                  => $is_admin,
			'C_DISPLAY_RESULTS'           => $is_admin || ($results_displayed && (!empty($nbr_votes) || !empty($ip))),
			'C_NO_VOTE'                   => !$is_admin && (empty($nbr_votes) && empty($ip)),
			'U_EDIT'                      => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?id=' . $poll['id']),
			'U_DEL'                       => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?delete=1&amp;id=' . $poll['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'IDPOLL'                      => $poll['id'],
			'QUESTION'                    => stripslashes($poll['question']),
			'VOTES'                       => $sum_vote,
			'L_POLL'                      => $LANG['poll'],
			'L_BACK_POLL'                 => $LANG['poll_back'],
			'L_VOTE'                      => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote']),
			'L_NO_VOTE'                   => $LANG['e_no_vote'],
			'L_RESULTS_NOT_DISPLAYED_YET' => StringVars::replace_vars($LANG['e_results_not_displayed_yet'], array('end_date' => $poll_end_date->format(Date::FORMAT_DAY_MONTH_YEAR)))
			)
		));

		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.
		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$nbrvote = intval($nbrvote);
			$percent = NumberHelper::round(($nbrvote * 100 / $sum_vote), 1);

			$tpl->assign_block_vars('result', array(
				'ANSWERS' => $answer,
				'NBRVOTE' => $nbrvote,
				'WIDTH'   => $percent * 4, //x 4 Pour agrandir la barre de vote.
				'PERCENT' => $percent
			));
		}

		$tpl->display();
	}
	else //Questions.
	{
		$date = new Date($poll['timestamp'], Timezone::SERVER_TIMEZONE);

		$tpl->put_all(array_merge(
			Date::get_array_tpl_vars($date,'date'),
			array(
			'C_POLL_VIEW'     => true,
			'C_POLL_QUESTION' => true,
			'C_IS_ADMIN'      => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'IDPOLL'          => $poll['id'],
			'U_EDIT'          => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?id=' . $poll['id']),
			'U_DEL'           => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?delete=1&amp;id=' . $poll['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'QUESTION'        => stripslashes($poll['question']),
			'VOTES'           => 0,
			'ID_R'            => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'QUESTION'        => stripslashes($poll['question']),
			'U_POLL_ACTION'   => url('.php?id=' . $poll['id'], '-' . $poll['id'] . '.php'),
			'U_POLL_RESULT'   => url('.php?id=' . $poll['id'] . '&amp;r=1', '-' . $poll['id'] . '-1.php'),
			'L_POLL'          => $LANG['poll'],
			'L_MINI_POLL'     => $LANG['mini_poll'],
			'L_BACK_POLL'     => $LANG['poll_back'],
			'L_VOTE'          => $LANG['poll_vote'],
			'L_RESULT'        => $LANG['poll_result']
			)
		));

		$z = 0;
		$array_answer = explode('|', $poll['answers']);
		if ($poll['type'] == '1')
		{
			foreach ($array_answer as $answer)
			{
				$tpl->assign_block_vars('radio', array(
					'NAME' => $z,
					'TYPE' => 'radio',
					'ANSWERS' => $answer
				));
				$z++;
			}
		}
		elseif ($poll['type'] == '0')
		{
			foreach ($array_answer as $answer)
			{
				$tpl->assign_block_vars('checkbox', array(
					'NAME'    => $z,
					'TYPE'    => 'checkbox',
					'ANSWERS' => $answer
				));
				$z++;
			}
		}
		$tpl->display();
	}
}
elseif ($archives) //Archives.
{
	$_NBR_ELEMENTS_PER_PAGE = 10;

	$tpl = new FileTemplate('poll/poll.tpl');

	$nbrarchives = PersistenceContext::get_querier()->count(PREFIX . "poll", 'WHERE archive = 1 AND visible = 1');

	//On crée une pagination si le nombre de sondages est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbrarchives, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/poll/poll.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$tpl->put_all(array(
		'C_POLL_ARCHIVES' => true,
		'C_IS_ADMIN'      => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
		'C_PAGINATION'    => $pagination->has_several_pages(),
		'PAGINATION'      => $pagination->display(),
		'L_ARCHIVE'       => $LANG['archives'],
		'L_BACK_POLL'     => $LANG['poll_back']
	));

	//On recupère les sondages archivés.
	$result = PersistenceContext::get_querier()->select("SELECT id, question, votes, answers, type, timestamp
	FROM " . PREFIX . "poll
	WHERE archive = 1 AND visible = 1
	ORDER BY timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from",
		array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)
	);
	while ($row = $result->fetch())
	{
		$array_answer = explode('|', $row['answers']);
		$array_vote = explode('|', $row['votes']);

		$sum_vote = array_sum($array_vote);
		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.

		$tpl->assign_block_vars('list', array_merge(
			Date::get_array_tpl_vars(Date::DATE_NOW, 'date'),
			array(
			'ID'       => $row['id'],
			'QUESTION' => stripslashes($row['question']),
			'U_EDIT'   => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?id=' . $row['id']),
			'U_DEL'    => PATH_TO_ROOT . "/poll/admin_poll" . url('.php?delete=1&amp;id=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'VOTE'     => $sum_vote,
			'L_VOTE'   => (($sum_vote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
			)
		));

		$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.
		$array_poll = array_combine($array_answer, $array_vote);
		foreach ($array_poll as $answer => $nbrvote)
		{
			$nbrvote = intval($nbrvote);
			$percent = NumberHelper::round(($nbrvote * 100 / $sum_vote), 1);

			$tpl->assign_block_vars('list.result', array(
				'ANSWERS' => $answer,
				'NBRVOTE' => $nbrvote,
				'WIDTH'   => $percent * 4, //x 4 Pour agrandir la barre de vote.
				'PERCENT' => $percent,
				'L_VOTE'  => (($nbrvote > 1 ) ? $LANG['poll_vote_s'] : $LANG['poll_vote'])
			));
		}
	}
	$result->dispose();

	$tpl->display();
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('poll');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

require_once('../kernel/footer.php');

?>
