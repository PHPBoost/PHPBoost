<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 6.0 - 2025 03 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldsetCapsTop extends AbstractFormFieldset
{
	protected string $tag = '';
	/**
	 * constructor
	 * @param string $tag The HTML tag of the fieldset
	 */
	public function __construct(string $id, string $tag = 'div', array $options = [])
	{
		parent::__construct($id, $options);
        $this->tag = $tag;
	}

	/** Return the form */
	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$this->assign_template_fields($template);

		$template->put_all([
			'L_TAG' => $this->tag
		]);

		return $template;
	}

	/**
	 * @param string $tag The fieldset tag
	 */
	public function set_tag(mixed $tag): void
	{
		$this->tag = $tag;
	}

	/**
	 * @return string The fieldset tag
	 */
	public function get_tag(): string
	{
		return $this->tag;
	}

	protected function get_default_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/FormFieldsetCapsTop.tpl');
	}
}

?>
