<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 03
 * @since       PHPBoost 4.1 - 2015 04 13
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                       English                     #
#####################################################

$lang['root_category_description'] = '
	Welcome to the website Articles module.
	<br /><br />
	One category and one article were created to show you how this module works. Here are some tips to get started on this module.
	<br /><br />
	<ul class="formatter-ul">
		<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a href="' . ArticlesUrlBuilder::configuration()->relative() . '">module administration</a></li>
		<li class="formatter-li"> To create categories, <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'articles')->relative() . '">clic here</a></li>
		<li class="formatter-li"> To create articles, <a href="' . ArticlesUrlBuilder::add_article()->relative() . '">clic here</a></li>
	</ul>
	<br />To learn more, don \'t hesitate to consult the documentation for the module on <a href="https://www.phpboost.com">PHPBoost</a> website.
';
?>
