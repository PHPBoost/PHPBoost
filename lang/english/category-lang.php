<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 4.0 - 2013 12 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['category.category']       = 'Category';
$lang['category.categories']     = 'Categories';
$lang['category.a.category']     = 'A category';
$lang['category.all.categories'] = 'All categories';
$lang['category.see.category']   = 'See category';

$lang['category.sub.category']   = 'Sub-category';
$lang['category.sub.categories'] = 'Sub-categories';

// Management
$lang['category.categories.management'] = 'Categories management';
$lang['category.categories.manage']     = 'Manage categories';
$lang['category.location']              = 'To be placed in category';
$lang['category.add']                   = 'Add category';
$lang['category.edit']                  = 'Edit category';
$lang['category.delete']                = 'Delete category';

// Form
$lang['category.form.authorizations.clue'] = 'General module authorizations are applied per default. You can apply special permissions.';

// Delete category
$lang['category.content.management'] = 'Category content management';
$lang['category.delete.description'] = 'You are about to delete the category. Two solutions are available to you. You can either move all of its contents (elements and subcategories) in another category or delete the whole category. <strong>Note that this action is irreversible!</strong>';
$lang['category.delete.all.content'] = 'Delete all content';
$lang['category.move.to']            = 'Move content to:';

// Messages
$lang['category.no.element']          = 'No category';
$lang['category.success.add']         = 'The category <b>:name</b> has been added';
$lang['category.success.edit']        = 'The category <b>:name</b> has been modified';
$lang['category.success.delete']      = 'The category <b>:name</b> has been deleted';
$lang['category.delete.confirmation'] = 'Do you really want to delete the category :name?';

// Default root description
$lang['category.default.created.elements.number']   = ':elements_number were created to show you how this module works.';
$lang['category.default.root.category.description'] = '
	Welcome to the website :module_name module.
	<br />
	:created_elements_number Here are some tips to get started on this module.
	<br />
	<ul class="formatter-ul">
		<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a class="offload" href=":configuration_link">module administration</a></li>
		<li class="formatter-li"> To create categories, <a class="offload" href=":add_category_link">clic here</a> </li>
		<li class="formatter-li"> To create :items, <a class="offload" href=":add_item_link">clic here</a></li>
	</ul>
	To learn more, feel free to consult the documentation for the module on <a class="offload" href=":documentation_link">PHPBoost</a> website.
';
?>
