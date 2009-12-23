<?php
/*##################################################
 *                        AdminSitemapController.class.php
 *                            -------------------
 *   begin                : December 09 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class AdminSitemapController extends AdminController
{
	private $lang = array();
	
	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}
	
	public function execute(HTTPRequest $request)
	{
		$response = new AdminSitemapResponse(self::get_form()->export());
		
		$response->get_graphical_environment()->set_page_title($this->lang['general_config']);

		return $response;
	}
	
	/**
	 * @return Form
	 */
	private function get_form()
	{
		$form = new Form('sitemap_global_config', 'toto', SitemapUrlBuilder::get_general_config());
		$fieldset = new FormFieldset($this->lang['general_config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormCheckbox('toto', array('title' => 'Enable sitemap.xml'),
			array(new FormCheckboxOption('toto', 'enabled', array('title' => 'Enable')))));
			
		$fieldset->add_field(new FormTextEdit('file_life_time', SitemapXMLFileService::get_life_time(), array('title' => 'Life time'), array(
		new IntegerIntervalFormFieldConstraint(1, 50, 'message qui devrait être généré automatiquement'))));
		
		return $form;
	}
}
?>