<?php
/*##################################################
 *                              AdminServerPHPInfoController.class.php
 *                            -------------------
 *   begin                : May 20, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class AdminServerPHPInfoController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		$phpinfo = preg_replace('`^.*<body>`is', '', $phpinfo);
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
