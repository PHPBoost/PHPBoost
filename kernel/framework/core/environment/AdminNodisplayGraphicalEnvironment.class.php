<?php
/*##################################################
 *                  admin_nodisplay_graphical_environment.class.php
 *                            -------------------
 *   begin                : October 15, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



/**
 * @package core
 * @subpackage environment
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AdminNodisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	public function __construct()
	{
		parent::__construct();

		global $LANG, $CONFIG;
		require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/admin.php';
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_header()
	 */
	function display_header()
	{
		self::set_page_localization('');

		$this->process_site_maintenance();
//		$this->check_admin_auth();
	}

	private function check_admin_auth()
	{
		if (!AppContext::get_user()->is_admin())
		{
			exit;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_footer()
	 */
	function display_footer()
	{

	}
}

?>