<?php
/*##################################################
 *                     AdminCacheConfigController.class.php
 *                            -------------------
 *   begin                : August 8 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

class AdminCacheConfigController extends AdminController
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
	private $css_cache_config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('level_css_cache')->set_hidden(!$this->css_cache_config->is_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminCacheMenuDisplayResponse($tpl, $this->lang['cache_configuration']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-cache-common');
		$this->css_cache_config = CSSCacheConfig::load();
	}
	
	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('explain', $this->lang['cache_configuration']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHTML('exp_php_cache', $this->lang['explain_php_cache']));
		$fieldset->add_field(new FormFieldBooleanInformation('apc_available', $this->lang['apc_available'], $this->is_apc_available(), array('description' => $this->lang['explain_apc_available'])));

		if ($this->is_apc_available())
		{
			$fieldset->add_field(new FormFieldCheckbox('enable_apc', $this->lang['enable_apc'], $this->is_apc_enabled()));
		}
		
		$fieldset->add_field(new FormFieldHTML('exp_css_cache', '<hr><br />' . $this->lang['explain_css_cache_config']));
		$fieldset->add_field(new FormFieldCheckbox('enable_css_cache', $this->lang['enable_css_cache'], $this->css_cache_config->is_enabled(), array(
		'events' => array('click' => '
			if (HTMLForms.getField("enable_css_cache").getValue()) {
				HTMLForms.getField("level_css_cache").enable();
			} else { 
				HTMLForms.getField("level_css_cache").disable();
			}'
		))));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('level_css_cache', $this->lang['level_css_cache'], $this->css_cache_config->get_optimization_level(), array(
			new FormFieldSelectChoiceOption($this->lang['low_level_css_cache'], CSSFileOptimizer::LOW_OPTIMIZATION),
			new FormFieldSelectChoiceOption($this->lang['high_level_css_cache'], CSSFileOptimizer::HIGH_OPTIMIZATION)
		), array('description' => $this->lang['level_css_cache'], 'hidden' => !$this->css_cache_config->is_enabled())));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		
		$this->form = $form;
	}

	private function is_apc_available()
	{
		return DataStoreFactory::is_apc_available();
	}
	
	private function is_apc_enabled()
	{
		return DataStoreFactory::is_apc_enabled();
	}

	protected function save()
	{
		if ($this->is_apc_available())
		{
			if ($this->form->get_value('enable_apc'))
			{
				DataStoreFactory::set_apc_enabled(true);
			}
			else
			{
				DataStoreFactory::set_apc_enabled(false);
			}
		}
		
		if ($this->form->get_value('enable_css_cache'))
		{
			$this->css_cache_config->enable();
		}
		else
		{
			$this->css_cache_config->disable();
		}
		
		if (!$this->form->field_is_disabled('level_css_cache'))
		{
			$this->css_cache_config->set_optimization_level($this->form->get_value('level_css_cache')->get_raw_value());
		}
		
		CSSCacheConfig::save();
		AppContext::get_cache_service()->clear_css_cache();
	}
}
?>