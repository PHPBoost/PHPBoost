<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 11
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

#####################################################
#                       English                     #
#####################################################

$lang['categories'] = $lang['items'] = array();

$lang['categories'][] = array(
	'category.name'        => 'First category',
	'category.description' => 'Article demonstration'
);

$lang['items'][] = array(
	'item.title'   => 'How to begin with the articles module',
	'item.content' => 'This brief article will give you some simple tips to take control of this module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, <a href="' . ModulesUrlBuilder::configuration('articles')->rel() . '">click here</a>
</li><li class="formatter-li">To add categories: <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'articles')->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add an item: <a href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'articles')->rel() . '">click here</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
For more information, please see the module documentation on the site <a href="https://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.',
	'item.summary' => ''
);
?>
