<?php
/*##################################################
 *                       SandboxIconsController.class.php
 *                            -------------------
 *   begin                : May 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class SandboxIconsController extends ModuleController
{
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxIconsController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		//Social
		$icons = array(
			array('fab', 'facebook-f', '\f39e'),
			array('fab', 'google-plus-g', '\f0d5'),
			array('fab', 'twitter', '\f099'),
			array('fas', 'hashtag', '\f292')
		);

		foreach ($icons as $icon)
		{
			$this->view->assign_block_vars('social', array(
					'PREFIX' => $icon[0],
					'FA'     => $icon[1],
					'CODE'   => $icon[2]
			));
		}

		//Responsive
		$icons = array(
			array('fas', 'tv', '\f26c'),
			array('fas', 'desktop', '\f108'),
			array('fas', 'laptop', '\f109'),
			array('fas', 'tablet-alt', '\f3fa'),
			array('fas', 'mobile-alt', '\f3cd')
		);

		foreach ($icons as $icon)
		{
			$this->view->assign_block_vars('responsive', array(
				'PREFIX' => $icon[0],
				'FA'     => $icon[1],
				'CODE'   => $icon[2]
			));
		}
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.icons'], $this->lang['module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.icons'], SandboxUrlBuilder::icons()->rel());

		return $response;
	}
}
?>
