<?php
/*##################################################
 *                       AdminExtendedFieldMemberRepositionController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
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

class AdminExtendedFieldMemberRepositionController extends AdminController
{
	public function execute(HTTPRequest $request)
	{
		$type = $request->get_getstring('type');
		
		$extended_field = new ExtendedField();
		$extended_field->set_id($request->get_getint('id'));
		$data = ExtendedFieldsDatabaseService::select_data_field_by_id($extended_field);
		
		if ($type == 'top')
		{
			$idbottom = ($data['position'] - 1);
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position = 0 WHERE position='" . $data['position'] . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position=" . $data['position'] . " WHERE position = '" . $idbottom . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position=" . $idbottom . " WHERE position = 0");
		}
		elseif ($type == 'bottom')
		{
			$idtop = ($data['position'] + 1);
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position = 0 WHERE position = '" . $data['position'] . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position = " . $data['position'] . " WHERE position = '" . $idtop . "'");
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET position = " . $idtop . " WHERE position = 0");
		}
		
		ExtendedFieldsCache::invalidate();
		AppContext::get_response()->redirect('/admin/member/index.php?url=/extended-fields/list');
	}
}

?>