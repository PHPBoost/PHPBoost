<?php
/*##################################################
 *                           categories-common.php
 *                            -------------------
 *   begin                : February 07, 2013
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 #                     French                       #
 ####################################################

$lang['category'] = 'Catégorie';
$lang['categories'] = 'Catégories';

//Errors
$lang['errors.unexisting'] = 'La catégorie n\'existe pas.';

//Form
$lang['category.form.name'] = 'Nom de la catégorie';
$lang['category.form.parent'] = 'Emplacement de la catégorie';
$lang['category.form.visiblity'] = 'Catégorie visible ?';
$lang['category.form.authorizations'] = 'Autorisations';
$lang['category.form.authorizations.read'] = 'Autorisations de lecture';
$lang['category.form.authorizations.write'] = 'Autorisations d\'écriture';
$lang['category.form.authorizations.contribution'] = 'Autorisations de contribution';
$lang['category.form.authorizations.moderation'] = 'Autorisations de modération';
$lang['category.form.authorizations.description'] = 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.';
$lang['category.form.description'] = 'Description de la catégorie';
$lang['category.form.options'] = 'Options';

//Delete category
$lang['delete.category'] = 'Suppression d\'une catégorie';
$lang['delete.description'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (articles et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de la catégorie. <strong>Attention, cette action est irréversible !</strong>';
$lang['delete.category_and_content'] = 'Supprimer la catégorie et tout son contenu';
$lang['delete.move_in_other_cat'] = 'Déplacer son contenu dans :';
?>