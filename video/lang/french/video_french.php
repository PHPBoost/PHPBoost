<?php
/*##################################################
 *                              video_french.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                                                          French                                                                        #
####################################################

global $VIDEO_LANG;
$VIDEO_LANG = array();

//Autorisations
$VIDEO_LANG['auth_read'] = 'Permissions de lecture';
$VIDEO_LANG['auth_write'] = 'Permissions d\'écriture';
$VIDEO_LANG['auth_aprob'] = 'Permissions d\'approuver';
$VIDEO_LANG['auth_flood'] = 'Permissions de flooder';
$VIDEO_LANG['auth_edit'] = 'Permissions d\'éditer';
$VIDEO_LANG['auth_delete'] = 'Permissions de supprimer';
$VIDEO_LANG['auth_moderate'] = 'Permissions de modérer';
$VIDEO_LANG['special_auth'] = 'Permissions spéciales';
$VIDEO_LANG['special_auth_explain'] = 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.';

//Généralités
$VIDEO_LANG['video'] = 'Vidéo';
$VIDEO_LANG['all_cats'] = 'Toutes les catégories';

//Management
$VIDEO_LANG['video_management'] = 'Gestion des vidéos';
$VIDEO_LANG['video_configuration'] = 'Configuration des vidéos';
$VIDEO_LANG['video_list'] = 'Liste des vidéos';
$VIDEO_LANG['cats_management'] = 'Gestion des catégories';
$VIDEO_LANG['add_cat'] = 'Ajouter une catégorie';
$VIDEO_LANG['show_all_answers'] = 'Afficher toutes les réponses';
$VIDEO_LANG['hide_all_answers'] = 'Cacher toutes les réponses';
$VIDEO_LANG['move'] = 'Déplacer';
$VIDEO_LANG['moving_a_question'] = 'Déplacement d\'une question';
$VIDEO_LANG['target_category'] = 'Catégorie cible';

//Others
$VIDEO_LANG['recount_success'] = 'Le nombre de vidéos pour chaque catégorie a été recompté avec succès.';
$VIDEO_LANG['recount_video_number'] = 'Recompter le nombre de vidéos pour chaque catégorie';

//Avertissement
$VIDEO_LANG['required_fields'] = 'Les champs marqués * sont obligatoires !';
$VIDEO_LANG['require_entitled'] = 'Veuillez entrer l\'intitulé de la question';
$VIDEO_LANG['require_answer'] = 'Veuillez entrer la réponse';
$VIDEO_LANG['require_cat_name'] = 'Veuillez entrer le nom de la catégorie';

//Administration / categories
$VIDEO_LANG['category'] = 'Catégorie';
$VIDEO_LANG['category_name'] = 'Nom de la catégorie';
$VIDEO_LANG['category_location'] = 'Emplacement de la catégorie';
$VIDEO_LANG['category_image'] = 'Image de la catégorie';
$VIDEO_LANG['removing_category'] = 'Suppression d\'une catégorie';
$VIDEO_LANG['explain_removing_category'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (questions et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son catégorie. <strong>Attention, cette action est irréversible !</strong>';
$VIDEO_LANG['delete_category_and_its_content'] = 'Supprimer la catégorie et tout son contenu';
$VIDEO_LANG['move_category_content'] = 'Déplacer son contenu dans :';
$VIDEO_LANG['faq_name'] = 'Nom de la VIDEO';
$VIDEO_LANG['faq_name_explain'] = 'Le nom de la VIDEO apparaîtra dans le titre et dans l\'arborescence de chaque page';
$VIDEO_LANG['nbr_cols'] = 'Nombre de catégories par colonne';
$VIDEO_LANG['nbr_cols_explain'] = 'Ce nombre est le nombre de colonnes dans lesquelles seront présentées les sous catégories d\'une catégorie';
$VIDEO_LANG['display_mode_admin_explain'] = 'Vous pouvez choisir la façon dont les questions seront affichées. Le mode en ligne permet d\'afficher les questions et un clic sur la question affiche la réponse, alors que le mode en blocs affiche l\'enchaînement des questions et des réponses. Il sera possible de choisir pour chaque catégorie le mode d\'affichage, il ne s\'agit ici que de la configuration par défaut.';
$VIDEO_LANG['general_auth'] = 'Autorisations générales';
$VIDEO_LANG['general_auth_explain'] = 'Vous configurez ici les autorisations générales de lecture et d\'écriture sur la VIDEO. Vous pourrez ensuite pour chaque catégorie lui appliquer des autorisations particulières.';

//Gestion
$VIDEO_LANG['cat_properties'] = 'Propriétés de la catégorie';
$VIDEO_LANG['cat_description'] = 'Description';
$VIDEO_LANG['go_back_to_cat'] = 'Retour à la catégorie';
$VIDEO_LANG['display_mode'] = 'Mode d\'affichage';
$VIDEO_LANG['display_block'] = 'Par blocs';
$VIDEO_LANG['display_inline'] = 'En lignes';
$VIDEO_LANG['display_auto'] = 'Automatique';
$VIDEO_LANG['display_explain'] = 'En automatique l\'affichage suivra la configuration générale, en ligne les réponses seront masquées et un clic sur la question affichera la réponse correspondante tandis que en blocs les questions seront suivies de leurs réponses.';
$VIDEO_LANG['global_auth'] = 'Autorisations spéciales';
$VIDEO_LANG['global_auth_explain'] = 'Permet d\'appliquer des autorisations particulières à la catégorie. Attention les autorisations de lecture se transmettent dans les sous catégories, c\'est-à-dire que si vous ne pouvez pas voir une catégorie vous ne pouvez pas voir ses filles.';
$VIDEO_LANG['read_auth'] = 'Autorisations de lecture';
$VIDEO_LANG['write_auth'] = 'Autorisations d\'écriture';
$VIDEO_LANG['questions_list'] = 'Liste des questions';
$VIDEO_LANG['ranks'] = 'Rangs';
$VIDEO_LANG['insert_question'] = 'Insérer une question';
$VIDEO_LANG['insert_question_begening'] = 'Insérer une question au début';
$VIDEO_LANG['update'] = 'Modifier';
$VIDEO_LANG['delete'] = 'Supprimer';
$VIDEO_LANG['up'] = 'Monter';
$VIDEO_LANG['down'] = 'Descendre';
$VIDEO_LANG['confirm_delete'] = 'Etes-vous sûr de vouloir supprimer cette question ?';
$VIDEO_LANG['category_management'] = 'Gestion d\'une catégorie';
$VIDEO_LANG['category_manage'] = 'Gérer la catégorie';
$VIDEO_LANG['question_edition'] = 'Modification d\'une question';
$VIDEO_LANG['question_creation'] = 'Création d\'une question';
$VIDEO_LANG['question'] = 'Question';
$VIDEO_LANG['entitled'] = 'Intitulé';
$VIDEO_LANG['answer'] = 'Réponse';

//Errors
$VIDEO_LANG['successful_operation'] = 'L\'opération que vous avez demandée a été effectuée avec succès';
$VIDEO_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement';
$VIDEO_LANG['unexisting_category'] = 'La catégorie que vous avez sélectionnée n\'existe pas';
$VIDEO_LANG['new_cat_does_not_exist'] = 'La catégorie cible n\'existe pas';
$VIDEO_LANG['infinite_loop'] = 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie';

?>