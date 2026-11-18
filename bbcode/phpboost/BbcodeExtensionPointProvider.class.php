<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 10 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BbcodeExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
    {
        parent::__construct('bbcode');
    }

	public function content_formatting(): BbcodeContentFormattingExtensionPoint
	{
		return new BbcodeContentFormattingExtensionPoint();
	}

	public function css_files(): ModuleCssFiles
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('bbcode.css');
		return $module_css_files;
	}
}
?>
