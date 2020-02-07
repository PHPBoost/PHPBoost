<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 07
 * @since       PHPBoost 4.0 - 2013 12 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['category'] = 'Category';
$lang['categories'] = 'Categories';
$lang['a.category'] = 'A category';
$lang['see.category'] = 'See category';

$lang['sub_category'] = 'Sub-category';
$lang['sub_categories'] = 'Sub-categories';

//Management
$lang['categories.management'] = 'Categories management';
$lang['categories.manage'] = 'Manage categories';
$lang['category.add'] = 'Add category';
$lang['category.edit'] = 'Edit category';
$lang['category.delete'] = 'Delete category';

//Form
$lang['category.form.authorizations.description'] = 'General module authorizations are applied per default. You can apply special permissions.';

//Delete category
$lang['delete.description'] = 'You are about to delete the category. Two solutions are available to you. You can either move all of its contents (elements and subcategories) in another category or delete the whole category. <strong>Note that this action is irreversible!</ strong>';
$lang['delete.category_and_content'] = 'Delete all content';
$lang['delete.move_in_other_cat'] = 'Move content in:';

//Messages
$lang['category.message.success.add'] = 'The category <b>:name</b> has been added';
$lang['category.message.success.edit'] = 'The category <b>:name</b> has been modified';
$lang['category.message.success.delete'] = 'The category <b>:name</b> has been deleted';
$lang['category.message.delete_confirmation'] = 'Do you really want to delete the category :name?';

//Default root description
$lang['default.created.elements.number'] = ':elements_number were created to show you how this module works.';
$lang['default.root.category.description'] = 'Welcome to the website :module_name module.
<br />
:created_elements_number Here are some tips to get started on this module.
<br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a href=":configuration_link">module administration</a></li>
	<li class="formatter-li"> To create categories, <a href=":add_category_link">clic here</a> </li>
	<li class="formatter-li"> To create :items, <a href=":add_item_link">clic here</a></li>
</ul>
To learn more, feel free to consult the documentation for the module on <a href=":documentation_link">PHPBoost</a> website.
';
?>
