<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 08
 * @since       PHPBoost 3.0 - 2012 02 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserCommentsController extends AbstractController
{
	private $module;
	private $user;
	private $view;
	private $lang;
	private $current_user;
	private $comments_number;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$module_id = $request->get_getstring('module_id', '');
		$user_id = $request->get_getint('user_id', 0);

		if (!empty($user_id))
		{
			try {
				$this->user = UserService::get_user($user_id);
			} catch (Exception $e) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($user_id && !$this->user)
		{
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if (!empty($module_id))
		{
			$this->module = ModulesManager::get_module($module_id);
		}

		$this->init($request);

		return $this->build_response($request);
	}

	private function init($request)
	{
		$this->view = new FileTemplate('user/UserCommentsController.tpl');
		$this->lang = LangLoader::get_all_langs();
		$this->current_user = AppContext::get_current_user();
		$this->view->add_lang($this->lang);
		$this->view->put_all(array(
			'MODULE_CHOICE_FORM' => $this->build_modules_choice_form()->display(),
			'COMMENTS'           => $this->build_view($request)
		));

		if ($request->get_string('delete-selected-comments', false))
		{
			for ($i = 1 ; $i <= $this->comments_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
						CommentsManager::delete_comment($this->ids[$i]);
				}
			}
			$this->view->put('COMMENTS', $this->build_view($request));
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
		}
	}

	private function build_view($request)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');
		$template->add_lang($this->lang);
		$page = $request->get_getint('page', 1);

		$id_module = $this->module === null ? null : $this->module->get_id();
		$pagination = $this->get_pagination($page);

		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		$result = PersistenceContext::get_querier()->select('SELECT
			comments.*, comments.timestamp AS comment_timestamp, comments.id AS id_comment,
			topic.*,
			member.user_id, member.display_name, member.level, member.user_groups,
			ext_field.user_avatar
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = comments.user_id
			LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = comments.user_id
			'. $this->build_where_request() .'
			ORDER BY comments.timestamp DESC
			LIMIT :number_items_per_page OFFSET :display_from', array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		));

		$user_accounts_config = UserAccountsConfig::load();
		$comments_authorizations = new CommentsAuthorizations();

		$display_delete_button = false;
		$this->comments_number = 0;
		while ($row = $result->fetch())
		{
			$this->comments_number++;
			$id = $row['id_comment'];
			$path = $row['path'];
			$this->ids[$this->comments_number] = $id;

			if ($row['user_id'] == $this->current_user->get_id())
				$display_delete_button = true;

			$timestamp = new Date($row['comment_timestamp'], Timezone::SERVER_TIMEZONE);

			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$template->assign_block_vars('comments', array_merge(
				Date::get_array_tpl_vars($timestamp,'date'),
				array(
				'C_CURRENT_USER_MESSAGE' => $this->current_user->get_display_name() == $row['display_name'],
				'C_VISITOR'              => empty($row['display_name']),
				'C_VIEW_TOPIC'           => true,
				'C_GROUP_COLOR'          => !empty($group_color),
				'C_AVATAR'               => $row['user_avatar'] || $user_accounts_config->is_default_avatar_enabled(),
				'C_MODERATOR'            => $comments_authorizations->is_authorized_moderation() || $display_delete_button,

				'U_TOPIC'   => Url::to_rel($path),
				'U_EDIT'    => CommentsUrlBuilder::edit($path, $id)->rel(),
				'U_DELETE'  => CommentsUrlBuilder::delete($path, $id, REWRITED_SCRIPT)->rel(),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_AVATAR'  => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar(),

				'COMMENT_NUMBER' => $this->comments_number,
				'ID_COMMENT'     => $id,
				'MESSAGE'        => FormatingHelper::second_parse($row['message']),

				// User
				'USER_ID'     => $row['user_id'],
				'PSEUDO'      => empty($row['display_name']) ? $row['pseudo'] : $row['display_name'],
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'L_LEVEL'     => UserService::get_level_lang($row['level'] !== null ? $row['level'] : User::VISITOR_LEVEL)
			)));

			$template->put_all(array(
				'MODULE_ID'    => $row['module_id'],
				'ID_IN_MODULE' => $row['id_in_module'],
				'L_VIEW_TOPIC' => $this->lang['comment.view.topic']
			));
		}
		$result->dispose();

		$this->view->put_all(array(
			'C_COMMENTS'              => $this->comments_number > 0,
			'C_DISPLAY_DELETE_BUTTON' => $this->comments_number && ($comments_authorizations->is_authorized_moderation() || $display_delete_button),
			'COMMENTS_NUMBER'         => $this->comments_number
		));

		return $template;
	}

	private function get_pagination($page)
	{
		$id_module = $this->module === null ? null : $this->module->get_id();
		$user_id = $this->user !== null ? $this->user->get_id() : null;

		$row = PersistenceContext::get_querier()->select('SELECT COUNT(*) AS nbr_comments
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic
			'. $this->build_where_request())->fetch();

		$pagination = new ModulePagination($page, $row['nbr_comments'], (int)CommentsConfig::load()->get_comments_number_display());
		$pagination->set_url(UserUrlBuilder::comments($id_module, $user_id, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_where_request()
	{
		$where = 'WHERE ';
		if ($this->module !== null && $this->user !== null)
		{
			$where .= 'topic.module_id = \'' . $this->module->get_id() . '\' AND comments.user_id = \'' . $this->user->get_id() . '\'';
		}
		elseif ($this->module !== null)
		{
			$where .= 'topic.module_id = \'' . $this->module->get_id() . '\'';
		}
		elseif ($this->user !== null)
		{
			$where .= ' comments.user_id = \'' . $this->user->get_id() . '\'';
		}
		else
		{
			$where .= '1';
		}
		return $where;
	}

	private function build_modules_choice_form()
	{
		$selected = $this->module !== null ? $this->module->get_id() : '';
		$user_id = $this->user !== null ? $this->user->get_id() : null;
		$form = new HTMLForm('ModuleChoice', '', false);
		$fieldset = new FormFieldsetHTML('ModuleChoice', $this->lang['common.filters']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldSimpleSelectChoice('module', $this->lang['common.sort'] . ' : ', $selected, $this->build_select(),
			array('events' => array('change' => 'document.location = "'. UserUrlBuilder::comments('', $user_id)->rel() .'" + HTMLForms.getField("module").getValue();'))
		));
		return $form;
	}

	private function build_select()
	{
		$modules = array(new FormFieldSelectChoiceOption($this->lang['comment.see.all.comments'], ''));
		$comments_config = CommentsConfig::load();

		foreach (ModulesManager::get_activated_feature_modules('comments') as $module)
		{
			if ($comments_config->module_comments_is_enabled($module->get_id()))
			{
				$modules[] = new FormFieldSelectChoiceOption($module->get_configuration()->get_name(), $module->get_id());
			}
		}
		return $modules;
	}

	private function build_response($request)
	{
		$module_id = $request->get_getstring('module_id', '');
		$page = $request->get_getint('page', 1);

		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();

		if ($this->user !== null)
			$graphical_environment->set_page_title($this->user->get_display_name(), $this->lang['comment.comments'], $page);
		else
			$graphical_environment->set_page_title($this->lang['comment.comments'], '', $page);

		$graphical_environment->get_seo_meta_data()->set_description($this->user !== null ? StringVars::replace_vars($this->lang['user.seo.comments.user'], array('name' => $this->user->get_display_name())) : $this->lang['user.seo.comments']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::comments($module_id, $this->user !== null ? $this->user->get_id() : null, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();

		if ($this->user !== null)
		{
			$breadcrumb->add($this->user->get_display_name(), UserUrlBuilder::profile($this->user->get_id())->rel());
			$breadcrumb->add($this->lang['user.publications'], UserUrlBuilder::publications($this->user->get_id())->rel());
			$breadcrumb->add($this->lang['comment.comments'], UserUrlBuilder::comments($module_id, $this->user->get_id(), $page)->rel());
		}
		else
		{
			$breadcrumb->add($this->lang['user.users'], UserUrlBuilder::home()->rel());
			$breadcrumb->add($this->lang['comment.comments'], UserUrlBuilder::comments()->rel());
		}

		return $response;
	}

	public function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->check_auth(CommentsConfig::load()->get_authorizations(), CommentsAuthorizations::READ_AUTHORIZATIONS))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		return $this;
	}
}
?>
