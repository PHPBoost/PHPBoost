<?php
/*##################################################
 *                        AdminSearchConfigController.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Rouchon Loïc
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

class AdminSearchConfigController extends AdminSearchController {

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

	public function __construct()
	{
		$this->view = new StringTemplate('# INCLUDE FORM #');
		$this->lang = LangLoader::get('admin', 'search');
		$this->config = SearchConfig::load();
	}

	public function execute(HTTPRequest $request)
	{
		$this->build_form();
		$this->try_save();
		return $this->send();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('configuration');
		$this->add_configuration_fieldset();
		$this->add_cache_configuration_fieldset();
		$this->add_clear_cache_fieldset();
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

	private function add_configuration_fieldset()
	{
		$fieldset = new FormFieldsetHTML('search_config', $this->lang['search_config']);
		$this->form->add_fieldset($fieldset);

		$id = 'nb_results_per_page';
		$name = $this->lang['nb_results_per_page'];
		$value = $this->config->get_nb_results_per_page();
		$options = array();
		$constraints = array(new FormFieldConstraintIntegerRange(1, 500));
		$field = new FormFieldTextEditor($id, $name, $value, $options, $constraints);
		$fieldset->add_field($field);

		$id = 'unauthorized_providers';
		$name = $this->lang['unauthorized_modules'];
		$value = var_export($this->config->get_unauthorized_providers(), true);
		$options = array('description' => $this->lang['unauthorized_modules_explain']);
		$field = new FormFieldTextEditor($id, $name, $value, $options);
		$fieldset->add_field($field);
	}

	private function add_cache_configuration_fieldset()
	{
		$fieldset = new FormFieldsetHTML('search_cache', $this->lang['search_cache']);
		$this->form->add_fieldset($fieldset);

		$id = 'cache_lifetime';
		$name = $this->lang['cache_time'];
		$value = $this->config->get_cache_lifetime();
		$options = array('description' => $this->lang['cache_time_explain']);
		$constraints = array(new FormFieldConstraintIntegerRange(0, 1000000));
		$field = new FormFieldTextEditor($id, $name, $value, $options, $constraints);
		$fieldset->add_field($field);

		$id = 'cache_max_uses';
		$name = $this->lang['cache_max_use'];
		$value = $this->config->get_cache_max_uses();
		$options = array('description' => $this->lang['cache_max_use_explain']);
		$constraints = array(new FormFieldConstraintIntegerRange(0, 1000000));
		$field = new FormFieldTextEditor($id, $name, $value, $options, $constraints);
		$fieldset->add_field($field);
	}

	private function add_clear_cache_fieldset()
	{
		$fieldset = new FormFieldsetHTML('clear_cache', $this->lang['clear_out_cache']);
		$this->form->add_fieldset($fieldset);

		$id = 'clear_cache';
        $title = $this->lang['clear_out_cache'];
        $url = AdminSearchUrlBuilder::clear_cache();
        $img = '/templates/' . get_utheme() . '/images/admin/refresh.png';
		$field = new FormFieldActionLink($id, $title, $url, $img);
		$fieldset->add_field($field);
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
		$this->config->set_nb_results_per_page($this->form->get_value('nb_results_per_page'));
		$this->config->set_cache_lifetime($this->form->get_value('cache_lifetime'));
		$this->config->set_cache_max_uses($this->form->get_value('cache_max_uses'));
		$providers = $this->form->get_value('unauthorized_providers');
		if (is_array($providers))
		{
			$this->config->set_unauthorized_providers($providers);
		}
		SearchConfig::save($this->config);
	}
}

?>