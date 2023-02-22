<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 23
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryHook extends Hook
{
	/**
	 * {@inheritdoc}
	 */
	public function on_add_action($module_id, array $properties, $description = '')
	{
		if (!in_array('items', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('add', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_edit_action($module_id, array $properties, $description = '')
	{
		if (!in_array('items', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('edit', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_delete_action($module_id, array $properties, $description = '')
	{
		if (!in_array('items', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('delete', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_add_category_action($module_id, array $properties, $description = '')
	{
		if (!in_array('categories', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('add_category', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_edit_category_action($module_id, array $properties, $description = '')
	{
		if (!in_array('categories', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('edit_category', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_delete_category_action($module_id, array $properties, $description = '')
	{
		if (!in_array('categories', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('delete_category', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_add_contribution_action($module_id, array $properties, $description = '')
	{
		if (!in_array('contributions', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('add_contribution', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_edit_contribution_action($module_id, array $properties, $description = '')
	{
		if (!in_array('contributions', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('edit_contribution', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_delete_contribution_action($module_id, array $properties, $description = '')
	{
		if (!in_array('contributions', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('delete_contribution', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_process_contribution_action($module_id, array $properties, $description = '')
	{
		if (!in_array('contributions', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('process_contribution', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_warning_action($user_id, array $properties, $description = '')
	{
		if (!in_array('moderation', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_warning', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_punishment_action($user_id, array $properties, $description = '')
	{
		if (!in_array('moderation', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_punishment', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_ban_action($user_id, array $properties, $description = '')
	{
		if (!in_array('moderation', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_ban', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_change_level_action($user_id, array $properties, $description = '')
	{
		if (!in_array('moderation', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_change_level', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_change_email_action($user_id, array $properties, $description = '')
	{
		if (!in_array('users', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_change_email', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_change_display_name_action($user_id, array $properties, $description = '')
	{
		if (!in_array('users', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_change_display_name', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_user_registration_action($user_id, array $properties, $description = '')
	{
		if (!in_array('users', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('user_registration', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_add_user_action($user_id, array $properties, $description = '')
	{
		if (!in_array('users', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('add_user', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_delete_user_action($user_id, array $properties, $description = '')
	{
		if (!in_array('users', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('delete_user', $user_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_add_comment_action($module_id, array $properties, $description = '')
	{
		if (!in_array('comments', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('add_comment', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_edit_comment_action($module_id, array $properties, $description = '')
	{
		if (!in_array('comments', HistoryConfig::load()->get_history_topics_disabled()))
		{
			$comment_properties = CommentsCache::load()->get_comment($properties['id']);
			return $this->add_history_entry('edit_comment', $module_id, ($comment_properties ? array_merge($comment_properties, $properties) : $properties), $description);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_delete_comment_action($module_id, array $properties, $description = '')
	{
		if (!in_array('comments', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('delete_comment', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_notation_action($module_id, array $properties, $description = '')
	{
		if (!in_array('notation', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('notation', $module_id, $properties, (!empty($description) ? $description : (LangLoader::get_message('common.note', 'common-lang') . ' : ' . $properties['note'] . '/' . $properties['notation_scale'])));
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_edit_config_action($module_id, array $properties = array(), $description = '')
	{
		if (!in_array('config', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('edit_config', $module_id, $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_install_action($installation_type, $element_id, array $properties, $description = '')
	{
		if (!in_array('config', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('install_' . $installation_type, 'kernel', $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_uninstall_action($uninstallation_type, $element_id, array $properties, $description = '')
	{
		if (!in_array('config', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('uninstall_' . $uninstallation_type, 'kernel', $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function on_update_action($upgrade_type, $element_id, array $properties, $description = '')
	{
		if (!in_array('config', HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry('update_' . $upgrade_type, 'kernel', $properties, $description);
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute_module_specific_hook_action($action, $module_id, array $properties, $description = '')
	{
		if (!in_array($module_id, HistoryConfig::load()->get_history_topics_disabled()))
			return $this->add_history_entry($action, $module_id, $properties, $description);
	}
	
	private function add_history_entry($action, $module_id, array $properties, $description = '')
	{
		if (ModulesManager::is_module_activated('history') && !in_array($module_id, HistoryConfig::load()->get_disabled_modules()))
		{
			$now = new Date();
			$module = ModulesManager::get_module($module_id);
			$id_in_module = (isset($properties['id_in_module']) ? $properties['id_in_module'] : (isset($properties['id']) ? $properties['id'] : 0));
			$title = (isset($properties['title']) ? $properties['title'] : (isset($properties['name']) ? $properties['name'] : ''));
			$url = isset($properties['item_url']) ? $properties['item_url'] : (isset($properties['url']) ? $properties['url'] : '');
			
			if (in_array($action, array('add_category', 'edit_category', 'delete_category')) && !$url && isset($properties['id']) && isset($properties['rewrited_name']))
				$url = Url::to_rel('/' . $module_id . '/' . $properties['id'] . '-' . $properties['rewrited_name'] . '/');
			
			$item_manager_class = ucfirst($module_id) . 'Manager';
			$item_service_class = ucfirst($module_id) . 'Service';
			$item_class = ucfirst($module_id) . 'Item';
			
			if ($module && $module->get_configuration()->has_items())
			{
				$item_class = $module->get_configuration()->get_item_name();
				$manager_class = ClassLoader::is_class_registered_and_valid($item_manager_class) ? $item_manager_class : 'ItemsManager';
			}
			else
				$manager_class = ClassLoader::is_class_registered_and_valid($item_manager_class) ? $item_manager_class : (ClassLoader::is_class_registered_and_valid($item_service_class) ? $item_service_class : '');
			
			if ($manager_class && $id_in_module && (!$title || !$url))
			{
				if ($action != 'delete' && method_exists($manager_class, 'get_item'))
				{
					if (ClassLoader::is_class_registered_and_valid($item_class) && is_subclass_of($item_class, 'Item'))
					{
						$manager = new $manager_class($module_id);
						
						try {
							$item = $manager->get_item($id_in_module);
						} catch (RowNotFoundException $e) {
							$item = '';
						}
					}
					else
						$item = $manager_class::get_item($id_in_module);
					
					if ($item && !$title && method_exists($item_class, 'get_title'))
						$title = $item->get_title();
					if ($item && !$url && method_exists($item_class, 'get_item_url'))
						$url = $item->get_item_url();
				}
			}
			
			$parameters = array(
				'module_id'     => $module_id,
				'id_in_module'  => $id_in_module,
				'user_id'       => AppContext::get_current_user()->get_id(),
				'creation_date' => $now->get_timestamp(),
				'action'        => $action,
				'title'         => $title,
				'url'           => $url,
				'description'   => $description
			);
			
			if (!HistoryManager::count('WHERE module_id = :module_id AND action = :action and creation_date = :creation_date and title = :title and user_id = :user_id', $parameters))
				return HistoryManager::add($parameters);
		}
		return false;
	}
}
?>
