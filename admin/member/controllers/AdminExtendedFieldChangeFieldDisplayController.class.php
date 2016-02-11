<?php
/*##################################################
 *                          AdminExtendedFieldChangeFieldDisplayController.class.php
 *                            -------------------
 *   begin                : March 4, 2015
 *   copyright            : (C) 2013 Julien BRISWALTER
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

class AdminExtendedFieldChangeFieldDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$display = $request->get_bool('display', true);
		
		if ($id !== 0)
		{
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, array('display' => (int)$display), 'WHERE id = :id', array(
				'id' => $id
			));
			ExtendedFieldsCache::invalidate();
		}
		
		return new JSONResponse(array('id' => $id, 'display' => (int)$display));
	}
}
?>
