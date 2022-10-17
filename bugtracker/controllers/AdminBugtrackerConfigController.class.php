<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 10 18
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminBugtrackerConfigController extends DefaultAdminModuleController
{
	private $max_input = 500;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('admin_alerts_levels')->set_hidden(!$this->config->are_admin_alerts_enabled());
			$this->form->get_field_by_id('admin_alerts_fix_action')->set_hidden(!$this->config->are_admin_alerts_enabled());

			foreach ($this->config->get_status_list() as $key => $value)
			{
				$this->form->get_field_by_id($key)->set_hidden(!$this->config->is_progress_bar_displayed());
			}

			$this->form->get_field_by_id('stats_top_posters_enabled')->set_hidden(!$this->config->are_stats_enabled());
			$this->form->get_field_by_id('stats_top_posters_number')->set_hidden(!$this->config->are_stats_top_posters_enabled());
			$this->form->get_field_by_id('pm_edit_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_delete_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_comment_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_in_progress_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_pending_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_assign_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_fix_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_reject_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('pm_reopen_enabled')->set_hidden(!$this->config->are_pm_enabled());
			$this->form->get_field_by_id('types_table')->set_value($this->build_types_table()->render());
			$this->form->get_field_by_id('categories_table')->set_value($this->build_categories_table()->render());
			$this->form->get_field_by_id('severities_table')->set_value($this->build_severities_table()->render());
			$this->form->get_field_by_id('priorities_table')->set_value($this->build_priorities_table()->render());
			$this->form->get_field_by_id('versions_table')->set_value($this->build_versions_table()->render());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminBugtrackerDisplayResponse($this->view, StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$severities = $this->config->get_severities();

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], (int)$this->config->get_items_per_page(),
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldColorPicker('fixed_bug_color', $this->lang['config.fixed_bug_color_label'], $this->config->get_fixed_bug_color(),
			array(),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`iu'))
		));

		$fieldset->add_field(new FormFieldColorPicker('rejected_bug_color', $this->lang['config.rejected_bug_color_label'], $this->config->get_rejected_bug_color(),
			array(),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`iu'))
		));

		$fieldset->add_field(new FormFieldCheckbox('roadmap_enabled', $this->lang['config.enable_roadmap'], $this->config->is_roadmap_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['explain.roadmap']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('restrict_display_to_own_elements_enabled', $this->lang['config.restrict_display_to_own_elements_enabled'], $this->config->is_restrict_display_to_own_elements_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['config.restrict_display_to_own_elements_enabled.explain']
			)
		));

		$fieldset = new FormFieldsetHTML('progress_bar', $this->lang['config.progress_bar']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('progress_bar_displayed', $this->lang['config.enable_progress_bar'], $this->config->is_progress_bar_displayed(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("progress_bar_displayed").getValue()) {
						HTMLForms.getField("' . BugtrackerItem::NEW_BUG . '").enable();
						HTMLForms.getField("' . BugtrackerItem::PENDING . '").enable();
						HTMLForms.getField("' . BugtrackerItem::ASSIGNED . '").enable();
						HTMLForms.getField("' . BugtrackerItem::IN_PROGRESS . '").enable();
						HTMLForms.getField("' . BugtrackerItem::REJECTED . '").enable();
						HTMLForms.getField("' . BugtrackerItem::REOPEN . '").enable();
						HTMLForms.getField("' . BugtrackerItem::FIXED . '").enable();
					} else {
						HTMLForms.getField("' . BugtrackerItem::NEW_BUG . '").disable();
						HTMLForms.getField("' . BugtrackerItem::PENDING . '").disable();
						HTMLForms.getField("' . BugtrackerItem::ASSIGNED . '").disable();
						HTMLForms.getField("' . BugtrackerItem::IN_PROGRESS . '").disable();
						HTMLForms.getField("' . BugtrackerItem::REJECTED . '").disable();
						HTMLForms.getField("' . BugtrackerItem::REOPEN . '").disable();
						HTMLForms.getField("' . BugtrackerItem::FIXED . '").disable();
					}'
				)
			)
		));

		foreach ($this->config->get_status_list() as $key => $value)
		{
			$fieldset->add_field(new FormFieldNumberEditor($key, $this->lang['config.status.' . $key], $value, array(
				'min' => 0, 'max' => 100, 'step' => 10, 'required' => true, 'hidden' => !$this->config->is_progress_bar_displayed()),
				array(new FormFieldConstraintIntegerRange(0, 100))
			));
		}

		$fieldset = new FormFieldsetHTML('admin_alerts', $this->lang['config.admin_alerts']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('admin_alerts_enabled', $this->lang['config.enable_admin_alerts'], $this->config->are_admin_alerts_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("admin_alerts_enabled").getValue()) {
						HTMLForms.getField("admin_alerts_fix_action").enable();
						HTMLForms.getField("admin_alerts_levels").enable();
					} else {
						HTMLForms.getField("admin_alerts_fix_action").disable();
						HTMLForms.getField("admin_alerts_levels").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldMultipleCheckbox('admin_alerts_levels', $this->lang['config.admin_alerts_levels'], $this->config->get_admin_alerts_levels(), $this->build_admin_alerts_levels($severities),
			array(
				'class' => 'top-field mini-checkbox inline-checkbox',
				'hidden' => !$this->config->are_admin_alerts_enabled()
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('admin_alerts_fix_action', $this->lang['config.admin_alerts_fix_action'], $this->config->get_admin_alerts_fix_action(),
			array(
				new FormFieldSelectChoiceOption($this->lang['labels.alert_fix'], BugtrackerConfig::FIX),
				new FormFieldSelectChoiceOption($this->lang['labels.alert_delete'], BugtrackerConfig::DELETE)
			),
			array(
				'class' => 'top-field',
				'hidden' => !$this->config->are_admin_alerts_enabled()
			)
		));

		$fieldset = new FormFieldsetHTML('stats', $this->lang['titles.stats']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('stats_enabled', $this->lang['config.enable_stats'], $this->config->are_stats_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("stats_enabled").getValue()) {
						HTMLForms.getField("stats_top_posters_enabled").enable();
						HTMLForms.getField("stats_top_posters_number").enable();
					} else {
						HTMLForms.getField("stats_top_posters_enabled").disable();
						HTMLForms.getField("stats_top_posters_number").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('stats_top_posters_enabled', $this->lang['config.enable_stats_top_posters'], $this->config->are_stats_top_posters_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_stats_enabled(),
				'events' => array('click' => '
					if (HTMLForms.getField("stats_top_posters_enabled").getValue()) {
						HTMLForms.getField("stats_top_posters_number").enable();
					} else {
						HTMLForms.getField("stats_top_posters_number").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('stats_top_posters_number', $this->lang['config.stats_top_posters_number'], (int)$this->config->get_stats_top_posters_number(), array(
			'min' => 1, 'required' => true, 'hidden' => !$this->config->are_stats_top_posters_enabled()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset = new FormFieldsetHTML('pm', $this->lang['config.pm']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('pm_enabled', $this->lang['config.enable_pm'], $this->config->are_pm_enabled() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("pm_enabled").getValue()) {
						HTMLForms.getField("pm_edit_enabled").enable();
						HTMLForms.getField("pm_delete_enabled").enable();
						HTMLForms.getField("pm_comment_enabled").enable();
						HTMLForms.getField("pm_in_progress_enabled").enable();
						HTMLForms.getField("pm_pending_enabled").enable();
						HTMLForms.getField("pm_assign_enabled").enable();
						HTMLForms.getField("pm_fix_enabled").enable();
						HTMLForms.getField("pm_reject_enabled").enable();
						HTMLForms.getField("pm_reopen_enabled").enable();
					} else {
						HTMLForms.getField("pm_edit_enabled").disable();
						HTMLForms.getField("pm_delete_enabled").disable();
						HTMLForms.getField("pm_comment_enabled").disable();
						HTMLForms.getField("pm_in_progress_enabled").disable();
						HTMLForms.getField("pm_pending_enabled").disable();
						HTMLForms.getField("pm_assign_enabled").disable();
						HTMLForms.getField("pm_fix_enabled").disable();
						HTMLForms.getField("pm_reject_enabled").disable();
						HTMLForms.getField("pm_reopen_enabled").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_edit_enabled', $this->lang['config.enable_pm.edit'], $this->config->are_pm_edit_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_delete_enabled', $this->lang['config.enable_pm.delete'], $this->config->are_pm_delete_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_comment_enabled', $this->lang['config.enable_pm.comment'], $this->config->are_pm_comment_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_in_progress_enabled', $this->lang['config.enable_pm.in_progress'], $this->config->are_pm_in_progress_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_pending_enabled', $this->lang['config.enable_pm.pending'], $this->config->are_pm_pending_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_assign_enabled', $this->lang['config.enable_pm.assign'], $this->config->are_pm_assign_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_fix_enabled', $this->lang['config.enable_pm.fix'], $this->config->are_pm_fix_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_reject_enabled', $this->lang['config.enable_pm.reject'], $this->config->are_pm_reject_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('pm_reopen_enabled', $this->lang['config.enable_pm.reopen'], $this->config->are_pm_reopen_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->config->are_pm_enabled()
			)
		));

		$fieldset = new FormFieldsetHTML('content-value', $this->lang['titles.content_value_title']);
		$fieldset->set_description($this->lang['explain.content_value']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('content_value', $this->lang['titles.content_value'], $this->config->get_content_value(),
			array('rows' => 8, 'cols' => 47)
		));

		$fieldset = new FormFieldsetHTML('types-fieldset', $this->lang['titles.types']);
		$fieldset->set_description($this->lang['explain.type'] . '<br /><br />' . $this->lang['explain.remarks']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('type_mandatory', $this->lang['labels.type_mandatory'], $this->config->is_type_mandatory(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_type_column', $this->lang['config.display_type_column'], $this->config->is_type_column_displayed(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldHTML('types_table', $this->build_types_table()->render(), array('class' => 'full-field')));

		$fieldset = new FormFieldsetHTML('categories-fieldset', $this->lang['titles.categories']);
		$fieldset->set_description($this->lang['explain.category'] . '<br /><br />' . $this->lang['explain.remarks']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('category_mandatory', $this->lang['labels.category_mandatory'], $this->config->is_category_mandatory(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_category_column', $this->lang['config.display_category_column'], $this->config->is_category_column_displayed(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldHTML('categories_table', $this->build_categories_table()->render(), array('class' => 'full-field')));

		$fieldset = new FormFieldsetHTML('severities-fieldset', $this->lang['titles.severities']);
		$fieldset->set_description($this->lang['explain.severity'] . '<br /><br />' . $this->lang['explain.remarks']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('severity_mandatory', $this->lang['labels.severity_mandatory'], $this->config->is_severity_mandatory(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldHTML('severities_table', $this->build_severities_table()->render(), array('class' => 'full-field')));

		$fieldset = new FormFieldsetHTML('priorities-fieldset', $this->lang['titles.priorities']);
		$fieldset->set_description($this->lang['explain.priority'] . '<br /><br />' . $this->lang['explain.remarks']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('priority_mandatory', $this->lang['labels.priority_mandatory'], $this->config->is_priority_mandatory(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_priority_column', $this->lang['config.display_priority_column'], $this->config->is_priority_column_displayed(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldHTML('priorities_table', $this->build_priorities_table()->render(), array('class' => 'full-field')));

		$fieldset = new FormFieldsetHTML('versions-fieldset', $this->lang['titles.versions']);
		$fieldset->set_description($this->lang['explain.version'] . '<br /><br />' . $this->lang['explain.remarks']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('detected_in_version_mandatory', $this->lang['labels.detected_in_mandatory'], $this->config->is_detected_in_version_mandatory(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_detected_in_column', $this->lang['config.display_detected_in_column'], $this->config->is_detected_in_column_displayed(),
			array('class' => 'custom-checkbox half-field')
		));

		$fieldset->add_field(new FormFieldHTML('versions_table', $this->build_versions_table()->render(), array('class' => 'full-field')));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_admin_alerts_levels($severities)
	{
		$list = array();

		foreach ($severities as $key => $severity)
		{
			$list[] = new FormFieldMultipleCheckboxOption($key, stripslashes($severity['name']));
		}

		return $list;
	}

	private function build_types_table()
	{
		$types = $this->config->get_types();

		$types_table = new FileTemplate('bugtracker/AdminBugtrackerTypesListController.tpl');
		$types_table->add_lang($this->lang);

		$key = 0;
		foreach ($types as $key => $type)
		{
			$types_table->assign_block_vars('types', array(
				'C_IS_DEFAULT' => $this->config->get_default_type() == $key,
				'ID'		   => $key,
				'NAME'		   => stripslashes($type),
				'LINK_DELETE'  => BugtrackerUrlBuilder::delete_parameter('type', $key)->rel()
			));
		}

		$types_table->put_all(array(
			'C_TYPES'						  => !empty($types),
			'C_DISPLAY_DEFAULT_DELETE_BUTTON' => $this->config->get_default_type(),
			'MAX_INPUT'						  => $this->max_input,
			'NEXT_ID'						  => $key + 1,
			'LINK_DELETE_DEFAULT'			  => BugtrackerUrlBuilder::delete_default_parameter('type')->rel()
		));

		return $types_table;
	}

	private function build_categories_table()
	{
		$categories = $this->config->get_categories();

		$categories_table = new FileTemplate('bugtracker/AdminBugtrackerCategoriesListController.tpl');
		$categories_table->add_lang($this->lang);

		$key = 0;
		foreach ($categories as $key => $category)
		{
			$categories_table->assign_block_vars('categories', array(
				'C_IS_DEFAULT' => $this->config->get_default_category() == $key,
				'ID'		   => $key,
				'NAME'		   => stripslashes($category),
				'LINK_DELETE'  => BugtrackerUrlBuilder::delete_parameter('category', $key)->rel()
			));
		}

		$categories_table->put_all(array(
			'C_CATEGORIES'					  => !empty($categories),
			'MAX_INPUT'						  => $this->max_input,
			'NEXT_ID'						  => $key + 1,
			'C_DISPLAY_DEFAULT_DELETE_BUTTON' => $this->config->get_default_category(),
			'LINK_DELETE_DEFAULT'			  => BugtrackerUrlBuilder::delete_default_parameter('category')->rel()
		));

		return $categories_table;
	}

	private function build_severities_table()
	{
		$severities = $this->config->get_severities();

		$severities_table = new FileTemplate('bugtracker/AdminBugtrackerSeveritiesListController.tpl');
		$severities_table->add_lang($this->lang);

		$key = 0;
		foreach ($severities as $key => $severity)
		{
			$severities_table->assign_block_vars('severities', array(
				'C_IS_DEFAULT' => $this->config->get_default_severity() == $key,
				'ID'		   => $key,
				'NAME'		   => stripslashes($severity['name']),
				'COLOR'		   => $severity['color']
			));
		}

		$severities_table->put_all(array(
			'C_SEVERITIES'					  => !empty($severities),
			'C_DISPLAY_DEFAULT_DELETE_BUTTON' => $this->config->get_default_severity(),
			'LINK_DELETE_DEFAULT'			  => BugtrackerUrlBuilder::delete_default_parameter('severity')->rel()
		));

		return $severities_table;
	}

	private function build_priorities_table()
	{
		$priorities = $this->config->get_priorities();

		$priorities_table = new FileTemplate('bugtracker/AdminBugtrackerPrioritiesListController.tpl');
		$priorities_table->add_lang($this->lang);

		$key = 0;
		foreach ($priorities as $key => $priority)
		{
			$priorities_table->assign_block_vars('priorities', array(
				'C_IS_DEFAULT' => $this->config->get_default_priority() == $key,
				'ID'		   => $key,
				'NAME'		   => stripslashes($priority)
			));
		}

		$priorities_table->put_all(array(
			'C_PRIORITIES'					  => !empty($priorities),
			'C_DISPLAY_DEFAULT_DELETE_BUTTON' => $this->config->get_default_priority(),
			'LINK_DELETE_DEFAULT'			  => BugtrackerUrlBuilder::delete_default_parameter('priority')->rel()
		));

		return $priorities_table;
	}

	private function build_versions_table()
	{
		$versions = $this->config->get_versions();

		$versions_table = new FileTemplate('bugtracker/AdminBugtrackerVersionsListController.tpl');
		$versions_table->add_lang($this->lang);

		$key = 0;
		foreach ($versions as $key => $version)
		{
			$release_date = !empty($version['release_date']) && is_numeric($version['release_date']) ? new Date($version['release_date'], Timezone::SERVER_TIMEZONE) : null;

			$versions_table->assign_block_vars('versions', array(
				'C_IS_DEFAULT'	=> $this->config->get_default_version() == $key,
				'C_DETECTED_IN'	=> $version['detected_in'],
				'ID'			=> $key,
				'NAME'			=> stripslashes($version['name']),
				'RELEASE_DATE'	=> !empty($release_date) ? $release_date->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) : '',
				'DAY'			=> !empty($release_date) ? $release_date->get_day() : date('j'),
				'MONTH'			=> !empty($release_date) ? $release_date->get_month() : date('n'),
				'YEAR'			=> !empty($release_date) ? $release_date->get_year() : date('Y'),
				'LINK_DELETE'	=> BugtrackerUrlBuilder::delete_parameter('version', $key)->rel()
			));
		}

		$versions_table->put_all(array(
			'C_VERSIONS'					  => !empty($versions),
			'MAX_INPUT'						  => $this->max_input,
			'NEXT_ID'						  => $key + 1,
			'DAY'							  => date('j'),
			'MONTH'							  => date('n'),
			'YEAR'							  => date('Y'),
			'C_DISPLAY_DEFAULT_DELETE_BUTTON' => $this->config->get_default_version(),
			'LINK_DELETE_DEFAULT'			  => BugtrackerUrlBuilder::delete_default_parameter('version')->rel()
		));

		return $versions_table;
	}

	private function save()
	{
		$request = AppContext::get_request();

		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions = $this->config->get_versions();

		foreach ($types as $key => $type)
		{
			$new_type_name = $request->get_value('type' . $key, '');
			$types[$key] = (!empty($new_type_name) && $new_type_name != $type) ? $new_type_name : $type;
		}

		$nb_types = count($types);
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$type = 'type_' . $i;
			if ($request->has_postparameter($type) && $request->get_poststring($type))
			{
				if (empty($nb_types))
					$types[1] = addslashes($request->get_poststring($type));
				else
					$types[] = addslashes($request->get_poststring($type));
				$nb_types++;
			}
		}

		foreach ($categories as $key => $category)
		{
			$new_category_name = $request->get_value('category' . $key, '');
			$categories[$key] = (!empty($new_category_name) && $new_category_name != $category) ? $new_category_name : $category;
		}

		$nb_categories = count($categories);
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$category = 'category_' . $i;
			if ($request->has_postparameter($category) && $request->get_poststring($category))
			{
				if (empty($nb_categories))
					$categories[1] = addslashes($request->get_poststring($category));
				else
					$categories[] = addslashes($request->get_poststring($category));
				$nb_categories++;
			}
		}

		foreach ($severities as $key => $severity)
		{
			$new_severity_name = $request->get_value('severity' . $key, '');
			$new_severity_color = $request->get_value('color' . $key, '');
			$severities[$key]['name'] = (!empty($new_severity_name) && $new_severity_name != $severity['name']) ? $new_severity_name : $severity['name'];
			$severities[$key]['color'] = ($new_severity_color != $severity['color']) ? $new_severity_color : $severity['color'];
		}

		foreach ($priorities as $key => $priority)
		{
			$new_priority_name = $request->get_value('priority' . $key, '');
			$priorities[$key] = (!empty($new_priority_name) && $new_priority_name != $priority) ? $new_priority_name : $priority;
		}

		foreach ($versions as $key => $version)
		{
			$new_version_name = $request->get_value('version' . $key, '');
			$new_version_release_date = $request->get_value('release_date' . $key, '');
			$new_version_detected_in = (bool)$request->get_value('detected_in' . $key, '');
			$versions[$key]['name'] = (!empty($new_version_name) && $new_version_name != $version['name']) ? $new_version_name : $version['name'];
			$release_date = $new_version_release_date ? new Date( $new_version_release_date) : '';
			$versions[$key]['release_date'] = $release_date ? $release_date->get_timestamp() : '';
			$versions[$key]['detected_in'] = ($new_version_detected_in != $version['detected_in']) ? $new_version_detected_in : $version['detected_in'];
		}

		$nb_versions = count($versions);
		for ($i = 1; $i <= $this->max_input; $i++)
		{
			$version = 'version_' . $i;
			if ($request->has_postparameter($version) && $request->get_poststring($version))
			{
				$version_release_date = $request->get_value('release_date_' . $i, '');
				$release_date = $version_release_date ? new Date($version_release_date) : '';
				$release_date = $release_date ? $release_date->get_timestamp() : '';

				if (empty($nb_versions))
					$versions[1] = array(
						'name'		   => addslashes($request->get_poststring($version)),
						'release_date' => $release_date,
						'detected_in'  => (bool)$request->get_value('detected_in' . $i, '')
					);
				else
					$versions[] = array(
						'name'		   => addslashes($request->get_poststring($version)),
						'release_date' => $release_date,
						'detected_in'  => (bool)$request->get_value('detected_in' . $i, '')
					);
				$nb_versions++;
			}
		}

		$this->config->set_items_per_page($this->form->get_value('items_per_page'));
		$this->config->set_rejected_bug_color($this->form->get_value('rejected_bug_color'));
		$this->config->set_fixed_bug_color($this->form->get_value('fixed_bug_color'));

		if ($this->form->get_value('roadmap_enabled'))
			$this->config->enable_roadmap();
		else
			$this->config->disable_roadmap();

		if ($this->form->get_value('restrict_display_to_own_elements_enabled'))
			$this->config->enable_restrict_display_to_own_elements();
		else
			$this->config->disable_restrict_display_to_own_elements();

		if ($this->form->get_value('progress_bar_displayed'))
		{
			$this->config->display_progress_bar();

			$status_list = array();
			foreach ($this->config->get_status_list() as $key => $value)
			{
				$status_list[$key] = $this->form->get_value($key);
			}
			$this->config->set_status_list($status_list);
		}
		else
			$this->config->hide_progress_bar();

		if ($this->form->get_value('admin_alerts_enabled'))
		{
			$this->config->enable_admin_alerts();
			$this->config->set_admin_alerts_fix_action($this->form->get_value('admin_alerts_fix_action')->get_raw_value());

			$admin_alerts_levels = array();
			foreach ($this->form->get_value('admin_alerts_levels') as $level => $value)
			{
				$admin_alerts_levels[] = (string)$value->get_id();
			}
			$this->config->set_admin_alerts_levels($admin_alerts_levels);
		}
		else
			$this->config->disable_admin_alerts();

		if ($this->form->get_value('stats_enabled'))
		{
			$this->config->enable_stats();
			if ($this->form->get_value('stats_top_posters_enabled'))
			{
				$this->config->enable_stats_top_posters();
				$this->config->set_stats_top_posters_number($this->form->get_value('stats_top_posters_number'));
			}
			else
				$this->config->disable_stats_top_posters();
		}
		else
			$this->config->disable_stats();

		if ($this->form->get_value('pm_enabled'))
		{
			$this->config->enable_pm();

			if ($this->form->get_value('pm_comment_enabled'))
				$this->config->enable_pm_comment();
			else
				$this->config->disable_pm_comment();

			if ($this->form->get_value('pm_in_progress_enabled'))
				$this->config->enable_pm_in_progress();
			else
				$this->config->disable_pm_in_progress();

			if ($this->form->get_value('pm_fix_enabled'))
				$this->config->enable_pm_fix();
			else
				$this->config->disable_pm_fix();

			if ($this->form->get_value('pm_pending_enabled'))
				$this->config->enable_pm_pending();
			else
				$this->config->disable_pm_pending();

			if ($this->form->get_value('pm_assign_enabled'))
				$this->config->enable_pm_assign();
			else
				$this->config->disable_pm_assign();

			if ($this->form->get_value('pm_edit_enabled'))
				$this->config->enable_pm_edit();
			else
				$this->config->disable_pm_edit();

			if ($this->form->get_value('pm_reject_enabled'))
				$this->config->enable_pm_reject();
			else
				$this->config->disable_pm_reject();

			if ($this->form->get_value('pm_reopen_enabled'))
				$this->config->enable_pm_reopen();
			else
				$this->config->disable_pm_reopen();

			if ($this->form->get_value('pm_delete_enabled'))
				$this->config->enable_pm_delete();
			else
				$this->config->disable_pm_delete();
		}
		else
			$this->config->disable_pm();

		$this->config->set_content_value($this->form->get_value('content_value'));

		if ($this->form->get_value('type_mandatory'))
			$this->config->type_mandatory();
		else
			$this->config->type_not_mandatory();

		if ($this->form->get_value('display_type_column'))
			$this->config->display_type_column();
		else
			$this->config->hide_type_column();

		$this->config->set_types($types);
		$this->config->set_default_type($request->get_value('default_type', 0));

		if ($this->form->get_value('category_mandatory'))
			$this->config->category_mandatory();
		else
			$this->config->category_not_mandatory();

		if ($this->form->get_value('display_category_column'))
			$this->config->display_category_column();
		else
			$this->config->hide_category_column();

		$this->config->set_categories($categories);
		$this->config->set_default_category($request->get_value('default_category', 0));

		if ($this->form->get_value('severity_mandatory'))
			$this->config->severity_mandatory();
		else
			$this->config->severity_not_mandatory();

		$this->config->set_severities($severities);
		$this->config->set_default_severity($request->get_value('default_severity', 0));

		if ($this->form->get_value('priority_mandatory'))
			$this->config->priority_mandatory();
		else
			$this->config->priority_not_mandatory();

		if ($this->form->get_value('display_priority_column'))
			$this->config->display_priority_column();
		else
			$this->config->hide_priority_column();

		$this->config->set_priorities($priorities);
		$this->config->set_default_priority($request->get_value('default_priority', 0));

		if ($this->form->get_value('detected_in_version_mandatory'))
			$this->config->detected_in_version_mandatory();
		else
			$this->config->detected_in_version_not_mandatory();

		if ($this->form->get_value('display_detected_in_column'))
			$this->config->display_detected_in_column();
		else
			$this->config->hide_detected_in_column();

		$this->config->set_versions($versions);
		$this->config->set_default_version($request->get_value('default_version', 0));

		BugtrackerConfig::save();

		BugtrackerStatsCache::invalidate();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
