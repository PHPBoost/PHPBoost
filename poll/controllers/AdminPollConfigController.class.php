<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 04
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminPollConfigController extends DefaultConfigurationController
{
	protected function add_additional_fieldsets(&$form)
	{
		$fieldset = new FormFieldsetHTML('advanced_config', $this->lang['poll.config.advanced.configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('cookie_name', $this->lang['poll.config.cookie.name'], $this->config->get_cookie_name(),
			array('maxlength' => 25, 'required' => true, 'class' => 'top-field'),
			array(new FormFieldConstraintRegex('`^[a-z0-9_-]+$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('cookie_lenght', $this->lang['poll.config.cookie.lenght'], $this->config->get_cookie_lenght(),
			array('min' => 1, 'required' => true, 'class' => 'top-field'),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		if (!empty($this->get_selected_items()))
		{
			$fieldset->add_field(new FormFieldCheckbox('mini_module_activating', $this->lang['poll.config.enable.mini.module'], $this->config->get_mini_module_activating(),
				array(
					'class' => 'custom-checkbox top-field',
					'events' => array('click' =>'
						if (HTMLForms.getField("mini_module_activating").getValue()) {
							HTMLForms.getField("mini_module_selected_items").enable();
						} else {
							HTMLForms.getField("mini_module_selected_items").disable();
						}'
					)
				)
			));

			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$categories_cache = CategoriesService::get_categories_manager('poll')->get_categories_cache();

			$fieldset->add_field(
			  new FormFieldCategoriesMapAndItemsSelect(
			    'mini_module_selected_items',
			    $this->lang['poll.config.mini.module.selected.items'],
			    Category::ROOT_CATEGORY,
			    $search_category_children_options,
			    $this->get_selected_items(),
			    array(
			        'hidden' => empty($this->get_selected_items()),
			        'required' => true,
			        'description' => $this->lang['poll.config.mini.module.selected.items.clue']
		        ),
			    $categories_cache
		    ));
		}
		else
		{
			$fieldset->add_field(new FormFieldFree('no_item_in_mini', $this->lang['poll.config.enable.mini.module'], MessageHelper::display($this->lang['poll.message.no.mini'], MessageHelper::WARNING)->render()));
		}
	}

	protected function add_additional_actions_authorization()
	{
		return array(
		  new ActionAuthorization($this->lang['poll.config.vote.authorization'], PollConfig::VOTE_AUTHORIZATIONS),
		  new ActionAuthorization($this->lang['poll.config.votes.result.authorization'], PollConfig::DISPLAY_VOTES_RESULT_AUTHORIZATIONS
		));
	}

	protected function save_additional_fields()
	{
		if (!empty($this->get_selected_items()))
		  	$this->config->set_mini_module_activating($this->form->get_value('mini_module_activating'));
		else
		  	$this->config->set_mini_module_activating(false);

		$this->config->set_mini_module_selected_items($this->get_selected_items());
		$this->config->set_cookie_name($this->form->get_value('cookie_name'));
		$this->config->set_cookie_lenght($this->form->get_value('cookie_lenght'));
	}

	protected function get_selected_items()
	{
		$request = AppContext::get_request();
		if ($request->has_postparameter('poll_config_form_cookie_name'))
		{
			$selected_items = array();
			if ($request->has_postparameter('poll_config_form_mini_module_selected_items'))
			{
				$pre_selected_items = $request->get_array('poll_config_form_mini_module_selected_items');
				foreach ($pre_selected_items as $id_item)
				{
					$selected_items[] = (string) $id_item;
				}
			}
		}
		else
		{
			$selected_items = $this->config->get_mini_module_selected_items();
		}

		return $selected_items;
	}
}
?>
