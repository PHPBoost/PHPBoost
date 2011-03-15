<?php
/*##################################################
 *                      AdminNewsletterDeleteStreamController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class AdminNewsletterDeleteStreamController extends AdminController
{
	public function execute(HTTPRequest $request)
	{
		$id = $request->get_int('id', 0);
		
		if ($this->stream_exist($id) || $id !== 0)
		{
			PersistenceContext::get_querier()->inject(
				"DELETE FROM " . NewsletterSetup::$newsletter_table_streams . " WHERE id = :id"
				, array(
					'id' => $id,
			));
			
			// TODO MAJ SUBSCRIBERS
			
			NewsletterStreamsCache::invalidate();
			
			$controller = new UserErrorController(LangLoader::get_message('success', 'errors'), LangLoader::get_message('admin.success-delete-categorie', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), LangLoader::get_message('admin.categorie-not-existed', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
	}
	
	private static function stream_exist($id)
	{
		$exist_stream = NewsletterStreamsCache::load()->get_existed_stream($id)
		return $exist_stream;
	}
}

?>