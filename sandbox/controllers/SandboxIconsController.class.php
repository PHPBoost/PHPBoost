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
			'facebook',
			'google',
			'twitter',
			'hashtag'
		);
		
		$code = array(
			'\f09a',
			'\f1a0',
			'\f099',
			'\f292'
			
		);
		
		$icon = array_combine($icons, $code);
		
		foreach ($icon as $fa=> $before)
		{
			$this->view->assign_block_vars('social', array('FA' => $fa, 'BEFORE' => $before));
		}
		
		//Responsive
		$icons = array(
			'television',
			'desktop',
			'laptop',
			'tablet',
			'mobile'
		);
		
		$code = array(
			'\f26c',
			'\f108',
			'\f109',
			'\f10a',
			'\f10b'
		);
		
		$icon = array_combine($icons, $code);
		
		foreach ($icon as $fa=> $before)
		{
			$this->view->assign_block_vars('responsive', array('FA' => $fa, 'BEFORE' => $before));
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.icons'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.icons'], SandboxUrlBuilder::icons()->rel());
		
		return $response;
	}
}
?>