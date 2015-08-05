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

global $MEDIA_LANG;

$MEDIA_LANG = array(
// media.php
'add_on_date' => 'Ajouté le %s',
'n_time' => '%d fois',
'n_times' => '%d fois',
'num_note' => '%d note',
'num_notes' => '%d notes',
'num_media' => '%d fichier multimédia',
'num_medias' => '%d fichiers multimédias',
'sort_popularity' => 'Popularité',
'sort_title' => 'Titre',
'media_infos' => 'Information sur le fichier multimédia',
'media_added_by' => 'Par',
'view_n_times' => 'Vu %d fois',

// media_action.php
'contribution_counterpart' => 'Complément de contribution',
'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer ce fichier). Ce champ est facultatif.',
'contribute_media' => 'Proposer un fichier multimédia',
'add_media' => 'Ajouter un fichier multimédia',
'delete_media' => 'Supprimer un fichier multimédia',
'deleted_success' => 'Le fichier multimédia a été supprimé avec succès !',
'edit_success' => 'Le fichier multimédia a été édité avec succès !',
'edit_media' => 'Éditer un fichier multimédia',
'media_aprobe' => 'Approbation',
'media_approved' => 'Approuvée',
'media_description' => 'Description du fichier multimédia',
'media_height' => 'Hauteur de la vidéo',
'media_moderation' => 'Modération',
'media_name' => 'Titre du fichier multimédia',
'media_url' => 'Lien du fichier multimédia',
'media_width' => 'Largeur de la vidéo',
'notice_contribution' => 'Vous n\'êtes pas autorisé à ajouter un fichier multimédia, cependant vous pouvez proposer un fichier multimédia. Votre contribution suivra le parcours classique et sera traitée dans la panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.',
'require_name' => 'Vous devez donnez un titre à ce fichier multimédia !',
'require_url' => 'Vous devez renseigner le lien de votre fichier multimédia !',
'hide_media' => 'Cacher ce fichier multimédia',

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
'unaprobed_media_short' => 'Désapprouver',
'unvisible_media' => 'Fichier invisible',
'visible' => 'Approuvés',
);

$LANG['e_mime_disable_media'] = 'Le type de fichier multimédia que vous souhaitez proposer est désactivé !';
$LANG['e_mime_unknow_media'] = 'Impossible de déterminer le type de ce fichier !';
$LANG['e_link_empty_media'] = 'Veuillez renseigner le lien de votre fichier multimédia !';
$LANG['e_unexist_media'] = 'Le fichier multimédia demandé n\'existe pas !';

?>