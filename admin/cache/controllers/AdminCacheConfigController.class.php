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

class AdminCacheConfigController extends AbstractAdminFormPageController
{
	private $lang;
	private $css_cache_config;

	public function __construct()
	{
		$this->lang = LangLoader::get('admin-cache-common');
		parent::__construct(LangLoader::get_message('process.success', 'errors-common'));
		$this->css_cache_config = CSSCacheConfig::load();
	}

	protected function create_form()
	{
		$form = new HTMLForm('cache_config');
		$fieldset = new FormFieldsetHTML('explain', $this->lang['cache_configuration']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldHTML('exp_php_cache', $this->lang['explain_php_cache']));
		$fieldset->add_field(new FormFieldBooleanInformation('apc_available', $this->lang['apc_available'], $this->is_apc_available(), array('description' => $this->lang['explain_apc_available'])));

		if ($this->is_apc_available())
		{
			$fieldset->add_field(new FormFieldCheckbox('enable_apc', $this->lang['enable_apc'], $this->is_apc_enabled()));
		}
		
		$fieldset->add_field(new FormFieldHTML('exp_css_cache', '<hr><br />' . $this->lang['explain_css_cache_config']));
		$fieldset->add_field(new FormFieldCheckbox('enable_css_cache', $this->lang['enable_css_cache'], $this->css_cache_config->is_enabled()));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('level_css_cache', $this->lang['level_css_cache'], $this->css_cache_config->get_optimization_level(), array(
			new FormFieldSelectChoiceOption($this->lang['low_level_css_cache'], CSSFileOptimizer::LOW_OPTIMIZATION),
			new FormFieldSelectChoiceOption($this->lang['high_level_css_cache'], CSSFileOptimizer::HIGH_OPTIMIZATION)
		), array('description' => $this->lang['level_css_cache'])));

		$button = new FormButtonDefaultSubmit();
		$this->set_submit_button($button);
		$form->add_button($button);
		$this->set_form($form);
	}

	private function is_apc_available()
	{
		return DataStoreFactory::is_apc_available();
	}
	
	private function is_apc_enabled()
	{
		return DataStoreFactory::is_apc_enabled();
	}

	protected function handle_submit()
	{
		if ($this->is_apc_available())
		{
			if ($this->get_form()->get_value('enable_apc'))
			{
				DataStoreFactory::set_apc_enabled(true);
			}
			else
			{
				DataStoreFactory::set_apc_enabled(false);
			}
		}
		
		if ($this->get_form()->get_value('enable_css_cache'))
		{
			$this->css_cache_config->enable();
		}
		else
		{
			$this->css_cache_config->disable();
		}
		
		$this->css_cache_config->set_optimization_level($this->get_form()->get_value('level_css_cache')->get_raw_value());
		CSSCacheConfig::save();
		AppContext::get_cache_service()->clear_css_cache();
	}

	protected function generate_response(View $view)
	{
		return new AdminCacheMenuDisplayResponse($view, $this->lang['cache_configuration']);
	}
}
?>