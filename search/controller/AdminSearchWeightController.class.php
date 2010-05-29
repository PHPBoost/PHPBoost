<?php
/*##################################################
 *                        AdminSearchWeightController.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Rouchon Loic
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class AdminSearchWeightController extends AdminSearchController {

	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var string[string]
	 */
	private $lang;

	/**
	 * @var HTMLForm
	 */
	private $form;

	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit;

	/**
	 * @var SearchConfig
	 */
	private $config;

	/**
	 * @var int[string]
	 */
	private $weightings;

	public function __construct()
	{
		$this->view = new StringTemplate('# INCLUDE FORM #');
		$this->lang = LangLoader::get('admin', 'search');
		$this->config = SearchConfig::load();
		$this->weightings = $this->config->get_weightings();
	}

	public function execute(HTTPRequest $request)
	{
		$this->build_form();
		$this->try_save();
		return $this->send();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('weighting');
		$this->add_fieldset();
		$this->add_buttons();
		return $this->form;
	}

	private function try_save()
	{
		if ($this->submit->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
	}

	private function send()
	{
		$this->view->add_subtemplate('FORM', $this->form->display());
		return $this->prepare_to_send($this->view);
	}

	private function add_fieldset()
	{
		$fieldset = new FormFieldsetHTML('search_weighting', $this->lang['search_config_weighting']);
		$fieldset->set_description($this->lang['search_config_weighting_explain']);
		$this->add_weightings_fields($fieldset);
		$this->form->add_fieldset($fieldset);
	}

	private function add_buttons()
	{
		$this->submit = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit);
		$reset = new FormButtonReset();
		$this->form->add_button($reset);
	}

	private function save()
	{
		$this->weightings = array();
		foreach (SearchProvidersService::get_providers_ids() as $provider_id)
		{
			$this->weightings[$provider_id] = $this->form->get_value($provider_id);
		}
		$this->config->set_weightings($this->weightings);
		SearchConfig::save($this->config);
	}

	private function add_weightings_fields(FormFieldset $fieldset)
	{
		$header = new FormFieldFree('header', $this->lang['provider'], '<b>' . $this->lang['search_weights'] . '<b>');
		$fieldset->add_field($header);
		foreach (SearchProvidersService::get_providers_ids() as $provider_id)
		{
			$provider_name = ModuleConfigurationManager::get($provider_id)->get_name();
			$value = $this->get_provider_weight($provider_id);
			$options = array();
			$constraints = array(new FormFieldConstraintIntegerRange(1, 100));
			$field = new FormFieldTextEditor($provider_id, $provider_name, $value, $options, $constraints);
			$fieldset->add_field($field);
		}
	}

	private function get_provider_weight($provider_id)
	{
		if (isset($this->weightings[$provider_id]))
		{
			return (int) $this->weightings[$provider_id];
		}
		return 1;
	}
}

?>