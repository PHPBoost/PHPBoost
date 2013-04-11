<?php
/*##################################################
 *                       AdminContactExtendedFieldRepositionController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminContactExtendedFieldRepositionController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$type = $request->get_getstring('type');
		
		$extended_field = new ExtendedField();
		$extended_field->set_id($request->get_getint('id'));
		$data = ContactExtendedFieldsDatabaseService::select_data_field_by_id($extended_field);
		
		if ($type == 'top')
		{
			$idbottom = ($data['position'] - 1);
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position = 0 WHERE position='" . $data['position'] . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position=" . $data['position'] . " WHERE position = '" . $idbottom . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position=" . $idbottom . " WHERE position = 0");
		}
		elseif ($type == 'bottom')
		{
			$idtop = ($data['position'] + 1);
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position = 0 WHERE position = '" . $data['position'] . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position = " . $data['position'] . " WHERE position = '" . $idtop . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . ContactSetup::$contact_extended_fields_table . " SET position = " . $idtop . " WHERE position = 0");
		}
		
		ContactExtendedFieldsCache::invalidate();
		AppContext::get_response()->redirect(ContactUrlBuilder::manage_fields());
	}
}

?>