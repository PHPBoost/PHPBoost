<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 4.0 - 2014 09 02
*/

####################################################
#                    French                        #
####################################################

$lang['root.category.description'] = 'Bienvenue dans la FAQ !
<br /><br />
2 catégories et quelques questions ont été créées pour vous montrer le fonctionnement de ce module. Voici quelques conseils pour bien débuter:
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez vous dans l\'<a href="' . FaqUrlBuilder::configuration()->relative() . '">administration du module</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'faq')->relative() . '">cliquez ici</a> </li>
	<li class="formatter-li"> Pour créer des questions, <a href="' . FaqUrlBuilder::add()->relative() . '">cliquez ici</a></li>
</ul>
<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com">PHPBoost</a>.';
?>
