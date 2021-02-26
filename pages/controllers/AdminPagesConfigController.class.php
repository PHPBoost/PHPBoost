<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 26
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class AdminPagesConfigController extends DefaultConfigurationController
{
	protected function add_additional_fields(&$fieldset)
	{
		parent::add_additional_fields($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('views_number_enabled', $this->lang['config.views.number.enabled'], $this->config->get_views_number_enabled(),
			array('class' => 'custom-checkbox')
		));
		$this->additional_fields_list[] = 'views_number_enabled';

		$fieldset->add_field(new FormFieldRichTextEditor('root_category_description', $this->lang['config.root_category_description'], $this->config->get_root_category_description(),
			array('rows' => 8, 'cols' => 47)
		));
		$this->additional_fields_list[] = 'root_category_description';

		$fieldset->add_field(new FormFieldRichTextEditor('default_content', $this->lang['config.item.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47)
		));
		$this->additional_fields_list[] = 'default_content';
	}
}
?>
