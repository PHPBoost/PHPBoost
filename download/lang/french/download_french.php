<?php
/*##################################################
 *                              download_french.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

global $DOWNLOAD_LANG;
$DOWNLOAD_LANG = array();

//Admin
$DOWNLOAD_LANG['download_add'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['download_management'] = 'Gestion Téléchargements';
$DOWNLOAD_LANG['download_config'] = 'Configuration des téléchargements';
$DOWNLOAD_LANG['file_list'] = 'Liste des fichiers';
$DOWNLOAD_LANG['edit_file'] = 'Edition du fichier';
$DOWNLOAD_LANG['nbr_download_max'] = 'Nombre maximum de fichiers affichés';
$DOWNLOAD_LANG['download_date'] = 'Date du fichier <span class="text_small">(jj/mm/aa)</span> <br />
<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)';
$DOWNLOAD_LANG['icon_cat'] = 'Image de la catégorie';
$DOWNLOAD_LANG['explain_icon_cat'] = 'Vous pouvez choisir une image du répertoire download/ ou mettre son adresse dans le champ prévu à cet effet';
$DOWNLOAD_LANG['root_description'] = 'Description de la racine des téléchargements';

//Titre
$DOWNLOAD_LANG['title_download'] = 'Téléchargements';

//DL
$DOWNLOAD_LANG['file'] = 'Fichier';
$DOWNLOAD_LANG['size'] = 'Taille';
$DOWNLOAD_LANG['download'] = 'Téléchargements';
$DOWNLOAD_LANG['none_download'] = 'Aucun fichier dans cette catégorie';
$DOWNLOAD_LANG['xml_download_desc'] = 'Derniers fichiers';
$DOWNLOAD_LANG['no_note'] = 'Aucune note';
$DOWNLOAD_LANG['actual_note'] = 'Note actuelle';
$DOWNLOAD_LANG['vote_action'] = 'Voter';
$DOWNLOAD_LANG['add_on_date'] = 'Ajouté le %s';
$DOWNLOAD_LANG['downloaded_n_times'] = 'Téléchargé %d fois';
$DOWNLOAD_LANG['num_com'] = '%d commentaire';
$DOWNLOAD_LANG['num_coms'] = '%d commentaires';
$DOWNLOAD_LANG['this_note'] = 'Note :';
$DOWNLOAD_LANG['short_contents'] = 'Courte description';
$DOWNLOAD_LANG['complete_contents'] = 'Description complète';
$DOWNLOAD_LANG['url'] = 'Adresse du fichier';
$DOWNLOAD_LANG['confirm_delete_file'] = 'Etes-vous certain de vouloir supprimer ce fichier ?';
$DOWNLOAD_LANG['download_file'] = 'Télécharger le fichier';
$DOWNLOAD_LANG['file_infos'] = 'Informations sur le fichier';
$DOWNLOAD_LANG['insertion_date'] = 'Date d\'ajout';
$DOWNLOAD_LANG['last_update_date'] = 'Date de sortie ou de dernière mise à jour';
$DOWNLOAD_LANG['downloaded'] = 'Téléchargé';
$DOWNLOAD_LANG['n_times'] = '%d fois';
$DOWNLOAD_LANG['num_notes'] = '%d votant(s)';
$DOWNLOAD_LANG['edit_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['delete_file'] = 'Supprimer le fichier';

//Gestion des fichiers
$DOWNLOAD_LANG['files_management'] = 'Gestion des fichiers';
$DOWNLOAD_LANG['file_management'] = 'Modification d\'un fichier';
$DOWNLOAD_LANG['file_addition'] = 'Ajout d\'un fichier';
$DOWNLOAD_LANG['add_file'] = 'Ajouter le fichier';

//Catégories
$DOWNLOAD_LANG['add_category'] = 'Ajouter une catégorie';
$DOWNLOAD_LANG['removing_category'] = 'Suppression d\'une catégorie';
$DOWNLOAD_LANG['explain_removing_category'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (questions et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son catégorie. <strong>Attention, cette action est irréversible !</strong>';
$DOWNLOAD_LANG['delete_category_and_its_content'] = 'Supprimer la catégorie et tout son contenu';
$DOWNLOAD_LANG['move_category_content'] = 'Déplacer son contenu dans :';
$DOWNLOAD_LANG['required_fields'] = 'Les champs marqués * sont obligatoires !';
$DOWNLOAD_LANG['category_name'] = 'Nom de la catégorie';
$DOWNLOAD_LANG['category_location'] = 'Emplacement de la catégorie';
$DOWNLOAD_LANG['cat_description'] = 'Description de la catégorie';
$DOWNLOAD_LANG['num_files_singular'] = '%d fichier';
$DOWNLOAD_LANG['num_files_plural'] = '%d fichiers';

//Autorisations
$DOWNLOAD_LANG['auth_read'] = 'Permissions de lecture';
$DOWNLOAD_LANG['auth_write'] = 'Permissions d\'écriture';
$DOWNLOAD_LANG['special_auth'] = 'Permissions spéciales';
$DOWNLOAD_LANG['special_auth_explain'] = 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.';
$DOWNLOAD_LANG['global_auth'] = 'Permissions globales';
$DOWNLOAD_LANG['global_auth_explain'] = 'Vous définissez ici les permissions globales au module. Vous pourrez changer ces permissions localement sur chaque catégorie';

//Erreurs
$DOWNLOAD_LANG['successful_operation'] = 'L\'opération que vous avez demandée a été effectuée avec succès';
$DOWNLOAD_LANG['required_fields_empty'] = 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement';
$DOWNLOAD_LANG['unexisting_category'] = 'La catégorie que vous avez sélectionnée n\'existe pas';
$DOWNLOAD_LANG['new_cat_does_not_exist'] = 'La catégorie cible n\'existe pas';
$DOWNLOAD_LANG['infinite_loop'] = 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie';
$DOWNLOAD_LANG['recount_success'] = 'Le nombre de fichiers pour chaque catégorie a été recompté avec succès.';

//Erreurs
$LANG['e_unexist_file_download'] = 'Le fichier que vous demandez n\'existe pas !';
?>