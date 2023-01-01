<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 03 11
 * @since       PHPBoost 3.0 - 2010 02 17
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldsetHorizontal extends AbstractFormFieldset
{

	public function __construct($id, $options = array())
	{
		parent::__construct($id, $options);
	}

	/**
	 * @return Template
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_template_fields($template);

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/fieldsetelements/FormFieldsetHorizontal.tpl');
	}
}
?>
