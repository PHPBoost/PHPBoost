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
			'fa fa-arrow-right',
			'fa fa-arrow-left',
			'fa fa-arrow-up',
			'fa fa-arrow-down',
			'fa fa-arrows',
			'fa fa-ban',
			'fa fa-bars',
			'fa fa-book',
			'fa fa-calendar',
			'fa fa-caret-down',
			'fa fa-caret-left',
			'fa fa-caret-right',
			'fa fa-check',
			'fa fa-clock-o',
			'fa fa-cloud-upload',
			'fa fa-cog',
			'fa fa-comment',
			'fa fa-comments-o',
			'fa fa-delete',
			'fa fa-download',
			'fa fa-edit',
			'fa fa-edit-sign',
			'fa fa-envelope',
			'fa fa-envelope-o',
			'fa fa-eraser',
			'fa fa-error',
			'fa fa-eye',
			'fa fa-eye-slash',
			'fa fa-facebook',
			'fa fa-fast-forward',
			'fa fa-female',
			'fa fa-file',
			'fa fa-file-text',
			'fa fa-filter',
			'fa fa-flag',
			'fa fa-folder',
			'fa fa-folder-open',
			'fa fa-forbidden',
			'fa fa-gavel',
			'fa fa-google-plus',
			'fa fa-hand-o-right',
			'fa fa-heart',
			'fa fa-home',
			'fa fa-level-down',
			'fa fa-level-up',
			'fa fa-magic',
			'fa fa-male',
			'fa fa-minus',
			'fa fa-minus-square-o',
			'fa fa-move',
			'fa fa-notice',
			'fa fa-offline',
			'fa fa-online',
			'fa fa-pencil',
			'fa fa-plus',
			'fa fa-plus-square-o',
			'fa fa-print',
			'fa fa-question',
			'fa fa-question-circle',
			'fa fa-random',
			'fa fa-refresh',
			'fa fa-remove',
			'fa fa-reply',
			'fa fa-search',
			'fa fa-sign-in',
			'fa fa-sign-out',
			'fa fa-skype',
			'fa fa-sort',
			'fa fa-sort-alpha-asc',
			'fa fa-spinner',
			'fa fa-star',
			'fa fa-star-half-empty',
			'fa fa-star-o',
			'fa fa-success',
			'fa fa-syndication',
			'fa fa-tag',
			'fa fa-tags',
			'fa fa-ticket',
			'fa fa-twitter',
			'fa fa-unban',
			'fa fa-unlink',
			'fa fa-user',
			'fa fa-warning',
			'fa fa-wrench'
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