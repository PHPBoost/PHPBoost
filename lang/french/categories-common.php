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

//Management
$lang['categories.management'] = 'Gestion des catégories';
$lang['categories.manage'] = 'Gérer les catégories';
$lang['category.add'] = 'Ajouter une catégorie';
$lang['category.edit'] = 'Modifier une catégorie';
$lang['category.delete'] = 'Supprimer une catégorie';
$lang['category.move_up'] = 'Monter';
$lang['category.move_down'] = 'Descendre';
$lang['category.update_position'] = 'Valider les positions';

//Errors
$lang['errors.unexisting'] = 'La catégorie n\'existe pas.';
$lang['message.no_element'] = 'Aucun élément existante';

//Form
$lang['category.form.name'] = 'Nom';
$lang['category.form.rewrited_name'] = 'Nom réécrit dans l\'url';
$lang['category.form.rewrited_name.description'] = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['category.form.rewrited_name.personalize'] = 'Personnaliser le nom dans l\'url';
$lang['category.form.parent'] = 'Emplacement';
$lang['category.form.authorizations.description'] = 'Les autorisations générales du module s\'appliquent par défaut. Vous pouvez appliquer des permissions particulières.';
$lang['category.form.description'] = 'Description';
$lang['category.form.picture'] = 'Image';

//Delete category
$lang['delete.description'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (articles et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de la catégorie. <strong>Attention, cette action est irréversible !</strong>';
$lang['delete.category_and_content'] = 'Supprimer tout le contenu';
$lang['delete.move_in_other_cat'] = 'Déplacer le contenu dans :';
?>