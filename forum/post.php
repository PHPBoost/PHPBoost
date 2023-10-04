<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 1.2 - 2005 10 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get_all_langs('forum');

$id_get = (int)retrieve(GET, 'id', 0);

$is_modo = ForumAuthorizationsService::check_authorizations($id_get)->moderation();

//Existance de la catégorie.
if ($id_get != Category::ROOT_CATEGORY && !CategoriesService::get_categories_manager('forum')->get_categories_cache()->category_exists($id_get))
{
	$controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($controller);
}

if (AppContext::get_current_user()->get_delay_readonly() > time()) //Lecture seule.
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

try {
	$category = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($id_get);
} catch (CategoryNotFoundException $e) {
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$locked_cat = ($category->get_status() == ForumCategory::STATUS_LOCKED && !AppContext::get_current_user()->is_admin());

//Récupération de la barre d'arborescence.
$Bread_crumb->add($config->get_forum_name(), 'index.php');
$categories = array_reverse(CategoriesService::get_categories_manager('forum')->get_parents($id_get, true));

foreach ($categories as $id => $cat)
{
	if ($cat->get_id() != Category::ROOT_CATEGORY)
		$Bread_crumb->add($cat->get_name(), 'forum' . url('.php?id=' . $cat->get_id(), '-' . $cat->get_id() . '-' . $cat->get_rewrited_name() . '.php'));
}

define('TITLE', LangLoader::get_message('contribution.contribution', 'contribution-lang'));

require_once('../kernel/header.php');

$new_get = retrieve(GET, 'new', '');
$idt_get = retrieve(GET, 'idt', '');
$error_get = retrieve(GET, 'error', '');
$post_topic = (bool)retrieve(POST, 'post_topic', false);

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('content');

//Niveau d'autorisation de la catégorie
if (ForumAuthorizationsService::check_authorizations($id_get)->read())
{
	$Forumfct = new Forum();

	//Mod anti-flood
	$check_time = false;

	if (ContentManagementConfig::load()->is_anti_flood_enabled() && AppContext::get_current_user()->get_id() != -1)
	{
		try {
			$check_time = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MAX(timestamp) as timestamp', 'WHERE user_id = :user_id', array('user_id' => AppContext::get_current_user()->get_id()));
		} catch (RowNotFoundException $e) {}
	}

	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';
	// $Bread_crumb->remove_last();
	foreach ($Bread_crumb->get_links() as $key => $array)
	{
		if ($i == 2)
			$forum_cats .= '<a href="' . $array[1] . '" class="offload">' . $array[0] . '</a>';
		elseif ($i > 2)
			$forum_cats .= ' <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="' . $array[1] . '" class="offload">' . $array[0] . '</a>';
		$i++;
	}

	if ($new_get === 'topic' && empty($error_get)) //Nouveau topic.
	{
		$Bread_crumb->add($lang['forum.new.topic'], '');
		if ($post_topic && !empty($id_get))
		{
			if (!ForumAuthorizationsService::check_authorizations($id_get)->write() || $locked_cat)
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#message_helper');

			if ($is_modo)
				$type = (int)retrieve(POST, 'type', 0);
			else
				$type = 0;

			//Verrouillé?
			$check_status = $category->get_status();
			//Déverrouillé pour admin et modo dans tous les cas
			if ($is_modo)
				$check_status = ForumCategory::STATUS_UNLOCKED;

			$content = retrieve(POST, 'content', '', TSTRING_UNCHANGE);
			$title = retrieve(POST, 'title', '');
			$subtitle = retrieve(POST, 'desc', '');

			//Mod anti Flood
			if ($check_time !== false && $check_status == ForumCategory::STATUS_UNLOCKED)
			{
				$delay_flood = ContentManagementConfig::load()->get_anti_flood_duration(); //On recupère le delai de flood.
				$delay_expire = time() - $delay_flood; //On calcul la fin du delai.

				//Droit de flooder?.
				if ($check_time >= $delay_expire && !ForumAuthorizationsService::check_authorizations()->flood()) //Flood
					AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=flood_t&id=' . $id_get, '', '&') . '#message_helper');
			}

			if ($check_status == ForumCategory::STATUS_UNLOCKED)
			{
				if (!empty($content) && !empty($title)) //Insertion nouveau topic.
				{
					list($last_topic_id, $last_msg_id) = $Forumfct->Add_topic($id_get, $title, $subtitle, $content, $type); //Insertion nouveau topic.

					//Ajout d'un sondage en plus du topic.
					$question = retrieve(POST, 'question', '');
					if (!empty($question))
					{
						$poll_type = (int)retrieve(POST, 'poll_type', 0);
						$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;

						$answers = array();
						$nbr_votes = 0;
						for ($i = 0; $i < 20; $i++)
						{
							$answer = str_replace('|', '', retrieve(POST, 'a'.$i, ''));
							if (!empty($answer))
							{
								$answers[$i] = $answer;
								$nbr_votes++;
							}
						}
						$Forumfct->Add_poll($last_topic_id, $question, $answers, $nbr_votes, $poll_type); //Ajout du sondage.
					}

					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $last_topic_id, '-' . $last_topic_id . '.php', '&') . '#m' . $last_msg_id);
				}
				else
					AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete_t&id=' . $id_get, '', '&') . '#message_helper');
			}
			else //Verrouillé
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#message_helper');
		}
		else
		{
			if (!ForumAuthorizationsService::check_authorizations($id_get)->write() || $locked_cat)
			{
				$controller = new UserErrorController($lang['warning.error'], $locked_cat ? $lang['forum.error.locked.category'] : $lang['forum.error.category.right']);
				DispatchManager::redirect($controller);
			}

			$view = new FileTemplate('forum/forum_post.tpl');
			$view->add_lang($lang);

			if (ForumAuthorizationsService::check_authorizations($id_get)->moderation())
			{
				$view->put_all(array(
					'C_FORUM_POST_TYPE'      => true,
					'C_NORMAL_TYPE_SELECTED' => true
				));
			}

			//Liste des choix des sondages => 20 maxi
			$nbr_poll_field = 0;
			for ($i = 0; $i < 5; $i++)
			{
				$view->assign_block_vars('answers_poll', array(
					'ID'     => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}

			$vars_tpl = array(
				'C_ADD_POLL_FIELD'       => true,
				'C_SIMPLE_POLL_SELECTED' => true,
				'C_DISPLAY_POLL'         => false,
				
				'FORUM_NAME'             => $config->get_forum_name(),
				'CATEGORY_NAME'          => $category->get_name(),
				'TITLE'                  => '',
				'DESCRIPTION'            => '',
				'IDTOPIC'                => 0,
				'KERNEL_EDITOR'          => $editor->display(),
				'NBR_POLL_FIELD'         => $nbr_poll_field,

				'U_ACTION'               => 'post.php' . url('?new=topic&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_CATEGORY'             => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),
				'U_TITLE_T'              => 'post' . url('.php?new=topic&amp;id=' . $id_get),

				'L_ACTION'               => $lang['forum.new.topic'],
			);

			$view->put_all($vars_tpl);

			$view->put('FORUM_TOP', $top_view->display());
			$view->display();
			$view->put('FORUM_BOTTOM', $bottom_view->display());
		}
	}
	elseif ($new_get === 'n_msg' && empty($error_get)) //Nouveau message
	{
		if (!ForumAuthorizationsService::check_authorizations($id_get)->write())
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#message_helper');

		try {
			$topic = PersistenceContext::get_querier()->select_single_row_query('SELECT user_id, id_category, title, nbr_msg, last_user_id, last_msg_id, status
			FROM ' . PREFIX . 'forum_topics
			WHERE id=:id', array(
				'id' => $idt_get
			));
		} catch (RowNotFoundException $e) {
			$controller = new UserErrorController($lang['warning.error'], $lang['forum.error.locked.topic']);
			DispatchManager::redirect($controller);
		}

		//Catégorie verrouillée?
		$check_status = $category->get_status();
		//Déverrouillé pour admin et modo dans tous les cas
		if ($is_modo)
			$check_status = ForumCategory::STATUS_UNLOCKED;

		if ($check_status == ForumCategory::STATUS_LOCKED) //Verrouillée
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_locked&id=' . $id_get, '', '&') . '#message_helper');

		//Mod anti Flood
		if ($check_time !== false)
		{
			$delay_expire = time() - ContentManagementConfig::load()->get_anti_flood_duration(); //On calcul la fin du delai.
			//Droit de flooder?
			if ($check_time >= $delay_expire && !ForumAuthorizationsService::check_authorizations()->flood()) //Ok
				AppContext::get_response()->redirect( url(HOST . SCRIPT . '?error=flood&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#message_helper');
		}

		$content = retrieve(POST, 'content', '', TSTRING_AS_RECEIVED);

		//Si le topic n'est pas vérrouilé on ajoute le message.
		if ($topic['status'] != 0 || $is_modo)
		{
			if (!empty($content) && !empty($idt_get) && empty($update)) //Nouveau message.
			{
				$last_page = ceil( ($topic['nbr_msg'] + 1) / $config->get_number_messages_per_page() );
				$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
				$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';

				if ($config->are_multiple_posts_allowed() || ForumAuthorizationsService::check_authorizations()->multiple_posts() || $topic['last_user_id'] != AppContext::get_current_user()->get_id())
					$last_msg_id = $Forumfct->Add_msg($idt_get, $topic['id_category'], $content, $topic['title'], $last_page, $last_page_rewrite);
				else
				{
					$last_page = ceil( $topic['nbr_msg'] / $config->get_number_messages_per_page() );
					$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
					$last_page = ($last_page > 1) ? '&pt=' . $last_page : '';

					$message_content = '';
					try {
						$message_content = FormatingHelper::unparse(PersistenceContext::get_querier()->get_column_value(PREFIX . 'forum_msg', 'content', 'WHERE id = :id', array('id' => $topic['last_msg_id'])));
					} catch (RowNotFoundException $e) {}

					$now = new Date();

					if (AppContext::get_current_user()->get_editor() == 'TinyMCE')
					{
						$message_content .= '<br /><br />-------------------------------------------<br /><em>' . $lang['forum.edited.on'] . ' ' . $now->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) . '</em><br /><br />' . $content;
					}
					else
					{
						$message_content .= '

-------------------------------------------
[i]' . $lang['forum.edited.on'] . ' ' . $now->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT) . '[/i]

' . $content;
					}

					$Forumfct->Update_msg($idt_get, $topic['last_msg_id'], $message_content, $topic['last_user_id']); //Mise à jour du message.
				}

				//Redirection après post.
				AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . $last_page, '-' . $idt_get . $last_page_rewrite . '.php', '&') . '#m' . $last_msg_id);
			}
			else
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#message_helper');
		}
		else
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=locked&id=' . $id_get . '&idt=' . $idt_get, '', '&') . '#message_helper');
	}
	elseif ($new_get === 'msg' && empty($error_get)) //Edition d'un message/topic.
	{
		if (!ForumAuthorizationsService::check_authorizations($id_get)->write())
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=c_write&id=' . $id_get, '', '&') . '#message_helper');

		$id_m = (int)retrieve(GET, 'idm', 0);
		$update = (bool)retrieve(GET, 'update', false);

		try {
			$id_first = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'MIN(id)', 'WHERE idtopic = :idtopic', array('idtopic' => $idt_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if (empty($id_get) || empty($id_first)) //Topic/message inexistant.
		{
			$controller = new UserErrorController($lang['warning.error'], $lang['forum.error.non.existent.topic']);
			DispatchManager::redirect($controller);
		}

		try {
			$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('title', 'subtitle', 'type', 'user_id', 'display_msg'), 'WHERE id=:id', array('id' => $idt_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Edition du topic complet
		if ($id_first == $id_m)
		{
			$Bread_crumb->add($lang['forum.edit.topic'], '');
			//User_id du message correspondant à l'utilisateur connecté => autorisation.
			$user_id_msg = 0;
			try {
				$user_id_msg = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'user_id', 'WHERE id = :id', array('id' => $id_m));
			} catch (RowNotFoundException $e) {}

			$check_auth = false;
			if ($user_id_msg == AppContext::get_current_user()->get_id())
				$check_auth = true;
			elseif ($is_modo)
				$check_auth = true;

			if (!$check_auth)
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}

			if ($update && $post_topic)
			{
				$title = retrieve(POST, 'title', '');
				$subtitle = retrieve(POST, 'desc', '');
				$content = retrieve(POST, 'content', '', TSTRING_AS_RECEIVED);
				$type = $is_modo ? (int)retrieve(POST, 'type', 0) : 0;

				if (!empty($title) && !empty($content))
				{
					$Forumfct->Update_topic($idt_get, $id_m, $title, $subtitle, addslashes($content), $type, $user_id_msg); //Mise à jour du topic.

					//Mise à jour du sondage en plus du topic.
					$del_poll = (bool)retrieve(POST, 'del_poll', false);
					$question = retrieve(POST, 'question', '');
					if (!empty($question) && !$del_poll) //Enregistrement du sondage.
					{
						//Mise à jour si le sondage existe, sinon création.
						$check_poll = PersistenceContext::get_querier()->count(PREFIX . 'forum_poll', 'WHERE idtopic=:idtopic', array('idtopic' => $idt_get));

						$poll_type = (int)retrieve(POST, 'poll_type', 0);
						$poll_type = ($poll_type == 0 || $poll_type == 1) ? $poll_type : 0;

						$answers = array();
						$nbr_votes = 0;
						for ($i = 0; $i < 20; $i++)
						{
							$answer = str_replace('|', '', retrieve(POST, 'a'.$i, ''));
							if (!empty($answer))
							{
								$answers[$i] = $answer;
								$nbr_votes++;
							}
						}

						if ($check_poll == 1) //Mise à jour.
							$Forumfct->Update_poll($idt_get, $question, $answers, $poll_type);
						elseif ($check_poll == 0) //Ajout du sondage.
							$Forumfct->Add_poll($idt_get, $question, $answers, $nbr_votes, $poll_type);
					}
					elseif ($del_poll && ForumAuthorizationsService::check_authorizations($id_get)->moderation()) //Suppression du sondage, admin et modo seulement biensûr...
						$Forumfct->Del_poll($idt_get);

					//Redirection après post.
					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php', '&'));
				}
				else
					AppContext::get_response()->redirect('/forum/post' . url('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete_t', '', '&') . '#message_helper');
			}
			else
			{
				$view = new FileTemplate('forum/forum_post.tpl');
				$view->add_lang($lang);

				$content = '';
				try {
					$content = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'content', 'WHERE id = :id', array('id' => $id_first));
				} catch (RowNotFoundException $e) {}

				//Gestion des erreurs à l'édition.
				$get_error_e = retrieve(GET, 'errore', '');
				if ($get_error_e == 'incomplete_t')
					$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.incomplete'], MessageHelper::NOTICE));

				if ($is_modo)
				{
					$view->put_all(array(
						'C_FORUM_POST_TYPE'        => true,
						'C_NORMAL_TYPE_SELECTED'   => ($topic['type'] == '0'),
						'C_PINNED_TYPE_SELECTED'   => ($topic['type'] == '1'),
						'C_ANNOUNCE_TYPE_SELECTED' => ($topic['type'] == '2')
					));
				}

				//Récupération des infos du sondage associé si il existe
				$poll = array('question' => '', 'answers' => '', 'votes' => '', 'type' => '');
				try {
					$poll = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_poll', array('question', 'answers', 'votes', 'type'), 'WHERE idtopic=:id', array('id' => $idt_get));
				} catch (RowNotFoundException $e) {}

				$array_answer = explode('|', $poll['answers']);
				$array_votes = explode('|', $poll['votes']);

				$TmpTemplate = new FileTemplate('forum/forum_generic_results.tpl');
				$module_data_path = $TmpTemplate->get_pictures_data_path();

				//Affichage du lien pour changer le display_msg du topic et autorisation d'édition.
				if ($config->is_message_before_topic_title_displayed() && ($is_modo || AppContext::get_current_user()->get_id() == $topic['user_id']))
				{
					$img_display = $topic['display_msg'] ? 'fa fa-check success' : 'fa fa-times error';
					$bottom_view->put_all(array(
						'C_DISPLAY_ISSUE_STATUS' => true,
						'C_DISPLAY_ISSUE_ICON'   => $config->is_message_before_topic_title_icon_displayed(),

						'ISSUE_ICON'             => $img_display,

						'L_DEFAULT_ISSUE_STATUS' => $topic['display_msg'] ? $config->get_message_when_topic_is_solved() : $config->get_message_when_topic_is_unsolved(),
						'L_UNSOLVED_TOPIC'       => $config->get_message_when_topic_is_unsolved(),
						'L_SOLVED_TOPIC'         => $config->get_message_when_topic_is_solved()
					));
					$view->put_all(array(
						'C_DISPLAY_ISSUE_STATUS' => true,
						'C_DISPLAY_ISSUE_ICON'   => $config->is_message_before_topic_title_icon_displayed(),

						'ISSUE_ICON'             => $img_display,

						'L_DEFAULT_ISSUE_STATUS' => $topic['display_msg'] ? $config->get_message_when_topic_is_solved() : $config->get_message_when_topic_is_unsolved(),
						'L_UNSOLVED_TOPIC'       => $config->get_message_when_topic_is_unsolved(),
						'L_SOLVED_TOPIC'         => $config->get_message_when_topic_is_solved()
					));
				}

				//Liste des choix des sondages => 20 maxi
				$nbr_poll_field = 0;
				foreach ($array_answer as $key => $answer)
				{
					if (!empty($answer))
					{
						$nbr_votes = isset($array_votes[$key]) ? $array_votes[$key] : 0;
						$view->assign_block_vars('answers_poll', array(
							'ID'        => $nbr_poll_field,
							'ANSWER'    => stripslashes($answer),
							'NBR_VOTES' => $nbr_votes,

							'L_VOTES'   => ($nbr_votes > 1) ? $lang['forum.poll.votes'] : $lang['forum.poll.vote']
						));
						$nbr_poll_field++;
					}
				}
				for ($i = $nbr_poll_field; $i < 5; $i++) //On complète s'il y a moins de 5 réponses.
				{
					$view->assign_block_vars('answers_poll', array(
						'ID'     => $i,
						'ANSWER' => ''
					));
					$nbr_poll_field++;
				}

				$vars_tpl = array(
					'C_DELETE_POLL'          => $is_modo, //Suppression d'un sondage => modo uniquement.
					'C_ADD_POLL_FIELD'       => ($nbr_poll_field <= 19),
					'C_SIMPLE_POLL_SELECTED' => true,
					'C_DISPLAY_POLL'         => $poll['question'],

					'FORUM_NAME'             => $config->get_forum_name(),
					'CATEGORY_NAME'          => $category->get_name(),
					'TITLE'                  => stripslashes($topic['title']),
					'DESCRIPTION'            => stripslashes($topic['subtitle']),
					'CONTENT'                => FormatingHelper::unparse($content),
					'POLL_QUESTION'          => !empty($poll['question']) ? stripslashes($poll['question']) : '',
					'MODULE_DATA_PATH'       => $module_data_path,
					'IDTOPIC'                => $idt_get,
					'KERNEL_EDITOR'          => $editor->display(),
					'NBR_POLL_FIELD'         => $nbr_poll_field,

					'U_ACTION'               => 'post.php' . url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m . '&amp;token=' . AppContext::get_session()->get_token()),
					'U_CATEGORY'             => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),
					'U_TITLE_T'              => 'topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php'),

					'L_NEW_SUBJECT'          => stripslashes($topic['title']),
					'L_ACTION'               => $lang['forum.edit.topic'],
				);

				//Type de réponses du sondage.
				if (isset($poll['type']) && $poll['type'] == '0')
					$view->put('C_SIMPLE_POLL_SELECTED', true);
				elseif (isset($poll['type']) && $poll['type'] == '1')
					$view->put('C_MULTIPLE_POLL_SELECTED', true);

				$view->put_all($vars_tpl);

				$view->put('FORUM_TOP', $top_view->display());
				$view->display();
				$view->put('FORUM_BOTTOM', $bottom_view->display());
			}
		}
		//Sinon on édite simplement le message
		elseif ($id_m > $id_first)
		{
			$Bread_crumb->add($lang['forum.edit.message'], '');
			//User_id du message correspondant à l'utilisateur connecté => autorisation.
			$user_id_msg = 0;
			try {
				$user_id_msg = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'user_id', 'WHERE id = :id', array('id' => $id_m));
			} catch (RowNotFoundException $e) {}

			$check_auth = false;
			if ($user_id_msg == AppContext::get_current_user()->get_id())
				$check_auth = true;
			elseif ($is_modo)
				$check_auth = true;

			if (!$check_auth) //Non autorisé!
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}

			if ($update && (bool)retrieve(POST, 'edit_msg', false))
			{
				$content = retrieve(POST, 'content', '', TSTRING_AS_RECEIVED);
				if (!empty($content))
				{
					$nbr_msg_before = $Forumfct->Update_msg($idt_get, $id_m, addslashes($content), $user_id_msg);

					//Calcul de la page sur laquelle se situe le message.
					$msg_page = ceil( ($nbr_msg_before + 1) / $config->get_number_messages_per_page() );
					$msg_page_rewrite = ($msg_page > 1) ? '-' . $msg_page : '';
					$msg_page = ($msg_page > 1) ? '&pt=' . $msg_page : '';

					//Redirection après édition.
					AppContext::get_response()->redirect('/forum/topic' . url('.php?id=' . $idt_get . $msg_page, '-' . $idt_get .  $msg_page_rewrite . '.php', '&') . '#m' . $id_m);
				}
				else
					AppContext::get_response()->redirect('/forum/post' . url('.php?new=msg&idm=' . $id_m . '&id=' . $id_get . '&idt=' . $idt_get . '&errore=incomplete', '', '&') . '#message_helper');
			}
			else
			{
				$view = new FileTemplate('forum/forum_edit_msg.tpl');
				$view->add_lang($lang);

				$content = '';
				try {
					$content = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_msg", 'content', 'WHERE id = :id', array('id' => $id_m));
				} catch (RowNotFoundException $e) {}

				//Gestion des erreurs à l'édition.
				$get_error_e = retrieve(GET, 'errore', '');
				if ($get_error_e == 'incomplete')
					$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.incomplete'], MessageHelper::NOTICE));

				$vars_tpl = array(
					'P_UPDATE'       => url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m),
					'FORUM_NAME'     => $config->get_forum_name(),
					'CATEGORY_NAME'  => $category->get_name(),
					'DESCRIPTION'    => stripslashes($topic['subtitle']),
					'CONTENT'        => FormatingHelper::unparse($content),
					'KERNEL_EDITOR'  => $editor->display(),
					'TITLE_T'        => stripslashes($topic['title']),

					'U_ACTION'       => 'post.php' . url('?update=1&amp;new=msg&amp;id=' . $id_get . '&amp;idt=' . $idt_get . '&amp;idm=' . $id_m . '&amp;token=' . AppContext::get_session()->get_token()),
					'U_CATEGORY'     => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),
					'U_TITLE_T'      => 'topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php'),

					'L_NEW_SUBJECT'  => stripslashes($topic['title']),
				);

				$view->put_all($vars_tpl);

				$view->display();
				$view->put('FORUM_TOP', $top_view->display());
				$view->put('FORUM_BOTTOM', $bottom_view->display());
			}
		}
	}
	elseif (!empty($error_get) && (!empty($idt_get) || !empty($id_get)))
	{
		if (!empty($id_get) && !empty($idt_get) && ($error_get === 'flood' || $error_get === 'incomplete' || $error_get === 'locked'))
		{
			try {
				$topic = PersistenceContext::get_querier()->select_single_row(PREFIX . 'forum_topics', array('id_category', 'title', 'subtitle'), 'WHERE id=:id', array('id' => $idt_get));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}
			if (empty($topic['id_category'])) //Topic inexistant.
			{
				$controller = new UserErrorController($lang['warning.error'],
                    $lang['forum.error.non.existent.topic']);
                DispatchManager::redirect($controller);
			}

			$view = new FileTemplate('forum/forum_edit_msg.tpl');
			$view->add_lang($lang);

			//Gestion erreur.
			switch ($error_get)
			{
				case 'flood':
				$errstr = $lang['warning.flood'];
				$type = MessageHelper::WARNING;
				break;
				case 'incomplete':
				$errstr = $lang['warning.incomplete'];
				$type = MessageHelper::NOTICE;
				break;
				case 'locked':
				$errstr = $lang['forum.error.locked.topic'];
				$type = MessageHelper::WARNING;
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, $type));

			$vars_tpl = array(
				'P_UPDATE'       => '',
				'FORUM_NAME'     => $config->get_forum_name(),
				'CATEGORY_NAME'  => $category->get_name(),
				'DESCRIPTION'    => stripslashes($topic['subtitle']),
				'KERNEL_EDITOR'  => $editor->display(),
				'TITLE_T'        => stripslashes($topic['title']),

				'U_ACTION'       => 'post.php' . url('?new=n_msg&amp;idt=' . $idt_get . '&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_CATEGORY'     => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),
				'U_TITLE_T'      => 'topic' . url('.php?id=' . $idt_get, '-' . $idt_get . '.php'),

				'L_NEW_SUBJECT'  => stripslashes($topic['title']),
			);
		}
		elseif (!empty($id_get) && ($error_get === 'c_locked' || $error_get === 'c_write' || $error_get === 'incomplete_t' || $error_get === 'false_t'))
		{
			$view = new FileTemplate('forum/forum_post.tpl');
			$view->add_lang($lang);


			if (ForumAuthorizationsService::check_authorizations($id_get)->moderation())
			{
				$view->put_all(array(
					'C_FORUM_POST_TYPE' => true,
					'C_NORMAL_TYPE_SELECTED' => true
				));
			}

			//Gestion erreur.
			switch ($error_get)
			{
				case 'flood_t':
				$errstr = $lang['warning.flood'];
				$type = MessageHelper::WARNING;
				break;
				case 'incomplete_t':
				$errstr = $lang['warning.incomplete'];
				$type = MessageHelper::NOTICE;
				break;
				case 'c_locked':
				$errstr = $lang['forum.error.locked.category'];
				$type = MessageHelper::WARNING;
				break;
				case 'c_write':
				$errstr = $lang['forum.error.category.right'];
				$type = MessageHelper::WARNING;
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$view->put('MESSAGE_HELPER', MessageHelper::display($errstr, $type));

			//Liste des choix des sondages => 20 maxi
			$nbr_poll_field = 0;
			for ($i = 0; $i < 5; $i++)
			{
				$view->assign_block_vars('answers_poll', array(
					'ID'     => $i,
					'ANSWER' => ''
				));
				$nbr_poll_field++;
			}

			$vars_tpl = array(
				'C_ADD_POLL_FIELD'       => true,
				'C_SIMPLE_POLL_SELECTED' => true,
				'C_DISPLAY_POLL'         => false,

				'FORUM_NAME'             => $config->get_forum_name(),
				'CATEGORY_NAME'          => $category->get_name(),
				'TITLE'                  => '',
				'IDTOPIC'                => 0,
				'KERNEL_EDITOR'          => $editor->display(),
				'NBR_POLL_FIELD'         => $nbr_poll_field,

				'U_ACTION'               => 'post.php' . url('?new =topic&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_CATEGORY'             => 'forum' . url('.php?id=' . $id_get, '-' . $id_get . '.php'),
				'U_TITLE_T'              => 'post' . url('.php?new=topic&amp;id=' . $id_get),

				'L_ACTION'               => $lang['forum.new.topic'],
			);
		}
		else
		{
			$controller = PHPBoostErrors::unknow();
            DispatchManager::redirect($controller);
		}

		$view->put_all($vars_tpl);

		$view->put('FORUM_TOP', $top_view->display());
		$view->display();
		$view->put('FORUM_BOTTOM', $bottom_view->display());
	}
	else
	{
		$controller = PHPBoostErrors::unknow();
        DispatchManager::redirect($controller);
	}
}
else
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

include('../kernel/footer.php');

?>
