<?php
/*##################################################
 *                      NewsletterArchiveController.class.php
 *                            -------------------
 *   begin                : March 21, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsletterArchiveController extends ModuleController
{
	private $lang;
	private $view;
	private $contents;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$id = $request->get_int('id', 0);
		
		$archive_exist = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
		if (!$archive_exist)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error-archive-not-existed']);
			DispatchManager::redirect($controller);
		}
		
		$id_stream = PersistenceContext::get_querier()->get_column_value(NewsletterSetup::$newsletter_table_archives, 'stream_id', "WHERE id = '". $id ."'");
		if (!NewsletterAuthorizationsService::id_stream($id_stream)->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}
		
		$this->contents = NewsletterService::display_newsletter($id);
	}
	
	private function init($request)
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->build_form($request);
		$this->view = new StringTemplate($this->contents);
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		return new SiteNodisplayResponse($view);
	}
}
?>