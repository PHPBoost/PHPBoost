<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 11 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldMultipleCheckboxOption
{
	private $id;
	private $label;

	public function __construct($id, $label)
	{
		$this->id = $id;
		$this->label = $label;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_label()
	{
		return $this->label;
	}
}

?>
