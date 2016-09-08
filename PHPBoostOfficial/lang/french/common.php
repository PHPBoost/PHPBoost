<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
 ####################################################

$cache = PHPBoostOfficialCache::load();

$lang['module_title'] = 'PHPBoost - Site Officiel';
$lang['site_description'] = 'Créez votre site Internet facilement en moins de 5 minutes';
$lang['site_slide_description'] = 'PHPBoost est un système de gestion de contenu (CMS) français et libre, qui existe depuis 2005, vous permettant de créer facilement votre site Internet. Très complet en terme de fonctionnalités il est cependant simple à utiliser. A l\'usage il s\'avère être un CMS fiable et robuste, optimisé pour le référencement et personnalisable à souhait.';
$lang['versions'] = 'Versions successives de PHPBoost';
$lang['versions.explain'] = 'Permet de mettre à jour automatiquement la page de téléchargements du site';
$lang['major_version_number'] = 'Numéro de version majeure';
$lang['minor_version_number'] = 'Numéro de version mineure';
$lang['minimal_php_version'] = 'Version PHP minimale';

$lang['phpboost_features'] = 'Fonctionnalités de PHPBoost';
$lang['phpboost_features.explain'] = 'Découvrir les fonctionnalités de PHPBoost';
$lang['last_modules'] = 'Les derniers modules';
$lang['last_themes'] = 'Les derniers thèmes';
$lang['modules_for_phpboost'] = 'Modules pour PHPBoost';
$lang['themes_for_phpboost'] = 'Thèmes pour PHPBoost';
$lang['discover_other_modules'] = 'Découvrir les autres modules';
$lang['discover_other_themes'] = 'Découvrir les autres thèmes';
$lang['version'] = 'Version';
$lang['try'] = 'Essayer';
$lang['demo'] = 'Démonstration de PHPBoost';
$lang['demo.website'] = 'Site démo';
$lang['download'] = 'Télécharger';
$lang['download.phpboost'] = 'Télécharger PHPBoost';
$lang['download.display_tree'] = 'Parcourir l\'arborescence';
$lang['download.display_root_cat'] = 'Afficher l\'accueil des téléchargements';
$lang['download.updates_phpboost'] = 'Mises à jour PHPBoost';
$lang['download.modules_phpboost'] = 'Modules PHPBoost';
$lang['download.themes_phpboost'] = 'Thèmes PHPBoost';
$lang['download.module_category.description'] = 'Modules compatibles avec PHPBoost';
$lang['download.theme_category.description'] = 'Thèmes compatibles avec PHPBoost';
$lang['download.updates'] = 'Mises à jour';
$lang['download.updates.description'] = 'Mise à jour et migration';
$lang['download.compatible_modules'] = 'Modules compatibles';
$lang['download.compatible_modules.description'] = 'Donnez de nouvelles fonctionnalités à votre site.';
$lang['download.compatible_themes'] = 'Thèmes compatibles';
$lang['download.compatible_themes.description'] = 'Trouvez la bonne entité graphique pour votre site.';
$lang['download.pdk_version'] = 'La version pour développeurs';
$lang['download.pdk_version_txt'] = 'Télécharger la version pour développeurs (PDK)';
$lang['download.last_version_pdk'] = $cache->get_last_version_major_version_number() . ' PDK';
$lang['download.previous_version_pdk'] = $cache->get_previous_version_major_version_number() . ' PDK';
$lang['download.last_major_version_number'] = $cache->get_last_version_major_version_number();
$lang['download.phpboost_last_major_version_number'] = 'PHPBoost ' . $cache->get_last_version_major_version_number();
$lang['download.last_complete_version_number'] = $cache->get_last_version_major_version_number() . '.' . $cache->get_last_version_minor_version_number();
$lang['download.phpboost_last_complete_major_version_number'] = 'PHPBoost ' . $cache->get_last_version_major_version_number() . '.' . $cache->get_last_version_minor_version_number();
$lang['download.last_minimal_php_version'] = 'PHP ' . $cache->get_last_version_minimal_php_version();
$lang['download.last_version_name'] = $cache->get_last_version_name();
$lang['download.last_version_download_link'] = $cache->get_last_version_download_link();
$lang['download.last_version_updates_cat_link'] = $cache->get_last_version_updates_cat_link();
$lang['download.last_version_pdk_link'] = $cache->get_last_version_pdk_link();
$lang['download.last_version_modules_cat_link'] = $cache->get_last_version_modules_cat_link();
$lang['download.last_version_themes_cat_link'] = $cache->get_last_version_themes_cat_link();
$lang['download.previous_major_version_number'] = $cache->get_previous_version_major_version_number();
$lang['download.phpboost_previous_major_version_number'] = 'PHPBoost ' .  $cache->get_previous_version_major_version_number();
$lang['download.previous_complete_version_number'] = $cache->get_previous_version_major_version_number() . '.' . $cache->get_previous_version_minor_version_number();
$lang['download.phpboost_previous_complete_major_version_number'] = 'PHPBoost ' . $cache->get_previous_version_major_version_number() . '.' . $cache->get_previous_version_minor_version_number();
$lang['download.previous_minimal_php_version'] = $cache->get_previous_version_minimal_php_version();
$lang['download.previous_version_name'] = $cache->get_previous_version_name();
$lang['download.previous_version_download_link'] = $cache->get_previous_version_download_link();
$lang['download.previous_version_updates_cat_link'] = $cache->get_previous_version_updates_cat_link();
$lang['download.previous_version_pdk_link'] = $cache->get_previous_version_pdk_link();
$lang['download.previous_version_modules_cat_link'] = $cache->get_previous_version_modules_cat_link();
$lang['download.previous_version_themes_cat_link'] = $cache->get_previous_version_themes_cat_link();
$lang['download.header.title'] = 'Télécharger PHPBoost';
$lang['download.header.description'] = 'Bienvenue sur la page de téléchargement de PHPBoost.';
$lang['download.page_content.title'] = 'Vous trouverez sur cette page';
$lang['download.page_content.last_stable_version'] = 'La dernière version stable : PHPBoost ' . $cache->get_last_version_major_version_number() . ' et sa version PDK destinée aux développeurs';
$lang['download.page_content.previous_version'] = 'L\'ancienne version PHPBoost ' . $cache->get_previous_version_major_version_number();
$lang['download.page_content.updates'] = 'Mise à jour des versions ' . $cache->get_previous_version_major_version_number() . ' et ' . $cache->get_last_version_major_version_number();
$lang['download.page_content.updates_scripts'] = 'Les scripts de migration pour passer votre site sous PHPBoost ' . $cache->get_previous_version_major_version_number() . ' et ' . $cache->get_last_version_major_version_number();
$lang['download.last_version.description'] = 'La version stable de PHPBoost. A utiliser pour bénéficier de toutes les dernières fonctionnalités implantées.';
$lang['download.previous_version.description'] = 'Pour les nostalgiques, ou pour les personnes ayant besoin de réparer une version ' . $cache->get_previous_version_major_version_number() . ' encore en production.';

$lang['news.phpboost.rss'] = 'Flux RSS des actualités de PHPBoost';
$lang['news.phpboost'] = 'L\'actualité de PHPBoost';
$lang['news.previous_news'] = 'Les news précédentes';
$lang['news.category.description'] = 'Toutes les news concernant PHPBoost';

$lang['partners.title'] = 'Nos partenaires';

?>
