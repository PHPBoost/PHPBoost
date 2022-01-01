<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 07
*/

class ExtendedFields implements ExtendedFieldExtensionPoint
{
	private $extended_fields = array();

	public function __construct(Array $extended_fields)
	{
		$this->extended_fields = $extended_fields;
	}

	public function get_extended_fields()
	{
		return $this->extended_fields;
	}
}
?>
