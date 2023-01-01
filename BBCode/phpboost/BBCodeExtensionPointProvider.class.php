<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

class BBCodeExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
    {
        parent::__construct('BBCode');
    }

	public function content_formatting()
	{
		return new BBCodeContentFormattingExtensionPoint();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('bbcode.css');
		return $module_css_files;
	}
}
?>
