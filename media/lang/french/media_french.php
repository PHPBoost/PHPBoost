<?php
/*##################################################
 *                              media_french.php
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
#                     French                       #
####################################################

global $MEDIA_LANG;

$MEDIA_LANG = array(
// admin_media.php
'aprob_media' => 'Approuver le fichier multimédia',
'confirm_delete_media' => 'Êtes-vous certain de vouloir supprimer ce fichier multimédia ?',
'hide_media' => 'Cacher ce fichier multimédia',
'recount_per_cat' => 'Recompter le nombre de fichiers multimédias par catégorie',
'show_media' => 'Montrer ce fichier multimédia',

// admin_media_cats.php
'auth_read' => 'Permissions de lecture',
'auth_contrib' => 'Permissions de contribution',
'auth_write' => 'Permissions d\'écriture',
'category' => 'Catégorie',
'cat_description' => 'Description de la catégorie',
'cat_image' => 'Image de la catégorie',
'cat_location' => 'Emplacement de la catégorie',
'cat_name' => 'Nom de la catégorie',
'display' => 'Affichage',
'display_com' => 'Afficher les commentaires',
'display_date' => 'Afficher la date du fichier multimédia',
'display_desc' => 'Afficher la description du fichier multimédia',
'display_in_list' => 'Liste',
'display_in_media' => 'Fichier',
'display_nbr' => 'Afficher le nombre de fichier dans la catégorie',
'display_note' => 'Afficher la note du fichier multimédia',
'display_poster' => 'Afficher le posteur du fichier multimédia',
'display_view' => 'Afficher le nombre de consultation du fichier multimédia',
'infinite_loop' => 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie',
'move_category_content' => 'Déplacer son contenu dans :',
'new_cat_does_not_exist' => 'La catégorie cible n\'existe pas',
'recount_success' => 'Le recomptage des fichiers multimédias a été réalisé avec succès.',
'remove_category_and_its_content' => 'Supprimer la catégorie et tout son contenu',
'removing_category' => 'Suppression d\'une catégorie',
'removing_category_explain' => 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (catégories et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son contenu. <strong>Attention, cette action est irréversible !</strong>',
'required_fields' => 'Les champs marqués * sont obligatoires !',
'required_fields_empty' => 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement',
'special_auth' => 'Autorisations spéciales',
'successful_operation' => 'L\'opération que vous avez demandé a été effectuée avec succès',
'unexisting_category' => 'La catégorie que vous avez sélectionné n\'existe pas',

// admin_media_config.php
'config_auth' => 'Autorisations générales',
'config_auth_explain' => 'Configurez ici les autorisations générales de lecture et d\'écriture du module MULTIMEDIA. Vous pourrez ensuite pour chaque catégorie appliquer des autorisations particulières.',
'config_display' => 'Configuration de l\'affichage',
'config_general' => 'Configuration générale',
'mime_type' => 'Types de fichiers autorisés',
'module_desc' => 'Description du module',
'module_name' => 'Nom du module',
'module_name_explain' => 'Le nom du module apparaîtra dans le titre et dans l\'arborescence de chaque page',
'nbr_cols' => 'Nombre de catégories par colonne',
'note' => 'Échelle de notation',
'pagination' => 'Nombre de fichiers multimédia affichés par page',
'require' => 'Veuillez compléter le champ : ',
'type_both' => 'Musique & Vidéo',
'type_music' => 'Musique',
'type_video' => 'Vidéo',

// admin_media_menu.php
'add_cat' => 'Ajouter une catégorie',
'add_media' => 'Ajouter un fichier multimédia',
'configuration' => 'Configuration',
'list_media' => 'Liste des fichiers multimédias',
'management_cat' => 'Gestion des catégories',
'management_media' => 'Gestion multimédia',

// contribution.php
'contribution_confirmation' => 'Confirmation de contribution',
'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>',
'contribution_success' => 'Votre contribution a bien été enregistrée.',

// media.php
'add_on_date' => 'Ajouté le %s',
'n_time' => '%d fois',
'n_times' => '%d fois',
'none_media' => 'Il n\'y a aucun fichier multimédia dans cette catégorie !',
'num_note' => '%d note',
'num_notes' => '%d notes',
'num_media' => '%d fichier multimédia',
'num_medias' => '%d fichiers multimédias',
'sort_popularity' => 'Popularité',
'sort_title' => 'Titre',
'media_infos' => 'Information sur le fichier multimédia',
'media_added' => '<a href="%2$s"%3$s>%1$s</a>',
'media_added_by' => 'Par <a href="%2$s"%3$s>%1$s</a>',
'view_n_times' => 'Vu %d fois',

// media_action.php
'action_success' => 'L\'action demandée a été réalisée avec succès !',
'add_success' => 'Le fichier a été ajouté avec succès !',
'contribution_counterpart' => 'Complément de contribution',
'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier). Ce champ est facultatif.',
'contribution_entitled' => '[Multimédia] %s',
'contribute_media' => 'Proposer un fichier multimédia',
'delete_media' => 'Supprimer un fichier multimédia',
'deleted_success' => 'Le fichier multimédia a été supprimée avec succès !',
'edit_success' => 'Le fichier multimédia a été édité avec succès !',
'edit_media' => 'Éditer un fichier multimédia',
'media_aprobe' => 'Approbation',
'media_approved' => 'Approuvée',
'media_category' => 'Catégorie du fichier multimédia',
'media_description' => 'Description du fichier multimédia',
'media_height' => 'Hauteur de la vidéo',
'media_moderation' => 'Modération',
'media_name' => 'Titre du fichier multimédia',
'media_url' => 'Lien du fichier multimédia',
'media_width' => 'Largeur de la vidéo',
'notice_contribution' => 'Vous n\'êtes pas autorisé à ajouter un fichier multimédia, cependant vous pouvez proposer un fichier multimédia. Votre contribution suivra le parcours classique et sera traitée dans la panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.',
'require_name' => 'Vous devez donnez un titre à ce fichier multimédia !',
'require_url' => 'Vous devez renseigner le lien de votre fichier multimédia !',

// media_interface.class.php
'media' => 'Fichier Multimédia',
'all_cats' => 'Toutes les catégories',
'xml_media_desc' => 'Derniers médias',

// moderation_media.php
'all_file' => 'Tous les fichiers',
'confirm_delete_media_all' => 'Cette action supprimera DÉFINITIVEMENT tous les fichiers sélectionnés !',
'display_file' => 'Afficher les fichiers',
'file_unaprobed' => 'Fichier désapprouvé',
'file_unvisible' => 'Fichier invisible',
'file_visible' => 'Fichier approuvé et visible',
'filter' => 'Filtre',
'from_cats' => 'de la catégorie',
'hide_media_short' => 'Cacher',
'include_sub_cats' => ', inclure les sous-catégories :',
'legend' => 'Légende',
'moderation_success' => 'Les actions ont été réalisées avec succès !',
'no_media_moderate' => 'Aucun fichier multimédia à modérer !',
'show_media_short' => 'Montrer',
'unaprobed' => 'Désapprouvés',
'unvisible' => 'Invisibles',
'unaprob_media' => 'Fichier désapprouvé',
'unaprobed_media_short' => 'Désapprouver',
'unvisible_media' => 'Fichier invisible',
'visible' => 'Approuvés',
);

$LANG['e_mime_disable_media'] = 'Le type du fichier multimédia que vous souhaitez proposer est désactivé !';
$LANG['e_mime_unknow_media'] = 'Impossible de déterminer le mime type de ce fichier !';
$LANG['e_link_empty_media'] = 'Veuillez renseigner le lien de votre fichier multimédia !';
$LANG['e_unexist_media'] = 'Le fichier multimédia demandée n\'existe pas !';

?>