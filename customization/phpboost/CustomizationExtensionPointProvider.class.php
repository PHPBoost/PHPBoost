<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 07 07
*/

class CustomizationExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('customization');
	}

	public function tree_links()
	{
		return new CustomizationTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/customization/index.php', '([\w/_-]*(?:\.css)?)$')));
	}
}
?>
