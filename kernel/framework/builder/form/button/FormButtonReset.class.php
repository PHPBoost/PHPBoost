<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 19
 * @since       PHPBoost 3.0 - 2010 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormButtonReset implements FormButton
{
	private $value;

	public function __construct($value = '')
	{
		$this->value = $value;

		if (empty($value))
			$this->value = LangLoader::get_message('reset', 'main');
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
