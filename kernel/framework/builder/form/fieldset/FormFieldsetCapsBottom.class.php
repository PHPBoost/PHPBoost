<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2018 10 26
 * @since       PHPBoost 3.0 - 2009 05 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldsetCapsBottom extends AbstractFormFieldset
{
	protected $tag = '';
	/**
	 * constructor
	 * @param string $tag The HTML tag of the fieldset
	 */
	public function __construct($id, $tag = 'div', $options = [])
	{
		parent::__construct($id, $options);
        $this->tag = $tag;
	}

	/**
	 * Return the form
	 * @param Template $template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_template_fields($template);

		$template->put_all(array(
			'L_TAG' => $this->tag
		));

		return $template;
	}

	/**
	 * @param string $tag The fieldset tag
	 */
	public function set_tag($tag)
	{
		$this->tag = $tag;
	}

	/**
	 * @return string The fieldset tag
	 */
	public function get_tag()
	{
		return $this->tag;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldsetCapsBottom.tpl');
	}
}

?>
