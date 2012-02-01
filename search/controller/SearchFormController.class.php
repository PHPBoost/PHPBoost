<?php
/*##################################################
 *                        SearchFormController.class.php
 *                            -------------------
 *   begin                : March 28, 2010
 *   copyright            : (C) 2010 Rouchon Loic
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class SearchFormController extends ModuleController {

	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var string[string]
	 */
	private $lang;

	/**
	 * @var SearchableExtensionPoint[string]
	 */
	private $searchables;

	/**
	 * @var HTMLForm
	 */
	private $form;

	public function __construct()
	{
		$this->view = new FileTemplate('search/SearchFormController.tpl');
		$this->lang = LangLoader::get('main', 'search');
		$this->view->add_lang($this->lang);
	}

	public function execute(HTTPRequest $request)
	{
		$this->init_search_providers();
        $this->build_form();
        $this->execute_search();
		return $this->prepare_to_send();
	}

	private function init_search_providers()
	{
		$eps = AppContext::get_extension_provider_service();
		$ep_list = $eps->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT);
		foreach ($ep_list as $id => $ep)
		{
			$name = null;
			try
			{
				$name = ModuleConfigurationManager::get($id)->get_name();
			}
			catch (Exception $ex)
			{
				$name = $id;
			}
			$this->searchables[$name] = $ep;
		}
		ksort($this->searchables);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('full_search_form');
		$fieldset = new FormFieldsetHTML('search', $this->lang['title']);
		$searched_input_text = new FormFieldTextEditor('searched_text', $this->lang['title'], 'Recherche...');
		$searched_input_text->add_constraint(new FormFieldConstraintLengthRange(3, 300));
		$fieldset->add_field($searched_input_text);
		$this->form->add_fieldset($fieldset);

		$this->build_special_search_options_form_fieldset();

		$button = new FormButtonSubmit($this->lang['do_search'], 'search_submit');
		$this->form->add_button($button);
		return $this->form;
	}

	private function build_special_search_options_form_fieldset()
	{
		foreach ($this->searchables as $searchable)
		{
			if ($searchable->has_search_options())
			{
				$this->form->add_fieldset($searchable->build_search_form());
			}
		}
		ksort($this->searchables);
	}

	private function execute_search()
	{
		$providers = SearchProvidersService::get_providers();
	}

	private function prepare_to_send()
	{
		$this->view->put('SEARCH_FORM', $this->form->display());
		$response = new SiteDisplayResponse($this->view);
		$environment = $response->get_graphical_environment();
		$environment->set_page_title($this->lang['page_title']);
		return $response;
	}
}

?>