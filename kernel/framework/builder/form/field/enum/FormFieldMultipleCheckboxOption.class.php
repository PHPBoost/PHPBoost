<?php
/**
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2010 11 20
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMultipleCheckboxOption
{
	private string $id = '';
	private string $label = '';

	public function __construct(string $id, string $label)
	{
		$this->id = $id;
		$this->label = $label;
	}

	public function get_id(): string
	{
		return $this->id;
	}

	public function get_label(): string
	{
		return $this->label;
	}
}

?>
