<?php
/*##################################################
 *                       UserCommentsController.class.php
 *                            -------------------
 *   begin                : February 20, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class UserCommentsController extends AbstractController
{
	private $module;
	private $user;
	private $tpl;
	private $lang;
	private $number_comments_per_page = 15;
	
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
		
		if (!empty($module_id))
		{
			$this->module = ModulesManager::get_module($module_id);
		}
		
		$this->init($request);

		return $this->build_response();
	}
	
	private function init($request)
	{
		$this->tpl = new FileTemplate('user/UserCommentsController.tpl');
		$this->lang = LangLoader::get('comments-common');
		$this->tpl->add_lang($this->lang);
		$this->tpl->put('MODULE_CHOICE_FORM', $this->build_modules_choice_form()->display());
		$this->tpl->put('COMMENTS', $this->build_view($request));
	}
	
	private function build_view($request)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');
		$page = $request->get_getint('page', 1);
		
		$id_module = $this->module === null ? null : $this->module->get_id();
		$pagination = $this->get_pagination($page);
		
		$this->tpl->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));
		
		$result = PersistenceContext::get_querier()->select('
			SELECT comments.*, comments.timestamp AS comment_timestamp, comments.id AS id_comment,
			topic.*,
			member.user_id, member.display_name, member.level, member.groups,
			ext_field.user_avatar
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = comments.user_id
			LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = comments.user_id
			'. $this->build_where_request() .'
			ORDER BY comments.timestamp DESC
			LIMIT :number_items_per_page OFFSET :display_from'
		, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		$user_accounts_config = UserAccountsConfig::load();
		$comments_authorizations = new CommentsAuthorizations();
		
		$number_comment = 0;
		while ($row = $result->fetch())
		{
			$id = $row['id_comment'];
			$path = $row['path'];
			
			//Avatar
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
			
			$timestamp = new Date($row['comment_timestamp'], Timezone::SERVER_TIMEZONE);

			$group_color = User::get_group_color($row['groups'], $row['level']);
			
			$template->assign_block_vars('comments', array(
				'C_MODERATOR' => $comments_authorizations->is_authorized_moderation(),
				'C_VISITOR' => empty($row['login']),
				'C_VIEW_TOPIC' => true,
				'C_GROUP_COLOR' => !empty($group_color),
				'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
				
				'U_TOPIC' => Url::to_rel($path),
				'U_EDIT' => CommentsUrlBuilder::edit($path, $id)->rel(),
				'U_DELETE' => CommentsUrlBuilder::delete($path, $id)->rel(),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_AVATAR' => $user_avatar,
				
				'ID_COMMENT' => $id,
				'DATE' => $timestamp->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'DATE_ISO8601' => $timestamp->format(Date::FORMAT_ISO8601),
				'MESSAGE' => FormatingHelper::second_parse($row['message']),
					
				// User
				'USER_ID' => $row['user_id'],
				'PSEUDO' => empty($row['login']) ? $row['pseudo'] : $row['login'],
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				
				'L_LEVEL' => UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1')
			));
			
			$template->put_all(array(
				'MODULE_ID' => $row['module_id'],
				'ID_IN_MODULE' => $row['id_in_module'],
				'L_VIEW_TOPIC' => $this->lang['view-topic']
			));
			$number_comment++;
		}
		$result->dispose();
		
		$this->tpl->put('C_NO_COMMENT', $number_comment == 0);
		
		$comments_tpl = new FileTemplate('framework/content/comments/comments.tpl');
		$comments_tpl->put_all(array(
			'COMMENTS_LIST' => $template,
			'MODULE_ID' => $row['module_id'],
			'ID_IN_MODULE' => $row['id_in_module']
		));
		return $comments_tpl;
	}
	
	private function get_pagination($page)
	{
		$id_module = $this->module === null ? null : $this->module->get_id();
		$user_id = $this->user !== null ? $this->user->get_id() : null;
		
		$row = PersistenceContext::get_querier()->select('SELECT COUNT(*) AS nbr_comments
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic 
			'. $this->build_where_request())->fetch();
		
		$pagination = new ModulePagination($page, $row['nbr_comments'], $this->number_comments_per_page);
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
		$fieldset = new FormFieldsetHTML('ModuleChoice', $this->lang['comments']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldSimpleSelectChoice('module', LangLoader::get_message('sort', 'common') . ' : ', $selected, $this->build_select(), 
		array('events' => array('change' => 'document.location = "'. UserUrlBuilder::comments('', $user_id)->rel() .'" + HTMLForms.getField("module").getValue();'))));
		return $form;
	}
	
	private function build_select()
	{
		$extensions_point = array_keys(CommentsProvidersService::get_extension_point());
		$modules = array(new FormFieldSelectChoiceOption(LangLoader::get_message('view_all_comments', 'admin'), ''));
		
		foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $module)
		{
			if (in_array($module->get_id(), $extensions_point))
			{
				$modules[] = new FormFieldSelectChoiceOption($module->get_configuration()->get_name(), $module->get_id());
			}
		}
		return $modules;
	}
	
	private function build_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['comments']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();

		if ($this->user !== null)
		{
			$breadcrumb->add($this->user->get_display_name(), UserUrlBuilder::profile($this->user->get_id())->rel());
			$breadcrumb->add(LangLoader::get_message('messages', 'user-common'), UserUrlBuilder::messages($this->user->get_id())->rel());
			$breadcrumb->add($this->lang['comments'], UserUrlBuilder::comments('', $this->user->get_id())->rel());
		}
		else
		{
			$breadcrumb->add(LangLoader::get_message('users', 'user-common'), UserUrlBuilder::home()->rel());
			$breadcrumb->add($this->lang['comments'], UserUrlBuilder::comments()->rel());
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
