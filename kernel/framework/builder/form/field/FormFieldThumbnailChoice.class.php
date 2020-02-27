<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 27
 * @since       PHPBoost 5.3 - 2020 02 27
*/

class FormFieldThumbnailChoice extends AbstractFormField
{
	protected $default_url = '';

	const NONE  = 'none';
	const DEFAULT_VALUE = 'default';

	public function __construct($id, $label = '', $value = self::NONE, $default_url = '', array $field_options = array(), array $constraints = array())
	{
		$this->default_value = $default_value;
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get('common');

		$tpl = new FileTemplate('framework/builder/form/FormFieldThumbnailChoice.tpl');
		$tpl->add_lang($lang);

		$this->assign_common_template_variables($template);

		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_NONE_CHECKED' => empty($this->get_value()),
			'C_DEFAULT_CHECKED' => $this->get_value() == self::DEFAULT_VALUE,
		 	'C_CUSTOM_CHECKED' => $this->get_value() && $this->get_value() != self::DEFAULT_VALUE,
			'DEFAULT_URL' => $this->default_url,
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
