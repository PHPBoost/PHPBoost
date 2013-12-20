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
		$icons = array(
			'icon-arrow-right',
			'icon-arrow-left',
			'icon-arrow-up',
			'icon-arrow-down',
			'icon-arrows',
			'icon-ban',
			'icon-bars',
			'icon-book',
			'icon-calendar',
			'icon-caret-down',
			'icon-caret-left',
			'icon-caret-right',
			'icon-check',
			'icon-clock-o',
			'icon-cloud-upload',
			'icon-cog',
			'icon-comment',
			'icon-comments-o',
			'icon-delete',
			'icon-download',
			'icon-edit',
			'icon-edit-sign',
			'icon-envelope',
			'icon-envelope-o',
			'icon-eraser',
			'icon-error',
			'icon-eye',
			'icon-eye-slash',
			'icon-facebook',
			'icon-fast-forward',
			'icon-female',
			'icon-file',
			'icon-file-text',
			'icon-filter',
			'icon-flag',
			'icon-folder',
			'icon-folder-open',
			'icon-forbidden',
			'icon-gavel',
			'icon-google-plus',
			'icon-hand-o-right',
			'icon-heart',
			'icon-home',
			'icon-level-down',
			'icon-level-up',
			'icon-magic',
			'icon-male',
			'icon-minus',
			'icon-minus-square-o',
			'icon-move',
			'icon-notice',
			'icon-offline',
			'icon-online',
			'icon-pencil',
			'icon-plus',
			'icon-plus-square-o',
			'icon-print',
			'icon-question',
			'icon-question-circle',
			'icon-random',
			'icon-refresh',
			'icon-remove',
			'icon-reply',
			'icon-search',
			'icon-sign-in',
			'icon-sign-out',
			'icon-skype',
			'icon-sort',
			'icon-sort-alpha-asc',
			'icon-spinner',
			'icon-star',
			'icon-star-half-empty',
			'icon-star-o',
			'icon-success',
			'icon-syndication',
			'icon-tag',
			'icon-tags',
			'icon-ticket',
			'icon-twitter',
			'icon-unban',
			'icon-unlink',
			'icon-user',
			'icon-warning',
			'icon-wrench'
		);
		
		foreach ($icons as $icon)
		{
			$this->view->assign_block_vars('icons', array('CLASS' => $icon));
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module_title'] . ' - ' . $this->lang['title.icons']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.icons'], SandboxUrlBuilder::icons()->rel());
		
		return $response;
	}
}
?>