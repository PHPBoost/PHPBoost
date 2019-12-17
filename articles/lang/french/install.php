<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 03
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

#####################################################
#                       French                      #
#####################################################

$lang = array();

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Articles de démonstration';
$lang['default.article.title'] = 'Débuter avec le module Articles';
$lang['default.article.description'] = '';
$lang['default.article.contents'] = 'Ce bref article va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">Pour configurer votre module, <a href="' . ArticlesUrlBuilder::configuration()->rel() . '">cliquez ici</a>
</li><li class="formatter-li">Pour ajouter des catégories : <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'articles')->rel() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)
</li><li class="formatter-li">Pour ajouter un article : <a href="' . ArticlesUrlBuilder::add_article()->rel() . '">cliquez ici</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/articles">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.';

?>
