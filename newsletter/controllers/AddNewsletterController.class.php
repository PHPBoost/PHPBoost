<?php
/*##################################################
 *                         AddNewsletterController.class.php
 *                            -------------------
 *   begin                : February 8, 2011
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

class AddNewsletterController extends ModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		
		$tpl = new FileTemplate('newsleter/AddNewsletterController.tpl');
		$tpl->add_lang($this->lang);

		$tpl->put_all(array(
			'BBCODE_LINK' => DispatchManager::get_url('/newsletter/index.php', '/add/bbcode'),
			'HTML_LINK' => DispatchManager::get_url('/newsletter/index.php', '/add/html'),
			'TEXT_LINK' => DispatchManager::get_url('/newsletter/index.php', '/add/text'),
		));
		
		
		return $this->build_response($view);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}
	
	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter.add-newsletter']);
		return $response;
	}
	
}

?>