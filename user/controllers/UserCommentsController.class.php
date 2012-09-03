<?php
/*##################################################
 *                       UserCommentsController.class.php
 *                            -------------------
 *   begin                : February 20, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
	
	public function execute(HTTPRequestCustom $request)
	{
		$module_id = $request->get_getstring('module_id', '');
		$user_id = $request->get_getint('user_id', 0);
		
		if (!empty($user_id))
		{
			try {
				$this->user = UserService::get_user('WHERE user_id=:user_id', array('user_id' => $user_id));
			} catch (Exception $e) {
				$error_controller = PHPBoostErrors::unexisting_member();
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
		$user_id = $this->user === null ? null : $this->user->get_id();
		$pagination = new UserCommentsListPagination($page, $id_module, $user_id);
		
		$this->tpl->put_all(array(
			'PAGINATION' => '&nbsp;<strong>' . LangLoader::get_message('page', 'main') . ' :</strong> ' . $pagination->display()->render()
		));
		
		$result = PersistenceContext::get_querier()->select('
			SELECT comments.*, comments.timestamp AS comment_timestamp, comments.id AS id_comment,
			topic.*,
			member.user_id, member.login, member.level, member.user_groups,
			ext_field.user_avatar
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = comments.user_id
			LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = comments.user_id
			'. $this->build_where_request() .'
			ORDER BY comments.timestamp ' . CommentsConfig::load()->get_order_display_comments() .'
			LIMIT :number_comments_per_page OFFSET :display_from'
		, array(
			'number_comments_per_page' => $pagination->get_number_comments_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		$user_accounts_config = UserAccountsConfig::load();
		$comments_authorizations = new CommentsAuthorizations();
		
		$number_comment = 0;
		while ($row = $result->fetch())
		{
			$id = $row['id_comment'];
			$path = PATH_TO_ROOT . $row['path'];
			
			if (empty($row['user_avatar']))
				$user_avatar = $user_accounts_config->is_default_avatar_enabled() == '1' ? Url::to_rel(PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '';
			else
				$user_avatar = Url::to_rel($row['user_avatar']);
			
			$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_SITE, $row['comment_timestamp']);

			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$template->assign_block_vars('comments', array(
				'C_MODERATOR' => $comments_authorizations->is_authorized_moderation(),
				'C_VISITOR' => empty($row['login']),
			
				'U_EDIT' => CommentsUrlBuilder::edit($path, $id)->absolute(),
				'U_DELETE' => CommentsUrlBuilder::delete($path, $id)->absolute(),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
				'U_AVATAR' => $user_avatar,
				
				'ID_COMMENT' => $id,
				'DATE' => $timestamp->format(DATE_FORMAT, TIMEZONE_AUTO),
				'MESSAGE' => FormatingHelper::second_parse($row['message']),
					
				// User
				'USER_ID' => $row['user_id'],
				'PSEUDO' => empty($row['login']) ? $row['pseudo'] : $row['login'],
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				
				'L_LEVEL' => UserService::get_level_lang(!empty($row['level']) ? $row['level'] : '-1'),
			));
			
			$template->put_all(array(
				'MODULE_ID' => $row['module_id'],
				'ID_IN_MODULE' => $row['id_in_module']
			));
			$number_comment++;
		}
		
		$this->tpl->put('C_NO_COMMENT', $number_comment == 0);
		
		$comments_tpl = new FileTemplate('framework/content/comments/comments.tpl');
		$comments_tpl->put_all(array(
			'COMMENTS_LIST' => $template,
			'MODULE_ID' => $row['module_id'],
			'ID_IN_MODULE' => $row['id_in_module']
		));
		return $comments_tpl;
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
		$form = new HTMLForm('ModuleChoice');
		$fieldset = new FormFieldsetHTML('ModuleChoice', $this->lang['module.choice']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldSimpleSelectChoice('module', $this->lang['module.choice'] . ' : ', $selected, $this->build_select(), 
		array('events' => array('change' => 'document.location = "'. UserUrlBuilder::comments('', $user_id)->absolute() .'" + HTMLForms.getField("module").getValue();'))));
		return $form;
	}
	
	private function build_select()
	{
		$extensions_point = CommentsProvidersService::get_extension_point();
		$modules = array(new FormFieldSelectChoiceOption(LangLoader::get_message('view_all_comments', 'admin'), ''));
		foreach (ModulesManager::get_activated_modules_map() as $id => $module)
		{
			if (array_key_exists($id, $extensions_point))
			{
				$modules[] = new FormFieldSelectChoiceOption($module->get_configuration()->get_name(), $id);
			}
		}
		return $modules;
	}
	
	private function build_response()
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['comments']);
		
		if ($this->user !== null)
		{
			$response->add_breadcrumb($this->user->get_pseudo(), UserUrlBuilder::profile($this->user->get_id())->absolute());
			$response->add_breadcrumb(LangLoader::get_message('messages', 'user-common'), UserUrlBuilder::messages($this->user->get_id())->absolute());
			$response->add_breadcrumb($this->lang['comments'], UserUrlBuilder::comments('', $this->user->get_id())->absolute());
		}
		else
		{
			$response->add_breadcrumb(LangLoader::get_message('users', 'user-common'), UserUrlBuilder::users()->absolute());
			$response->add_breadcrumb($this->lang['comments'], UserUrlBuilder::comments()->absolute());
		}
		return $response->display($this->tpl);
	}
}
?>