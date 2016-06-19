<?php
/*##################################################
 *                             config.php
 *                            -------------------
 *   begin                : June 30, 2013
 *   copyright            : (C) 2013 j1.seth
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
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
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="http://www.phpboost.com/forum/">PHPBoost</a>.';
?>
