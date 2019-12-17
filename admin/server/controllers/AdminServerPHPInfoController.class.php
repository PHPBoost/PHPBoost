<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 04
 * @since       PHPBoost 4.1 - 2015 05 20
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminServerPHPInfoController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		$phpinfo = preg_replace('`^.*<body>`isu', '', $phpinfo);
		$phpinfo = str_replace(array('class="e"', 'class="v"', '<table border="0" cellpadding="3" width="600">', '</body></html>'),
		array('', 'style="overflow:auto;"',  '<table style="table-layout:fixed;">', ''), $phpinfo);
		ob_end_clean();
		ob_start();

		$tpl = new StringTemplate('{PHPINFO}');
		$tpl->put('PHPINFO', $phpinfo);

		return new AdminServerDisplayResponse($tpl, LangLoader::get_message('phpinfo', 'admin'));
	}
}
?>
