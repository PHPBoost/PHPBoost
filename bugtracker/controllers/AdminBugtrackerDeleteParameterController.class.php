<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 18
 * @since       PHPBoost 3.0 - 2012 10 22
*/

class AdminBugtrackerDeleteParameterController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $config;

	private $parameter;
	private $id;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);

		if (!$this->get_parameter_items_exists())
		{
			$this->delete_parameter_in_config();
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::configuration());
		}

		$this->build_form();
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->form->get_value('delete_parameter_and_content'))
			{
				$this->delete_parameter_and_bugs();
			}
			else
			{
				$other_id = $this->form->get_value('move_into_another')->get_raw_value();
				$this->move_into_another($other_id);
			}
			$this->delete_parameter_in_config();
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::configuration());
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminBugtrackerDisplayResponse($tpl, $this->lang['config.delete_parameter.' . $this->parameter]);
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->config = BugtrackerConfig::load();

		//Get the parameter to delete
		$this->parameter = $request->get_string('parameter', '');
		//Get the id of the parameter to delete
		$this->id = $request->get_int('id', '');

		if (!in_array($this->parameter, array('type', 'category', 'version')) || empty($this->id))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_parameter']);
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}

		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$versions = $this->config->get_versions();

		switch ($this->parameter)
		{
			case 'type':
				if (!isset($types[$this->id]))
				{
					//Error : unexist type
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_type']);
					$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
					DispatchManager::redirect($controller);
				}
				break;
			case 'category':
				if (!isset($categories[$this->id]))
				{
					//Error : unexist category
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_category']);
					$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
					DispatchManager::redirect($controller);
				}
				break;
			case 'version':
				if (!isset($versions[$this->id]))
				{
					//Error : unexist version
					$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_version']);
					$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
					DispatchManager::redirect($controller);
				}
				break;
		}
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('delete_' . $this->parameter, $this->lang['config.delete_parameter.' . $this->parameter]);
		$fieldset->set_description($this->lang['config.delete_parameter.description.' . $this->parameter]);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('delete_parameter_and_content', $this->lang['config.delete_parameter.parameter_and_content.' . $this->parameter], FormFieldCheckbox::UNCHECKED),
			array('class' => 'custom-checkbox')
		);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('move_into_another', $this->lang['config.delete_parameter.move_into_another'], '', $this->get_move_into_another_options()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_parameter_items_exists()
	{
		return PersistenceContext::get_querier()->row_exists(BugtrackerSetup::$bugtracker_table, 'WHERE ' . ($this->parameter == 'version' ? 'detected_in=:id_parameter OR fixed_in' : $this->parameter) . '=:id_parameter', array('id_parameter' => $this->id));
	}

	private function get_move_into_another_options()
	{
		$other = array();
		$other[] = new FormFieldSelectChoiceOption(' ', 0);

		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$versions = $this->config->get_versions();

		switch ($this->parameter)
		{
			case 'type':
				foreach ($types as $key => $type)
				{
					if ($key != $this->id)
						$other[] = new FormFieldSelectChoiceOption(stripslashes($type), $key);
				}
				break;
			case 'category':
				foreach ($categories as $key => $category)
				{
					if ($key != $this->id)
						$other[] = new FormFieldSelectChoiceOption(stripslashes($category), $key);
				}
				break;
			case 'version':
				foreach ($versions as $key => $version)
				{
					if ($key != $this->id)
						$other[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
				}
				break;
		}

		return $other;
	}

	private function delete_parameter_in_config()
	{
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$versions = $this->config->get_versions();

		switch ($this->parameter)
		{
			case 'type':
				//Delete the type in the list of types
				unset($types[$this->id]);
				$this->config->set_types($types);
				if ($this->config->get_default_type() == $this->id)
					$this->config->set_default_type(0);
				break;
			case 'category':
				//Delete the category in the list of categories
				unset($categories[$this->id]);
				$this->config->set_categories($categories);
				if ($this->config->get_default_category() == $this->id)
					$this->config->set_default_category(0);
				break;
			case 'version':
				//Delete the version in the list of versions
				unset($versions[$this->id]);
				$this->config->set_versions($versions);
				if ($this->config->get_default_version() == $this->id)
					$this->config->set_default_version(0);
				break;
		}

		BugtrackerConfig::save();
	}

	private function delete_parameter_and_bugs()
	{
		$bugs_list = array();
		switch ($this->parameter)
		{
			case 'type':
				$result = PersistenceContext::get_querier()->select_rows(BugtrackerSetup::$bugtracker_table, array('id'), 'WHERE type=:id', array('id' => $this->id));
				while ($row = $result->fetch())
				{
					$bugs_list[] = $row['id'];
				}
				$result->dispose();

				//Delete bugs
				BugtrackerService::delete('WHERE type=:id', array('id' => $this->id));
				break;
			case 'category':
				$result = PersistenceContext::get_querier()->select_rows(BugtrackerSetup::$bugtracker_table, array('id'), 'WHERE category=:id', array('id' => $this->id));
				while ($row = $result->fetch())
				{
					$bugs_list[] = $row['id'];
				}
				$result->dispose();

				//Delete bugs
				BugtrackerService::delete('WHERE category=:id', array('id' => $this->id));
				break;
			case 'version':
				$result = PersistenceContext::get_querier()->select_rows(BugtrackerSetup::$bugtracker_table, array('id'), 'WHERE detected_in=:id OR fixed_in=:id', array('id' => $this->id));
				while ($row = $result->fetch())
				{
					$bugs_list[] = $row['id'];
				}
				$result->dispose();

				//Delete bugs
				BugtrackerService::delete('WHERE detected_in=:id OR fixed_in=:id', array('id' => $this->id));

				BugtrackerStatsCache::invalidate();
				break;
		}

		//Delete history lines containing this type
		BugtrackerService::delete_history("WHERE bug_id IN (:bugs_list)", array('bugs_list' => implode(',', $bugs_list)));
	}

	private function move_into_another($new_id)
	{
		switch ($this->parameter)
		{
			case 'type':
				//Update the type for the bugs of this type
				BugtrackerService::update_parameter(array('type' => $new_id), 'WHERE type=:id', array('id' => $this->id));

				if (empty($new_id))
				{
					//Delete history lines containing this type
					BugtrackerService::delete_history("WHERE updated_field='type' AND (old_value=:id OR new_value=:id)", array('id' => $this->id));
				}
				else
				{
					//Update history lines containing this type
					BugtrackerService::update_history(array('old_value' => $new_id), "WHERE updated_field='type' AND old_value=:id", array('id' => $this->id));
					BugtrackerService::update_history(array('new_value' => $new_id), "WHERE updated_field='type' AND new_value=:id", array('id' => $this->id));
				}
				break;
			case 'category':
				//Update the category for the bugs of this category
				BugtrackerService::update_parameter(array('category' => $new_id), 'WHERE category=:id', array('id' => $this->id));

				if (empty($new_id))
				{
					//Delete history lines containing this type
					BugtrackerService::delete_history("WHERE updated_field='category' AND (old_value=:id OR new_value=:id)", array('id' => $this->id));
				}
				else
				{
					//Update history lines containing this category
					BugtrackerService::update_history(array('old_value' => $new_id), "WHERE updated_field='category' AND old_value=:id", array('id' => $this->id));
					BugtrackerService::update_history(array('new_value' => $new_id), "WHERE updated_field='category' AND new_value=:id", array('id' => $this->id));
				}
				break;
			case 'version':
				//Update the version for the bugs of this version
				BugtrackerService::update_parameter(array('detected_in' => $new_id), 'WHERE detected_in=:id', array('id' => $this->id));
				BugtrackerService::update_parameter(array('fixed_in' => $new_id), 'WHERE fixed_in=:id', array('id' => $this->id));

				if (empty($new_id))
				{
					//Delete history lines containing this type
					BugtrackerService::delete_history("WHERE updated_field='detected_in' OR updated_field='fixed_in' AND (old_value=:id OR new_value=:id)", array('id' => $this->id));
				}
				else
				{
					//Update history lines containing this version
					BugtrackerService::update_history(array('old_value' => $new_id), "WHERE updated_field='detected_in' AND old_value=:id", array('id' => $this->id));
					BugtrackerService::update_history(array('new_value' => $new_id), "WHERE updated_field='detected_in' AND new_value=:id", array('id' => $this->id));
					BugtrackerService::update_history(array('old_value' => $new_id), "WHERE updated_field='fixed_in' AND old_value=:id", array('id' => $this->id));
					BugtrackerService::update_history(array('new_value' => $new_id), "WHERE updated_field='fixed_in' AND new_value=:id", array('id' => $this->id));
				}

				BugtrackerStatsCache::invalidate();
				break;
		}
	}
}
?>
