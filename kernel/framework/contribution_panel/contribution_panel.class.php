<?php
/*##################################################
 *                             contribution_panel.class.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright          : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once(PATH_TO_ROOT . '/kernel/framework/contribution_panel/contribution.class.php');

define('CONTRIBUTION_STATUS_NOT_READ', 1);
define('CONTRIBUTION_STATUS_PROCESSING', 2);
define('CONTRIBUTION_STATUS_SETTLED', 3);

class Contribution_panel
{
	function Contribution_panel()
	{
		
	}
	
	function add_contribution(&$contribution)
	{
		$contribution->create_in_db();
		return $contribution->get_id();
	}
	
	function update_contribution(&$contribution)
	{
		$contribution->update_in_db();
	}
	
	function delete_contribution(&$contribution)
	{
		$contribution->delete_in_db();
	}
}

?>