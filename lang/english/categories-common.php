<?php
/*##################################################
 *                           categories-common.php
 *                            -------------------
 *   begin                : December 05, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

 ####################################################
 #                     English                      #
 ####################################################

$lang['category'] = 'Category';
$lang['categories'] = 'Categories';

//Management
$lang['categories.management'] = 'Categories management';
$lang['categories.manage'] = 'Manage categories';
$lang['category.add'] = 'Add category';
$lang['category.edit'] = 'Edit category';
$lang['category.delete'] = 'Delete category';

//Page title
$lang['categories.page_title.management'] = 'Categories management of the module :module_name';
$lang['category.page_title.add'] = 'Add category to the module :module_name';
$lang['category.page_title.edit'] = 'Edit category of the module :module_name';
$lang['category.page_title.delete'] = 'Delete category of the module :module_name';

//Errors
$lang['errors.unexisting'] = 'The category doesn\'t exist.';
$lang['message.no_categories'] = 'No category';

//Form
$lang['category.form.name'] = 'Category name';
$lang['category.form.rewrited_name'] = 'Category name in the url';
$lang['category.form.rewrited_name.description'] = 'Only contains lowercase letters, numbers and hyphens.';
$lang['category.form.rewrited_name.personalize'] = 'Personalize category name in the url';
$lang['category.form.parent'] = 'Parent category';
$lang['category.form.authorizations.description'] = 'Default category has the general configuration of the module. You can apply special permissions.';
$lang['category.form.description'] = 'Category description';
$lang['category.form.picture'] = 'Category picture';

//Delete category
$lang['delete.category'] = 'Deleting a category';
$lang['delete.description'] = 'You are about to delete the category. Two solutions are available to you. You can either move all of its contents (articles and subcategories) in another category or delete the whole category. <strong>Note that this action is irreversible!</ strong>';
$lang['delete.category_and_content'] = 'Delete category and all its content';
$lang['delete.move_in_other_cat'] = 'Move its content in :';
?>