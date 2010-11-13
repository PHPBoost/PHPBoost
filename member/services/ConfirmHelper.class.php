<?php
/*##################################################
 *                       ConfirmHelper.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 
 class ConfirmHelper
 {
	public static function check_activation_pass_exist($key)
	{
		return PersistenceContext::get_sql()->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
	}
	
	public static function update_aprobation($key)
	{
		PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_aprob = 1, activ_pass = '' WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
	}
 
 }
 ?>