<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 14
 * @since       PHPBoost 3.0 - 2011 10 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class TinyMCEExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('TinyMCE');
	}

	public function content_formatting()
	{
		return new TinyMCEContentFormattingExtensionPoint();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('tinymce.css');
		return $module_css_files;
	}
}
?>
