<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2015 05 20
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerPHPInfoController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		$phpinfo = preg_replace('`^.*<body>`isu', '', $phpinfo);
		$phpinfo = str_replace(['class="e"', 'class="v"', '<table>', '<th>', '</body></html>'],
		['', '',  '<table class="table-no-header phpinfo">', '<td>', ''], $phpinfo);
		ob_end_clean();
		ob_start();

		$tpl = new StringTemplate('{PHPINFO}');
		$tpl->put('PHPINFO', $phpinfo);

		return new AdminServerDisplayResponse($tpl, LangLoader::get_message('admin.phpinfo', 'admin-lang'));
	}
}
?>
