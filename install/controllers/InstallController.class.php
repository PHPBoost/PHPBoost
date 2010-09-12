<?php
/*##################################################
 *                         InstallController.class.php
 *                            -------------------
 *   begin                : September 12 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

abstract class InstallController extends AbstractController
{
	const DEFAULT_LOCALE = 'french';

	protected $lang = array();

	protected function load_lang(HTTPRequest $request)
	{
		$locale = $request->get_string('lang', InstallController::DEFAULT_LOCALE);
		LangLoader::set_locale($locale);
		InstallUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get('install', 'install');
	}
}
?>