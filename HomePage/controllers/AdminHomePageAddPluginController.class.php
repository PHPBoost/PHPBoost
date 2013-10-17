<?php
/*##################################################
 *                      AdminHomePageAddPluginController.class.php
 *                            -------------------
 *   begin                : March 14, 2012
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

class AdminHomePageAddPluginController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $plugin_fieldset_configuration;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$column_selected = $request->get_int('column', 1);
		$plugin_selected = $request->get_string('plugin', '');
		
		$this->init();
		
		$this->view->put('FORM_CHOICE_PLUGIN', $this->build_plugin_choice($column_selected, $plugin_selected)->display());
		
		if (!empty($plugin_selected))
		{
			$this->build_form($column_selected, $plugin_selected);
			$this->view->put('FORM', $this->form->display());
			
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$this->save($plugin_selected);
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'errors-common'), MessageHelper::SUCCESS));
			}
		}

		return $this->response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomePage');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM_CHOICE_PLUGIN # # INCLUDE FORM # # INCLUDE PLUGIN_FORM_CONFIG #');
	}
	
	private function build_plugin_choice($column_selected, $plugin_selected)
	{
		$form = new HTMLForm('PluginChoice');
		$fieldset = new FormFieldsetHTML('PluginChoice', $this->lang['plugin.type']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHomePagePluginSelect('PluginSelect', $this->lang['plugin.type'], $plugin_selected,
		array('events' => array('change' => 'document.location = "'. HomePageUrlBuilder::add_plugin($column_selected, $plugin_selected)->absolute() .'" + HTMLForms.getField("PluginSelect").getValue();'))));
		return $form;
	}
	
	private function build_form($column_selected, $plugin_selected)
	{
		$this->form = new HTMLForm('HomePage');

		$fieldset = new FormFieldsetHTML('AddPlugin', $this->lang['plugin.add']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['title'], ''));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('column', $this->lang['column'], $column_selected, array(
			new FormFieldSelectChoiceOption('1', '1'),
			new FormFieldSelectChoiceOption('2', '2'),
			new FormFieldSelectChoiceOption('3', '3')
		)));

		$fieldset->add_field(new FormFieldCheckbox('enabled', $this->lang['enabled']));
		
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['authorizations'], Plugin::READ_AUTHORIZATIONS)));
		$auth_settings->build_from_auth_array(array('r1' => 1, 'r0' => 1, 'r-1' => 1));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$plugin = new $plugin_selected();
		if ($plugin->has_configuration())
		{
			$this->plugin_fieldset_configuration = $plugin->get_fieldset_configuration($this->form);
			$this->form->add_fieldset($this->plugin_fieldset_configuration->get_fieldset());
		}
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
	}
	
	private function save($plugin_selected)
	{
		$block = (int)$this->form->get_value('column')->get_raw_value();
		$enabled = $this->form->get_value('enabled');
		$title = $this->form->get_value('title');
		$plugin = array(
			'title' => $title,
			'block' => $block,
			'class' => $plugin_selected,
			'enabled' => (int)$enabled,
			'position' => HomePagePluginsService::get_next_position($block),
			'authorizations' => serialize($this->form->get_value('authorizations')->build_auth_array())
		);
		$id = HomePagePluginsService::add($plugin);
		$this->plugin_fieldset_configuration->get_plugin_configuration()->set_id($id);
		$this->plugin_fieldset_configuration->handle_submit();
		
		HomePagePluginsCache::invalidate();
	}
		
	private function response()
	{
		$response = new AdminMenuDisplayResponse($this->view);
		$response->set_title($this->lang['home_page']);

		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['plugin.add']);
		return $response;
	}
}
?>