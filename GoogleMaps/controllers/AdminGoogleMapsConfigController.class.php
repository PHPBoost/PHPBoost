<?php
/*##################################################
 *		               AdminGoogleMapsConfigController.class.php
 *                            -------------------
 *   begin                : March 26, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminGoogleMapsConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	/**
	 * @var GoogleMapsConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			
			if ($this->config->get_api_key())
			{
				$this->form->get_field_by_id('default_position')->set_hidden(false);
				$this->form->get_field_by_id('default_position')->enable();
			}
			else
				$this->form->get_field_by_id('default_position')->set_hidden(true);
			
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'GoogleMaps');
		$this->config = GoogleMapsConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('config', $this->lang['config.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('api_key', $this->lang['config.api.key'], $this->config->get_api_key(),
			array('description' => $this->lang['config.api.key.desc'])
		));

		$fieldset->add_field(new GoogleMapsFormFieldMapAddress('default_position', $this->lang['config.default_marker.position'], new GoogleMapsMarker($this->config->get_default_marker_address(), $this->config->get_default_marker_latitude(), $this->config->get_default_marker_longitude(), '', $this->config->get_default_zoom()),
			array('description' => $this->lang['config.default_marker.position.description'], 'always_display_marker' => true, 'hidden' => !$this->config->get_api_key())
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_api_key($this->form->get_value('api_key'));
		
		if ($this->form->get_value('api_key') && !$this->form->get_field_by_id('default_position')->is_hidden())
		{
			$default_marker = new GoogleMapsMarker();
			$default_marker->set_properties(TextHelper::unserialize($this->form->get_value('default_position')));
			
			$this->config->set_default_marker_address($default_marker->get_address());
			$this->config->set_default_marker_latitude($default_marker->get_latitude());
			$this->config->set_default_marker_longitude($default_marker->get_longitude());
			$this->config->set_default_zoom($default_marker->get_zoom());
		}
		
		GoogleMapsConfig::save();
	}
	
	private function build_response(View $tpl)
	{
		$title = LangLoader::get_message('configuration', 'admin');
		
		$response = new AdminMenuDisplayResponse($tpl);
		$response->set_title($title);
		$response->add_link($this->lang['config.title'], GoogleMapsUrlBuilder::configuration());
		$response->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('GoogleMaps')->get_configuration()->get_documentation());
		$env = $response->get_graphical_environment();
		$env->set_page_title($title);
		
		return $response;
	}
}
?>
