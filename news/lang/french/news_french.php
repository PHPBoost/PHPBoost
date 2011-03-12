<?php
/*##################################################
 *                             news_french.php
 *                            -------------------
 *   begin                :  June 20, 2005
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

//Administration
$LANG['confirm_del_news'] = 'Supprimer cette news ?';
$LANG['add_news'] = 'Ajouter une news';
$LANG['configuration_news'] = 'Configuration des news';
$LANG['category_news'] = 'Catégories des news';
$LANG['img_management'] = 'Interface image';
$LANG['preview_image'] = 'Aperçu image';
$LANG['preview_image_explain'] = 'Par défaut à droite';
$LANG['img_link'] = 'Adresse de la photo';
$LANG['img_desc'] = 'Description image';
$LANG['news_management'] = 'Gestion des news';
$LANG['edit_news'] = 'Editer la news';
$LANG['edito'] = 'Edito';
$LANG['edito_where'] = 'Message visible de tous en haut de l\'accueil';
$LANG['config_news'] = 'Configuration des news';
$LANG['nbr_news_p'] = 'Nombre de news par pages';
$LANG['nbr_news_p_explain'] = '6 par défaut';
$LANG['nbr_arch_p'] = 'Nombre d\'archives par pages';
$LANG['nbr_arch_p_explain'] = '15 par défaut';
$LANG['module_management'] = 'Gestion des modules';
$LANG['activ_pagination'] = 'Activer la pagination';
$LANG['activ_pagination_explain'] = 'Sinon affiche un lien vers les archives';
$LANG['activ_edito'] = 'Activer l\'édito';
$LANG['activ_edito_explain'] = 'Message au dessus des news';
$LANG['activ_news_block'] = 'Activer les news en bloc';
$LANG['activ_com_n'] = 'Activer les commentaires des news';
$LANG['activ_icon_n'] = 'Afficher les icônes de catégories des news';
$LANG['display_news_author'] = 'Afficher l\'auteur de la news';
$LANG['display_news_date'] = 'Afficher la date de la news';
$LANG['extended_news'] = 'News étendue';
$LANG['icon_cat'] = 'Icône de la catégorie';
$LANG['news_date'] = 'Date de la news';
$LANG['news_date_explain'] = '(jj/mm/aa) Laisser vide pour mettre la date d\'aujourd\'hui';
$LANG['nbr_news_column'] = 'Nombre de colonnes pour afficher les news';
$LANG['no_img'] = 'Aucune image';

//Erreurs
$LANG['e_unexist_news'] = 'La news que vous demandez n\'existe pas';

//Titre
$LANG['title_news'] = 'News';

//Alertes
$LANG['alert_delete_news'] = 'Supprimer cette News ?';

//News
$LANG['news'] = 'News';
$LANG['propose_news'] = 'Proposer une news';
$LANG['xml_news_desc'] = 'Suivez les dernières actualités sur';
$LANG['add_succes_news'] = 'News envoyée avec succès, en attente d\'approbation';
$LANG['add_news'] = 'Ajouter une news';
$LANG['last_news'] = 'Dernières news';
$LANG['extend_contents'] = 'Lire la suite...';
$LANG['no_news_available'] = 'Aucune news disponible pour le moment';
$LANG['archive'] = 'Archives';
$LANG['display_archive'] = 'Voir les archives';
$LANG['read_feed'] = 'Lire';

//Ajout news.
$MAIL['new_news_website'] = 'Nouvelle news sur votre site web';
$MAIL['new_news'] = 'Une nouvelle news a été ajouté sur votre site web ' . HOST . ', 
elle devra être approuvée avant d\'être visible sur le site par tout le monde.

Titre de la news: %s
Contenu:  %s...[suite]
Posté par:  %s

Rendez-vous dans le panneau gestion des news de l\'administration, pour l\'approuver.
' . HOST . DIR . '/admin/admin_news_gestion.php';
?>