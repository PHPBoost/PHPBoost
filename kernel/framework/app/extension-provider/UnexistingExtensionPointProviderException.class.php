<?php
/**
 * @package     PHPBoost
 * @subpackage  Extension-provider
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 02 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UnexistingExtensionPointProviderException extends Exception
{
	public function __construct(string $provider)
	{
		parent::__construct('Extension point provider "' . $provider . '" does not exist');
	}
}
?>
