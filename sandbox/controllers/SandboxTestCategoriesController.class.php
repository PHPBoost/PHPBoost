<?php
/*##################################################
 *                       SandboxTestCategoriesController.class.php
 *                            -------------------
 *   begin                : January 31, 2013
 *   copyright            : (C) 2013 Kévin MASSY
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

class SandboxTestCategoriesController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$view = new StringTemplate('# INCLUDE FORM #');
		$form = $this->build_form();
		
		$view->put('FORM', $form->display());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{
		$form = new HTMLForm('sandboxCats');

		$fieldset = new FormFieldsetHTML('fieldset_1', 'Fieldset');
		$form->add_fieldset($fieldset);
		
		$cat = new CategoriesManager(ArticlesCategoriesCache::load());
		
		$fieldset->add_field($cat->get_select_categories_form_field('test', 'Choix catégorie sans autorisations particulières', '0', new SearchCategoryChildrensOptions()));
		
		$options = new SearchCategoryChildrensOptions();
		$options->add_authorisations_bits(2);
		$fieldset->add_field($cat->get_select_categories_form_field('test2', 'Choix catégorie avec autorisations contribution', '0', $options));
		
		$options = new SearchCategoryChildrensOptions();
		$options->add_authorisations_bits(2);
		$options->add_authorisations_bits(4);
		$fieldset->add_field($cat->get_select_categories_form_field('test3', 'Choix catégorie avec autorisations écriture et contribution sans vérification des deux bits', '0', $options));
		
		$options = new SearchCategoryChildrensOptions();
		$options->add_authorisations_bits(2);
		$options->add_authorisations_bits(4);
		$options->set_check_all_bits(true);
		$fieldset->add_field($cat->get_select_categories_form_field('test4', 'Choix catégorie avec autorisations écriture et contribution et vérification des deux bits', '0', $options));
		
		$options = new SearchCategoryChildrensOptions();
		$options->set_enable_recursive_exploration(false);
		$fieldset->add_field($cat->get_select_categories_form_field('test5', 'Choix catégorie recherche non recursive en partant de root', '0', $options));
		
		$options = new SearchCategoryChildrensOptions();
		$options->set_add_category_in_list(false);
		$fieldset->add_field($cat->get_select_categories_form_field('test6', 'Choix catégorie recherche recursive en partant de root sans l\'inclure', '0', $options));
		
		$options = new SearchCategoryChildrensOptions();
		$options->add_authorisations_bits(2);
		$options->add_authorisations_bits(4);
		$options->set_check_all_bits(true);
		$options->set_add_category_in_list(false);
		$cats = $cat->get_childrens(0, $options);
		
		$ids = 'ID : ';
		foreach ($cats as $id => $category)
		{
			$ids .= ' '. $id;
		}
		$fieldset->add_field(new FormFieldFree('test7', 'Affichage liste des enfants de root sans inclure la racine avec vérifications des autorisations d\'écriture et de contribution', $ids));
		
		$cats = $cat->get_parents(3);
		$ids = 'ID : ';
		foreach ($cats as $id => $category)
		{
			$ids .= ' '. $id;
		}
		$fieldset->add_field(new FormFieldFree('test8', 'Affichage liste des parents de test2 (#3)', $ids));
		
		return $form;
	}
}
?>
