<?php
/*##################################################
 *                          DownloadDeadLinkController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
 
class DownloadDeadLinkController extends AbstractController
{
	private $downloadfile;
	
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			try {
				$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
		
		if ($this->downloadfile !== null && (!DownloadAuthorizationsService::check_authorizations($this->downloadfile->get_id_category())->read() || !DownloadAuthorizationsService::check_authorizations()->display_download_link()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->downloadfile !== null && $this->downloadfile->is_visible())
		{
			if (!PersistenceContext::get_querier()->row_exists(PREFIX . 'events', 'WHERE id_in_module=:id_in_module AND module=\'download\' AND current_status = 0', array('id_in_module' => $this->downloadfile->get_id())))
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($this->downloadfile->get_id());
				$contribution->set_entitled(StringVars::replace_vars(LangLoader::get_message('contribution.deadlink', 'common'), array('link_name' => $this->downloadfile->get_name())));
				$contribution->set_fixing_url(DownloadUrlBuilder::edit($this->downloadfile->get_id())->relative());
				$contribution->set_description(LangLoader::get_message('contribution.deadlink_explain', 'common'));
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('download');
				$contribution->set_type('alert');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						DownloadService::get_categories_manager()->get_heritated_authorizations($this->downloadfile->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				
				ContributionService::save_contribution($contribution);
			}
			
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
