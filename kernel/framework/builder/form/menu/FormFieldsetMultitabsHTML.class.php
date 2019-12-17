<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 12
 * @since       PHPBoost 5.2 - 2019 10 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldsetMultitabsHTML extends FormFieldsetHTML
{
	protected $modal = false;

	/**
	 * Return the form
	 * @param Template $template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$template->put_all(array(
			'C_TITLE' => !empty($this->title),
			'C_MODAL' => $this->modal,
			'L_TITLE' => $this->title
		));

		$this->assign_template_fields($template);

		return $template;
	}

	protected function compute_options(array &$options)
	{
		foreach($options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'modal':
					$this->modal = $value;
					unset($options['modal']);
					break;
			}
		}
		parent::compute_options($options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldsetMultitabsHTML.tpl');
	}
}

?>
