<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 4.0 - 2013 06 30
*/

####################################################
#                     English                      #
####################################################

$lang['wiki_name'] = 'Wiki ' . GeneralConfig::load()->get_site_name();
$lang['index_text'] = 'Bienvenue sur le module wiki !<br /><br />
Voici quelques conseils pour bien débuter sur ce module.<br />
<ul class="formatter-ul">
<li class="formatter-li">Pour configurer votre module, rendez vous dans l\'<a href="' . WikiUrlBuilder::configuration()->relative() . '">administration du module</a></li>
<li class="formatter-li">Pour créer des catégories, <a href="' . WikiUrlBuilder::add_category()->relative() . '">cliquez ici</a></li>
<li class="formatter-li">Pour créer des articles, rendez vous <a href="' . WikiUrlBuilder::add()->relative() . '">ici</a></li>
</ul><br /><br />
Pour personnaliser l\'accueil de ce module, <a href="' . WikiUrlBuilder::configuration()->relative() . '">cliquez ici</a><br /><br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/forum/">PHPBoost</a>.';
?>
