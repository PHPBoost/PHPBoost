<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2012 05 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ExtendedFields implements ExtendedFieldExtensionPoint
{
	private array $extended_fields = [];

	public function __construct(array $extended_fields)
	{
		$this->extended_fields = $extended_fields;
	}

	public function get_extended_fields(): array
	{
		return $this->extended_fields;
	}
}
?>
