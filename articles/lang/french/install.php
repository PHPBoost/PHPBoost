<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 16
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                       French                      #
#####################################################

$lang['categories'] = $lang['items'] = [];

$lang['categories'][] = array(
	'category.name'        => 'Catégorie de test',
	'category.description' => 'Articles de démonstration'
);

$lang['items'][] = array(
	'item.title'   => 'Débuter avec le module Articles',
	'item.content' => '
		<p>Ce bref article va vous donner quelques conseils simples pour prendre en main ce module.</p>
		<ul class="formatter-ul">
			<li class="formatter-li">Pour configurer votre module, <a class="offload" href="' . ModulesUrlBuilder::configuration('articles')->relative() . '">cliquez ici</a></li>
			<li class="formatter-li">Pour ajouter des catégories : <a class="offload" href="' . CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, 'articles')->relative() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)</li>
			<li class="formatter-li">Pour ajouter un article : <a class="offload" href="' . ItemsUrlBuilder::add(Category::ROOT_CATEGORY, 'articles')->relative() . '">cliquez ici</a></li>
		</ul>
		<ul class="formatter-ul">
			<li class="formatter-li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a class="offload" href="https://www.phpboost.com/wiki/bbcode">article</a>)<br /></li>
		</ul>
		<p>Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href="https://www.phpboost.com/wiki/articles">PHPBoost</a>.</p>
		<br />
		<br />
		Bonne utilisation de ce module.',
	'item.summary' => ''
);
?>
