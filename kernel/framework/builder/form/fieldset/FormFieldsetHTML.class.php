<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2009 05 01
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldsetHTML extends AbstractFormFieldset
{
	protected string $title = '';

	/**
	 * constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct(string $id, string $name = '', array $options = [])
	{
		parent::__construct($id, $options);
		$this->title = $name;
	}

	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$template->put_all([
			'C_TITLE' => !empty($this->title),
			'L_TITLE' => $this->title
		]);

		$this->assign_template_fields($template);

		return $template;
	}

	/**
	 * @param string $title The fieldset title
	 */
	public function set_title(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return string The fieldset title
	 */
	public function get_title(): string
	{
		return $this->title;
	}

	protected function get_default_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/FormFieldset.tpl');
	}
}

?>
