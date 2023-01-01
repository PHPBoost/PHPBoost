<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 2.0 - 2008 08 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCacheConfigController extends DefaultAdminController
{
	private $css_cache_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('level_css_cache')->set_hidden(!$this->css_cache_config->is_enabled());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminCacheMenuDisplayResponse($this->view, $this->lang['admin.cache.configuration']);
	}

	private function init()
	{
		$this->css_cache_config = CSSCacheConfig::load();
	}

	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('explain', $this->lang['admin.cache.configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldHTML('exp_php_cache', $this->lang['admin.php.description'],
			array('class' => 'half-field')
		));

		$fieldset->add_field(new FormFieldBooleanInformation('apc_available', $this->lang['admin.apc.available'], $this->is_apc_available(),
			array('class' => 'top-field', 'description' => $this->lang['admin.apc.available.clue'])
		));

		if ($this->is_apc_available())
		{
			$fieldset->add_field(new FormFieldCheckbox('enable_apc', $this->lang['admin.enable.apc'], $this->is_apc_enabled(),
				array('class' => ' top-field custom-checkbox')
			));
		}

		$fieldset->add_field(new FormFieldHTML('exp_css_cache', $this->lang['admin.css.cache.description'],
			array('class'=>'half-field')
		));

		$fieldset->add_field(new FormFieldCheckbox('enable_css_cache', $this->lang['admin.enable.css.cache'], $this->css_cache_config->is_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("enable_css_cache").getValue()) {
						HTMLForms.getField("level_css_cache").enable();
					} else {
						HTMLForms.getField("level_css_cache").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('level_css_cache', $this->lang['admin.optimization.level'], $this->css_cache_config->get_optimization_level(),
			array(
				new FormFieldSelectChoiceOption($this->lang['admin.low.level'], CSSFileOptimizer::LOW_OPTIMIZATION),
				new FormFieldSelectChoiceOption($this->lang['admin.high.level'], CSSFileOptimizer::HIGH_OPTIMIZATION)
			),
			array(
				'class' => 'top-field',
				'description' => $this->lang['admin.level.clue'],
				'hidden' => !$this->css_cache_config->is_enabled()
			)
		));

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

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['admin.cache.configuration'], 'url' => AdminCacheUrlBuilder::configuration()->rel()));
	}
}
?>
