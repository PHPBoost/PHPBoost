<?php
/*##################################################
 *                           controller.class.php
 *                            -------------------
 *   begin                : June 09 2009
 *   copyright         : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

define('CONTROLLER__INTERFACE', 'Controller');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This interface declares the minimalist controler pattern
 * with no actions.
 *
 */
interface Controller
{
	/**
	  * @desc This method will always be called just before the controler action
	  */
	function init();
	/**
	  * @desc This method will always be called just after the controler action
	  */
	function destroy();
	/**
	  * @desc retrieves the page title
	  */
	function get_title();
	/**
	  * @desc retrieves the page breadcrumb
	  */
	function get_bread_crumb();
	
	/**
	  * @desc returns true if the header and footer have to be displayed
	  */
	function is_display_enabled();
	
	/**
	  * @desc Catch all non-catched exceptions thrown by the controller method
	  * matching the current url
	  * @param Exception $exception the thrown exception
	  * @throws Exception
	  */
	function exception_handler($exception);
}
?>