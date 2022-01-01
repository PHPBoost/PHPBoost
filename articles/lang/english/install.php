<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 31
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	'item.content' => '
		<p>This brief article will give you some simple tips to take control of this module.</p>
		<ul class="formatter-ul">
			<li class="formatter-li">To configure your module, <a class="offload" href="' . ModulesUrlBuilder::configuration('articles')->relative() . '">click here</a></li>
			<li class="formatter-li">To add categories: <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'articles')->relative() . '">click here</a> (categories and subcategories are infinitely)</li>
			<li class="formatter-li">To add an item: <a class="offload" href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'articles')->relative() . '">click here</a></li>
		</ul>
		<ul class="formatter-ul">
			<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a class="offload" href="https://www.phpboost.com/wiki/bbcode">article</a>)<br /></li>
		</ul>
		<p>For more information, please see the module documentation on the site <a class="offload" href="https://www.phpboost.com">PHPBoost</a>.</p>
		<br />
		<br />
		Enjoy using this module.',
	'item.summary' => ''
);
?>
