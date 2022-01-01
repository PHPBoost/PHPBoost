<?php
/**
 * @package     PHPBoost
 * @subpackage  User\authentication
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 01 21
 * @since       PHPBoost 5.1 - 2018 01 08
*/

class ExternalAuthenticationsExtensionPoint implements ExtensionPoint
{
	const EXTENSION_POINT = 'external_authentications';

	protected $external_authentications = array();

	/**
	 * @param array Contains a table with instances of the class ExternalAuthentication
	 */
	public function __construct(array $external_authentications)
	{
		$this->external_authentications = $external_authentications;
	}

	/**
	 * @return array Contains a table with instances of the class ExternalAuthentication
	 */
	public function get_external_authentications()
	{
		return $this->external_authentications;
	}
}
?>
