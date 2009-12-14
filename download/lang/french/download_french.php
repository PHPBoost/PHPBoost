<?php
/*##################################################
 *                            download_french.php
 *                            -------------------
 *   begin                : July 27, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

global $DOWNLOAD_LANG, $LANG;
$DOWNLOAD_LANG = array();

//Gestion des fichiers
$DOWNLOAD_LANG['files_management'] = 'Gestion des fichiers';
$DOWNLOAD_LANG['file_management'] = 'Modification du fichier';
$DOWNLOAD_LANG['file_addition'] = 'Ajout d\'un fichier';
$DOWNLOAD_LANG['add_file'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['update_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['warning_previewing'] = 'Attention, vous prévisualisez la fiche correspondant à votre fichier. Tant que vous ne validez pas vos modifications elles ne seront pas prises en compte.';
$DOWNLOAD_LANG['file_image'] = 'Adresse de l\'image illustrant le fichier';
$DOWNLOAD_LANG['require_description'] = 'Veuillez entrer une description !';
$DOWNLOAD_LANG['require_url'] = 'Veuillez entrer une adresse correcte pour le fichier !';
$DOWNLOAD_LANG['require_creation_date'] = 'Veuillez entrer une date de création au bon format (jj/mm/aa) !';
$DOWNLOAD_LANG['require_release_date'] = 'Veuillez entrer une date de sortie (ou de mise à jour) au bon format (jj/mm/aa) !';
$DOWNLOAD_LANG['download_add'] = 'Ajouter un fichier';
$DOWNLOAD_LANG['download_management'] = 'Gestion Téléchargements';
$DOWNLOAD_LANG['download_config'] = 'Configuration des téléchargements';
$DOWNLOAD_LANG['file_list'] = 'Liste des fichiers';
$DOWNLOAD_LANG['edit_file'] = 'Edition du fichier';
$DOWNLOAD_LANG['nbr_download_max'] = 'Nombre maximum de fichiers affichés par page';
$DOWNLOAD_LANG['nbr_columns_for_cats'] = 'Nombre de colonnes dans lesquelles sont présentées les catégories';
$DOWNLOAD_LANG['download_date'] = 'Date d\'ajout du fichier';
$DOWNLOAD_LANG['release_date'] = 'Date de sortie (ou dernière mise à jour) du fichier';
$DOWNLOAD_LANG['ignore_release_date'] = 'Ignorer la date de sortie du fichier';
$DOWNLOAD_LANG['file_visibility'] = 'Parution du fichier';
$DOWNLOAD_LANG['icon_cat'] = 'Image de la catégorie';
$DOWNLOAD_LANG['explain_icon_cat'] = 'Vous pouvez choisir une image du répertoire download/ ou mettre son adresse dans le champ prévu à cet effet';
$DOWNLOAD_LANG['root_description'] = 'Description de la racine des téléchargements';
$DOWNLOAD_LANG['approved'] = 'Approuvé';
$DOWNLOAD_LANG['hidden'] = 'Caché';
$DOWNLOAD_LANG['number_of_hits'] = 'Nombre de téléchargements';
$DOWNLOAD_LANG['download_method'] = 'Méthode de téléchargement';
$DOWNLOAD_LANG['download_method_explain'] = 'Choisissez de faire une redirection vers le fichier sauf si le fichier s\'affiche dans le navigateur au lieu d\'être téléchargé par le téléchargement et que ce fichier est <strong>sur votre serveur</strong>, dans ce cas, choisissez de forcer le téléchargement';
$DOWNLOAD_LANG['force_download'] = 'Forcer le téléchargement';
$DOWNLOAD_LANG['redirection_up_to_file'] = 'Rediriger vers le fichier';

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
$DOWNLOAD_LANG['num_notes'] = '%d note(s)';
$DOWNLOAD_LANG['edit_file'] = 'Modifier le fichier';
$DOWNLOAD_LANG['delete_file'] = 'Supprimer le fichier';
$DOWNLOAD_LANG['unknown_size'] = 'inconnue';
$DOWNLOAD_LANG['unknown_date'] = 'inconnue';
$DOWNLOAD_LANG['read_feed'] = 'Téléchager';

//Catégories
$DOWNLOAD_LANG['add_category'] = 'Ajouter une catégorie';
$DOWNLOAD_LANG['removing_category'] = 'Suppression d\'une catégorie';
$DOWNLOAD_LANG['explain_removing_category'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (fichiers et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son catégorie. <strong>Attention, cette action est irréversible !</strong>';
$DOWNLOAD_LANG['delete_category_and_its_content'] = 'Supprimer la catégorie et tout son contenu';
$DOWNLOAD_LANG['move_category_content'] = 'Déplacer son contenu dans :';
$DOWNLOAD_LANG['required_fields'] = 'Les champs marqués * sont obligatoires !';
$DOWNLOAD_LANG['category_name'] = 'Nom de la catégorie';
$DOWNLOAD_LANG['category_location'] = 'Emplacement de la catégorie';
$DOWNLOAD_LANG['cat_description'] = 'Description de la catégorie';
$DOWNLOAD_LANG['num_files_singular'] = '%d fichier';
$DOWNLOAD_LANG['num_files_plural'] = '%d fichiers';
$DOWNLOAD_LANG['recount_subfiles'] = 'Recompter le nombre de fichiers de chaque catégorie';
$DOWNLOAD_LANG['popularity'] = 'Popularité';
$DOWNLOAD_LANG['sort_alpha'] = 'Alphabétique';
$DOWNLOAD_LANG['order_by'] = 'Trier selon';

//Autorisations
$DOWNLOAD_LANG['auth_read'] = 'Permissions de lecture';
$DOWNLOAD_LANG['auth_write'] = 'Permissions d\'écriture';
$DOWNLOAD_LANG['auth_contribute'] = 'Permissions de contribution';
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

//Syndication
$DOWNLOAD_LANG['read_feed'] = 'Télécharger';
$DOWNLOAD_LANG['posted_on'] = 'Le';

//Contribution
$DOWNLOAD_LANG['notice_contribution'] = 'Vous n\'êtes pas autorisé à créer un fichier, cependant vous pouvez proposer un fichier. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$DOWNLOAD_LANG['contribution_counterpart'] = 'Complément de contribution';
$DOWNLOAD_LANG['contribution_counterpart_explain'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier au téléchargement). Ce champ est facultatif.';
$DOWNLOAD_LANG['contribution_entitled'] = 'Un fichier a été proposé : %s';
$DOWNLOAD_LANG['contribution_confirmation'] = 'Confirmation de contribution';
$DOWNLOAD_LANG['contribution_confirmation_explain'] = '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';
$DOWNLOAD_LANG['contribution_success'] = 'Votre contribution a bien été enregistrée.';

//Erreurs
$LANG['e_unexist_file_download'] = 'Le fichier que vous demandez n\'existe pas !';
$LANG['e_unexist_category_download'] = 'La catégorie que vous demandez n\'existe pas !';

?>