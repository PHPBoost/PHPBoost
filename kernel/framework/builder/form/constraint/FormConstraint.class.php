<?php
/*##################################################
 *                             FormConstraint.class.php
 *                            -------------------
 *   begin                : December 19, 2009
 *   copyright            : (C) 2009 Régis Viarre, Benoit Sautel, Loic Rouchon
 *   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>, Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc 
 * @package {@package}
 */ 
interface FormConstraint 
{
	function validate();

	function get_js_validation();
	
	function get_related_fields();
}

?>