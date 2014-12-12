<?php
/*##################################################
 *                  AdminNodisplayGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 15, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

/**
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AdminNodisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	/**
	 * {@inheritdoc}
	 */
	function display($content)
	{
		self::set_page_localization('');

		$this->process_site_maintenance();
		$this->check_admin_auth();
				
		echo $content;
	}

	private function check_admin_auth()
	{
		if (!AppContext::get_current_user()->is_admin())
		{
			exit;
		}
	}
}
?>