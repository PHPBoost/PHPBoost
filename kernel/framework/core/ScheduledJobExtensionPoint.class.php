<?php
/*##################################################
 *                       ScheduledJobExtensionPoint.class.php
 *                            -------------------
 *   begin                : October 16, 2010
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

interface ScheduledJobExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'scheduled_jobs';

	/**
	 * @desc Execute daily commands. This extension point is called by the first request of the day.
	 * @param Date $yesterday date representing yesterday
	 * @param Date $today date representing today
	 */
	function on_changeday(Date $yesterday, Date $today);
	
	/**
	 * @desc Execute commands to change page.
	 */
	function on_changepage();
	
	/**
	 * @desc Execute commands to new session start.
	 * @param bool $new_visitor true if the new session start with a new visitor
	 * @param bool $is_robot true if the new session start with a robot visitor
	 */
	function on_new_session($new_visitor, $is_robot);
}
?>