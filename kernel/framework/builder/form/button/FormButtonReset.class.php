<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2010 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonReset implements FormButton
{
	private $value;

	public function __construct($value = '')
	{
		$this->value = $value;

		if (empty($value))
			$this->value = LangLoader::get_message('form.reset', 'form-lang');
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		$template = new FileTemplate('framework/builder/form/button/FormButtonReset.tpl');

		$template->put_all(array(
			'L_RESET' => $this->value
		));

		return $template;
	}

	public function set_form_id($form_id) {}
}
?>
