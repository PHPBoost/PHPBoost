<?php
/*##################################################
 *                           web_french.php
 *                            -------------------
 *   begin                :  July 28, 2005
 *   copyright            : (C) 2006 CrowkaiT
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

//Admin
$LANG['web_add'] = 'Ajouter un lien';
$LANG['web_management'] = 'Gestion des liens';
$LANG['web_config'] = 'Configuration des liens';
$LANG['edit_link'] = 'Edition du lien';
$LANG['nbr_web_max'] = 'Nombre maximum de liens web affichés';
$LANG['icon_cat'] = 'Icône de la catégorie';

//Erreurs
$LANG['e_unexist_link_web'] = 'Le lien que vous avez demandé n\'existe pas';

//Titre
$LANG['title_web'] = 'Liens Web';

//Web
$LANG['link'] = 'Lien';
$LANG['propose_link'] = 'Proposer un lien';
$LANG['none_link'] = 'Aucun lien dans cette catégorie';
$LANG['how_link'] = 'Lien(s) dans la base de données!';
$LANG['no_note'] = 'Aucune note';
$LANG['actual_note'] = 'Note actuelle';
$LANG['vote'] = 'Voter';
$LANG['delete_link'] = 'Supprimer le lien?';

//Ajout lien web.
$MAIL['new_link_website'] = 'Nouveau lien sur votre site web';
$MAIL['new_link'] = 'Un nouveau lien web a été ajouté sur votre site web ' . HOST . ', 
il devra être approuvé avant d\'être visible sur le site par tout le monde.

Titre du lien: %s
Url du lien: %s
Contenu: %s...[suite]

Rendez-vous dans le panneau gestion des liens web de l\'administration, pour l\'approuver.
' . HOST . DIR . '/admin/admin_web_gestion.php';
?>