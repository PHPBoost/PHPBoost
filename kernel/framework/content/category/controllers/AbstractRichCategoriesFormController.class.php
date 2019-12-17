<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 4.0 - 2013 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class AbstractRichCategoriesFormController extends AbstractCategoriesFormController
{
	protected function get_options_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new FormFieldRichTextEditor('description', self::$common_lang['form.description'], $this->get_category()->get_description()));

		$fieldset->add_field(new FormFieldUploadPictureFile('image', self::$common_lang['form.picture'], $this->get_category()->get_image()->relative()));
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_description($this->form->get_value('description'));
		$this->get_category()->set_image(new Url($this->form->get_value('image')));
	}
}
?>
