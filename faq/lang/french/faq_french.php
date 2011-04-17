<?php
/*##################################################
 *                              faq_french.php
 *                            -------------------
 *   begin                : October 20, 2007
 *   copyright            : (C) 2007 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
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
#                                                          French                                                                        #
 ####################################################

global $FAQ_LANG;
$FAQ_LANG = array();

//Généralités
$FAQ_LANG['faq'] = 'FAQ';
$FAQ_LANG['faq_no_question_here'] = 'Aucune question présente dans cette catégorie';
$FAQ_LANG['faq_page_title'] = 'FAQ - %s';
$FAQ_LANG['cat_name'] = 'Nom de la catégorie';
$FAQ_LANG['num_questions_singular'] = '%d question';
$FAQ_LANG['num_questions_plural'] = '%d questions';
$FAQ_LANG['url_of_question'] = 'URL de la question';

//Gestion
$FAQ_LANG['cat_properties'] = 'Propriétés de la catégorie';
$FAQ_LANG['cat_description'] = 'Description';
$FAQ_LANG['go_back_to_cat'] = 'Retour à la catégorie';
$FAQ_LANG['display_mode'] = 'Mode d\'affichage';
$FAQ_LANG['display_block'] = 'Par blocs';
$FAQ_LANG['display_inline'] = 'En lignes';
$FAQ_LANG['display_auto'] = 'Automatique';
$FAQ_LANG['display_explain'] = 'En automatique l\'affichage suivra la configuration générale, en ligne les réponses seront masquées et un clic sur la question affichera la réponse correspondante tandis que en blocs les questions seront suivies de leurs réponses.';
$FAQ_LANG['global_auth'] = 'Autorisations spéciales';
$FAQ_LANG['global_auth_explain'] = 'Permet d\'appliquer des autorisations particulières à la catégorie. Attention les autorisations de lecture se transmettent dans les sous catégories, c\'est-à-dire que si vous ne pouvez pas voir une catégorie vous ne pouvez pas voir ses filles.';
$FAQ_LANG['read_auth'] = 'Autorisations de lecture';
$FAQ_LANG['write_auth'] = 'Autorisations d\'écriture';
$FAQ_LANG['questions_list'] = 'Liste des questions';
$FAQ_LANG['ranks'] = 'Rangs';
$FAQ_LANG['insert_question'] = 'Insérer une question';
$FAQ_LANG['insert_question_begening'] = 'Insérer une question au début';
$FAQ_LANG['update'] = 'Modifier';
$FAQ_LANG['delete'] = 'Supprimer';
$FAQ_LANG['up'] = 'Monter';
$FAQ_LANG['down'] = 'Descendre';
$FAQ_LANG['confirm_delete'] = 'Etes-vous sûr de vouloir supprimer cette question ?';
$FAQ_LANG['category_management'] = 'Gestion d\'une catégorie';
$FAQ_LANG['category_manage'] = 'Gérer la catégorie';
$FAQ_LANG['question_edition'] = 'Modification d\'une question';
$FAQ_LANG['question_creation'] = 'Création d\'une question';
$FAQ_LANG['question'] = 'Question';
$FAQ_LANG['entitled'] = 'Intitulé';
$FAQ_LANG['answer'] = 'Réponse';

//Management
$FAQ_LANG['faq_management'] = 'Gestion de la FAQ';
$FAQ_LANG['faq_configuration'] = 'Configuration de la FAQ';
$FAQ_LANG['faq_questions_list'] = 'Liste des questions';
$FAQ_LANG['cats_management'] = 'Gestion des catégories';
$FAQ_LANG['add_cat'] = 'Ajouter une catégorie';
$FAQ_LANG['add_question'] = 'Ajouter une question';
$FAQ_LANG['show_all_answers'] = 'Afficher toutes les réponses';
$FAQ_LANG['hide_all_answers'] = 'Cacher toutes les réponses';
$FAQ_LANG['move'] = 'Déplacer';
$FAQ_LANG['moving_a_question'] = 'Déplacement d\'une question';
$FAQ_LANG['target_category'] = 'Catégorie cible';

//Avertissement
$FAQ_LANG['required_fields'] = 'Les champs marqués * sont obligatoires !';
$FAQ_LANG['require_entitled'] = 'Veuillez entrer l\'intitulé de la question';
$FAQ_LANG['require_answer'] = 'Veuillez entrer la réponse';
$FAQ_LANG['require_cat_name'] = 'Veuillez entrer le nom de la catégorie';

//Administration / categories
$FAQ_LANG['category'] = 'Catégorie';
$FAQ_LANG['category_name'] = 'Nom de la catégorie';
$FAQ_LANG['category_location'] = 'Emplacement de la catégorie';
$FAQ_LANG['category_image'] = 'Image de la catégorie';
$FAQ_LANG['removing_category'] = 'Suppression d\'une catégorie';
$FAQ_LANG['explain_removing_category'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (questions et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de sa catégorie. <strong>Attention, cette action est irréversible !</strong>';
$FAQ_LANG['delete_category_and_its_content'] = 'Supprimer la catégorie et tout son contenu';
$FAQ_LANG['move_category_content'] = 'Déplacer son contenu dans :';
$FAQ_LANG['faq_name'] = 'Nom de la FAQ';
$FAQ_LANG['faq_name_explain'] = 'Le nom de la FAQ apparaîtra dans le titre et dans l\'arborescence de chaque page';
$FAQ_LANG['nbr_cols'] = 'Nombre de catégories par colonne';
$FAQ_LANG['nbr_cols_explain'] = 'Ce nombre est le nombre de colonnes dans lesquelles seront présentées les sous catégories d\'une catégorie';
$FAQ_LANG['display_mode_admin_explain'] = 'Vous pouvez choisir la façon dont les questions seront affichées. Le mode en ligne permet d\'afficher les questions et un clic sur la question affiche la réponse, alors que le mode en blocs affiche l\'enchaînement des questions et des réponses. Il sera possible de choisir pour chaque catégorie le mode d\'affichage, il ne s\'agit ici que de la configuration par défaut.';
$FAQ_LANG['general_auth'] = 'Autorisations générales';
$FAQ_LANG['general_auth_explain'] = 'Vous configurez ici les autorisations générales de lecture et d\'écriture sur la FAQ. Vous pourrez ensuite pour chaque catégorie lui appliquer des autorisations particulières.';

//Errors
$FAQ_LANG['successful_operation'] = 'L\'opération que vous avez demandée a été effectuée avec succès';
$FAQ_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement';
$FAQ_LANG['unexisting_category'] = 'La catégorie que vous avez sélectionnée n\'existe pas';
$FAQ_LANG['new_cat_does_not_exist'] = 'La catégorie cible n\'existe pas';
$FAQ_LANG['infinite_loop'] = 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie';

//Module mini
$FAQ_LANG['random_question'] = 'Question aléatoire';
$FAQ_LANG['no_random_question'] = 'Aucune question disponible';

//Others
$FAQ_LANG['recount_success'] = 'Le nombre de questions pour chaque catégorie a été recompté avec succès.';
$FAQ_LANG['recount_questions_number'] = 'Recompter le nombre de questions pour chaque catégorie';

?>