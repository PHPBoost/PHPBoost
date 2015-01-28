<?php
/*##################################################
 *                      NewsletterDeleteArchiveController.class.php
 *                            -------------------
 *   begin                : January 27, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class NewsletterDeleteArchiveController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$id_stream = $request->get_int('id_stream', 0);
		
		$db_querier = PersistenceContext::get_querier();

		if ($this->archive_exist($id) || $id_stream !== 0 && $id !== 0)
		{
			if (!NewsletterAuthorizationsService::id_stream($id_stream)->moderation_subscribers())
			{
				NewsletterAuthorizationsService::get_errors()->moderation_archives();
			}
			
			NewsletterService::delete_newsletter($id);
			
			AppContext::get_response()->redirect($request->get_getvalue('redirect', NewsletterUrlBuilder::archives($id_stream)));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error-archive-not-existed']);
			DispatchManager::redirect($controller);
		}
	}
	
	private static function archive_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
	}
}
?>