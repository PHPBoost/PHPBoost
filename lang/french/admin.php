<?php
/*##################################################
 *                                admin.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
#                    French                        #
 ####################################################

$LANG['xml_lang'] = 'fr';
$LANG['administration'] = 'Administration';
$LANG['no_administration'] = 'Aucune administration n\'est associée à ce module !';

$LANG['extend_menu'] = 'Menu étendu';
$LANG['phpinfo'] = 'PHP info';

//Config
$LANG['serv_name'] = 'URL du serveur';
$LANG['serv_path'] = 'Chemin de PHPBoost';
$LANG['default_theme'] = 'Thème (par défaut) du site';
$LANG['default_language'] = 'Langue (par défaut) du site';
$LANG['start_page'] = 'Page de démarrage du site';
$LANG['cookie_name'] = 'Nom du cookie des sessions';
$LANG['session_time'] = 'Durée de la session (en secondes)';
$LANG['session invit'] = 'Durée utilisateurs actifs (en secondes)';

//Form
$LANG['add'] = 'Ajouter';

//Alertes formulaires
$LANG['alert_same_pass'] = 'Les mots de passe ne sont pas identiques !';
$LANG['alert_max_dim'] = 'Le fichier dépasse les largeurs et hauteurs maximales spécifiées !';
$LANG['alert_error_avatar'] = 'Erreur d\'enregistrement de l\'avatar !';
$LANG['alert_error_img'] = 'Erreur d\'enregistrement de l\'image !';
$LANG['alert_invalid_file'] = 'Le fichier image n\'est pas valide (jpg, gif ou png) !';
$LANG['alert_max_weight'] = 'Image trop lourde';
$LANG['alert_s_already_use'] = 'Code du smiley déjà utilisé !';
$LANG['alert_no_cat'] = 'Aucun nom/catégorie saisi';
$LANG['alert_fct_unlink'] = 'Suppression des miniatures impossible. Vous devez les supprimer manuellement sur le ftp !';
$LANG['alert_no_login'] = 'Le pseudo entré n\'existe pas !';

//Requis
$LANG['require'] = 'Les Champs marqués * sont obligatoires !';
$LANG['require_title'] = 'Veuillez entrer un titre !';
$LANG['require_text'] = 'Veuillez entrer un texte !';
$LANG['require_password'] = 'Veuillez entrer un mot de passe !';
$LANG['require_cat'] = 'Veuillez entrer une catégorie !';
$LANG['require_cat_create'] = 'Aucune catégorie trouvée, veuillez d\'abord en créer une';
$LANG['require_serv'] = 'Veuillez entrer un nom pour le serveur !';
$LANG['require_name'] = 'Veuillez entrer un nom !';
$LANG['require_cookie_name'] = 'Veuillez entrer un nom de cookie !';
$LANG['require_session_time'] = 'Veuillez entrer une durée pour la session !';
$LANG['require_session_invit'] = 'Veuillez entrer une durée pour la session invité !';
$LANG['require_pass'] = 'Veuillez entrer un mot de passe !';
$LANG['require_rank'] = 'Veuillez entrer un rang !';
$LANG['require_code'] = 'Veuillez entrer un code pour le smiley !';
$LANG['require_max_width'] = 'Veuillez entrer une largeur maximale pour les avatars !';
$LANG['require_height'] = 'Veuillez entrer une hauteur maximale pour les avatars !';
$LANG['require_weight'] = 'Veuillez entrer un poids maximum pour les avatars !';
$LANG['require_rank_name'] = 'Veuillez entrer un nom pour le rang !';
$LANG['require_nbr_msg_rank'] = 'Veuillez entrer un nombre de messages pour le rang !';
$LANG['require_subcat'] = 'Veuillez sélectionner une sous-catégorie !';
$LANG['require_file_name'] = 'Vous devez saisir un nom de fichier !';

//Confirmations.
$LANG['redirect'] = 'Redirection en cours...';
$LANG['del_entry'] = 'Supprimer l\'entrée ?';
$LANG['confirm_del_member'] = 'Supprimer le membre ? (définitif)';
$LANG['confirm_del_admin'] = 'Supprimer l\'admin ? (irréversible)';
$LANG['confirm_theme'] = 'Supprimer le thème ?';
$LANG['confirm_del_smiley'] = 'Supprimer le smiley ?';
$LANG['confirm_del_cat'] = 'Supprimer cette catégorie ?';
$LANG['confirm_del_article'] = 'Supprimer cet article ?';
$LANG['confirm_del_rank'] = 'Supprimer ce rang ?';
$LANG['confirm_del_group'] = 'Supprimer ce groupe ?';
$LANG['confirm_del_member_group'] = 'Supprimer ce membre du groupe ?';

//Commun
$LANG['pseudo'] = 'Pseudo';
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['description'] = 'Description';
$LANG['view'] = 'Vu';
$LANG['views'] = 'Vues';
$LANG['name'] = 'Nom';
$LANG['title'] = 'Titre';
$LANG['message'] = 'Message';
$LANG['aprob'] = 'Approbation';
$LANG['unaprob'] = 'Désapprobation';
$LANG['url'] = 'Adresse';
$LANG['categorie'] = 'Catégorie';
$LANG['note'] = 'Note';
LangLoader::get_message('date', 'date-common') = 'Date';
$LANG['com'] = 'Commentaires';
$LANG['size'] = 'Taille';
$LANG['file'] = 'Fichier';
$LANG['download'] = 'Téléchargé';
$LANG['delete'] = 'Supprimer';
$LANG['user_ip'] = 'Adresse ip';
$LANG['localisation'] = 'Localisation';
$LANG['activ'] = 'Activé';
$LANG['unactiv'] = 'Désactivé';
$LANG['unactivate'] = 'Désactiver';
$LANG['activate'] = 'Activer';
$LANG['img'] = 'Image';
$LANG['activation'] = 'Activation';
$LANG['position'] = 'Position';
$LANG['path'] = 'Chemin';
$LANG['on'] = 'Le';
$LANG['at'] = 'à';
$LANG['registered'] = 'Enregistré';
$LANG['website'] = 'Site web';
$LANG['search'] = 'Recherche';
$LANG['mail'] = 'Mail';
$LANG['password'] = 'Mot de passe';
$LANG['contact'] = 'Contact';
$LANG['info'] = 'Informations';
$LANG['language'] = 'Langue';
$LANG['sanction'] = 'Sanction';
$LANG['ban'] = 'Banni';
$LANG['theme'] = 'Thème';
$LANG['code'] = 'Code';
$LANG['status'] = 'Statut';
$LANG['question'] = 'Question';
$LANG['answers'] = 'Réponses';
$LANG['archived'] = 'Archivé';
$LANG['galerie'] = 'Galerie' ;
$LANG['select'] = 'Sélectionner';
$LANG['pics'] = 'Photos';
$LANG['empty'] = 'Vider';
$LANG['show'] = 'Consulter';
$LANG['link'] = 'Lien';
$LANG['type'] = 'Type';
$LANG['of'] = 'de';
$LANG['autoconnect'] = 'Connexion automatique';
$LANG['unspecified'] = 'Non spécifié';
$LANG['configuration'] = 'Configuration';
$LANG['management'] = 'Gestion';
$LANG['add'] = 'Ajouter';
$LANG['category'] = 'Catégorie';
$LANG['site'] = 'Site';
$LANG['modules'] = 'Modules';
$LANG['powered_by'] = 'Boosté par';
$LANG['release_date'] = 'Date de parution jj/mm/aa';
$LANG['immediate'] = 'Immédiate';
$LANG['waiting'] = 'En attente';
$LANG['stats'] = 'Statistiques';
$LANG['cat_management'] = 'Gestion des catégories';
$LANG['cat_add'] = 'Ajouter une catégorie';
$LANG['visible'] = 'Visible';
$LANG['undefined'] = 'Indéterminé';
$LANG['nbr_cat_max'] = 'Nombre de catégories maximum affichées';
$LANG['nbr_column_max'] = 'Nombre de colonnes';
$LANG['note_max'] = 'Echelle de notation';
$LANG['max_link'] = 'Nombre de liens maximum dans le message';
$LANG['max_link_explain'] = 'Mettre -1 pour illimité';
$LANG['generate'] = 'Générer';
$LANG['or_direct_path'] = 'Ou chemin direct';
$LANG['unknow_bot'] = 'Bot inconnu';
$LANG['captcha_difficulty'] = 'Difficulté du code de vérification';

//Connexion
$LANG['unlock_admin_panel'] = 'Déverrouillage de l\'administration';
$LANG['flood_block'] = 'Il vous reste %d essai(s). Après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5) !';
$LANG['flood_max'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes';

//Rang
$LANG['rank_management'] = 'Gestion des rangs';
$LANG['upload_rank'] = 'Uploader une image de rang';
$LANG['upload_rank_format'] = 'JPG, GIF, PNG, BMP autorisés';
$LANG['rank_add'] = 'Ajouter un rang';
$LANG['rank'] = 'Rang';
$LANG['special_rank'] = 'Rang spécial';
$LANG['rank_name'] = 'Nom du Rang';
$LANG['nbr_msg'] = 'Nombre de message(s)';
$LANG['img_assoc'] = 'Image associée';
$LANG['guest'] = 'Visiteur';
$LANG['a_member'] = 'membre';
$LANG['member'] = 'Membre';
$LANG['a_modo'] = 'modo';
$LANG['modo'] = 'Modérateur';
$LANG['a_admin'] = 'admin';
$LANG['admin'] = 'Administrateur';

//Index
$LANG['update_available'] = 'Mises à jour disponibles';
$LANG['core_update_available'] = 'Nouvelle version <strong>%s</strong> du noyau disponible, pensez à mettre à jour PHPBoost ! <a href="http://www.phpboost.com">Plus d\'informations</a>';
$LANG['no_core_update_available'] = 'Aucune nouvelle version disponible, le système est à jour !';
$LANG['module_update_available'] = 'Des mises à jour des modules sont disponibles !';
$LANG['no_module_update_available'] = 'Aucune mise à jour des modules, vous êtes à jour !';
$LANG['unknow_update'] = 'Impossible de déterminer si une mise à jour est disponible !';
$LANG['user_online'] = 'Utilisateur(s) en ligne';
$LANG['last_update'] = 'Dernière mise à jour';
$LANG['quick_links'] = 'Liens rapides';
$LANG['members_managment'] = 'Gestion des membres';
$LANG['menus_managment'] = 'Gestion des menus';
$LANG['modules_managment'] = 'Gestion des modules';
$LANG['last_comments'] = 'Derniers commentaires';
$LANG['view_all_comments'] = 'Voir tous les commentaires';
$LANG['writing_pad'] = 'Bloc-notes';
$LANG['writing_pad_explain'] = 'Cet emplacement est réservé pour y saisir vos notes personnelles.';

//Alertes administrateur
$LANG['administrator_alerts'] = 'Alertes';
$LANG['administrator_alerts_list'] = 'Liste des alertes';
$LANG['no_unread_alert'] = 'Aucune alerte en attente';
$LANG['unread_alerts'] = 'Des alertes non traitées sont en attente.';
$LANG['no_administrator_alert'] = 'Aucune alerte existante';
$LANG['display_all_alerts'] = 'Voir toutes les alertes';
$LANG['priority'] = 'Priorité';
$LANG['priority_very_high'] = 'Immédiat';
$LANG['priority_high'] = 'Urgent';
$LANG['priority_medium'] = 'Moyenne';
$LANG['priority_low'] = 'Faible';
$LANG['priority_very_low'] = 'Très faible';
$LANG['administrator_alerts_action'] = 'Actions';
$LANG['admin_alert_fix'] = 'Régler';
$LANG['admin_alert_unfix'] = 'Passer l\'alerte en non réglée';
$LANG['confirm_delete_administrator_alert'] = 'Etes-vous sûr de vouloir supprimer cette alerte ?';

//Maintain
$LANG['maintain_auth'] = 'Autorisation d\'accès au site durant la maintenance';
$LANG['maintain_for'] = 'Mettre le site en maintenance';
$LANG['maintain_delay'] = 'Afficher la durée de la maintenance';
$LANG['maintain_display_admin'] = 'Afficher la durée de la maintenance à l\'administrateur';
$LANG['maintain_text'] = 'Texte à afficher lorsque la maintenance du site est en cours';

//Rapport système
$LANG['system_report'] = 'Rapport système';
$LANG['server'] = 'Serveur';
$LANG['php_version'] = 'Version de PHP';
$LANG['dbms_version'] = 'Version du SGBD';
$LANG['dg_library'] = 'Librairie GD';
$LANG['url_rewriting'] = 'Réécriture des URL';
$LANG['register_globals_option'] = 'Option <em>register globals</em>';
$LANG['phpboost_config'] = 'Configuration de PHPBoost';
$LANG['kernel_version'] = 'Version du noyau';
$LANG['output_gz'] = 'Compression des pages';
$LANG['directories_auth'] = 'Autorisation des répertoires';
$LANG['system_report_summerization'] = 'Récapitulatif';
$LANG['system_report_summerization_explain'] = 'Ceci est le récapitulatif du rapport. Cela vous sera particulièrement utile lorsqu\'on vous demandera la configuration de votre système pour du support';

//Gestion de l'upload
$LANG['explain_upload_img'] = 'L\'image uploadée doit être au format jpg, gif, png ou bmp';
$LANG['explain_archive_upload'] = 'L\'archive uploadée doit être au format zip ou gzip';

//Gestion des fichiers
$LANG['auth_files'] = 'Autorisation requise pour l\'activation de l\'interface de fichiers';
$LANG['size_limit'] = 'Taille maximale des uploads autorisés aux membres';
$LANG['bandwidth_protect'] = 'Protection de la bande passante';
$LANG['bandwidth_protect_explain'] = 'Interdiction d\'accès aux fichiers du répertoire upload depuis un autre serveur';
$LANG['auth_extensions'] = 'Extensions autorisées';
$LANG['extend_extensions'] = 'Extensions autorisées supplémentaires';
$LANG['extend_extensions_explain'] = 'Séparez les extensions avec des virgules';
$LANG['files_image'] = 'Images';
$LANG['files_archives'] = 'Archives';
$LANG['files_text'] = 'Textes';
$LANG['files_media'] = 'Media';
$LANG['files_prog'] = 'Programmation';
$LANG['files_misc'] = 'Divers';

//Gestion des menus
$LANG['confirm_del_menu'] = 'Supprimer ce menu ?';
$LANG['confirm_delete_element'] = 'Voulez vous vraiment supprimer cet élément ?';
$LANG['menus_management'] = 'Gestion des menus';
$LANG['menus_content_add'] = 'Menu de contenu';
$LANG['menus_links_add'] = 'Menu de liens';
$LANG['menus_feed_add'] = 'Menu de flux';
$LANG['menus_edit'] = 'Modifier le menu';
$LANG['menus_add'] = 'Ajouter un menu';
$LANG['vertical_menu'] = 'Menu vertical';
$LANG['horizontal_menu'] = 'Menu horizontal';
$LANG['tree_menu'] = 'Menu arborescent';
$LANG['vertical_scrolling_menu'] = 'Menu vertical déroulant';
$LANG['horizontal_scrolling_menu'] = 'Menu horizontal déroulant';
$LANG['available_menus'] = 'Menus disponibles';
$LANG['no_available_menus'] = 'Aucun menu disponible';
$LANG['menu_header'] = 'Tête de page';
$LANG['menu_subheader'] = 'Sous entête';
$LANG['menu_left'] = 'Menu gauche';
$LANG['menu_right'] = 'Menu droit';
$LANG['menu_top_central'] = 'Menu central haut';
$LANG['menu_bottom_central'] = 'Menu central bas';
$LANG['menu_top_footer'] = 'Sur pied de page';
$LANG['menu_footer'] = 'Pied de page';
$LANG['location'] = 'Emplacement';
$LANG['use_tpl'] = 'Utiliser la structure des templates';
$LANG['add_sub_element'] = 'Ajouter un élément';
$LANG['add_sub_menu'] = 'Ajouter un sous-menu';
$LANG['display_title'] = 'Afficher le titre';
$LANG['choose_feed_in_list'] = 'Veuillez choisir un flux dans la liste';
$LANG['feed'] = 'flux';
$LANG['availables_feeds'] = 'Flux disponibles';
$LANG['valid_position_menus'] = 'Valider la position des menus';
$LANG['themes_management'] = 'Gérer le thème';
$LANG['move_up'] = 'Monter';
$LANG['move_down'] = 'Descendre';

$LANG['menu_configurations'] = 'Configurations';
$LANG['menu_configurations_list'] = 'Liste des configurations de menus';
$LANG['menus'] = 'Menus';
$LANG['menu_configuration_name'] = 'Nom';
$LANG['menu_configuration_match_regex'] = 'Correspond à';
$LANG['menu_configuration_edit'] = 'Editer';
$LANG['menu_configuration_configure'] = 'Configurer';
$LANG['menu_configuration_default_name'] = 'Configuration par défaut';
$LANG['menu_configuration_configure_default_config'] = 'Configurer la configuration par défaut';
$LANG['menu_configuration_edition'] = 'Edition d\'une configuration de menu';
$LANG['menu_configuration_edition_name'] = 'Nom de la configuration';
$LANG['menu_configuration_edition_match_regex'] = 'Expression régulière de correspondance';

//Smiley
$LANG['upload_smiley'] = 'Uploader un smiley';
$LANG['smiley'] = 'Smiley';
$LANG['add_smiley'] = 'Ajouter smiley';
$LANG['smiley_code'] = 'Code du smiley (ex : :D)';
$LANG['smiley_available'] = 'Smileys disponibles';
$LANG['edit_smiley'] = 'Edition des smileys';
$LANG['smiley_management'] = 'Gestion des smileys';
$LANG['e_smiley_already_exist'] = 'Le smiley existe déjà';

//Thèmes
$LANG['upload_theme'] = 'Uploader un thème';
$LANG['theme_on_serv'] = 'Thèmes disponibles sur le serveur';
$LANG['no_theme_on_serv'] = 'Aucun thème <strong>compatible</strong> n\'est disponible sur le serveur';
$LANG['theme_management'] = 'Gestion des thèmes';
$LANG['theme_add'] = 'Ajouter un thème';
$LANG['theme'] = 'Thème';
$LANG['e_theme_already_exist'] = 'Le thème existe déjà';
$LANG['xhtml_version'] = 'Version Html';
$LANG['css_version'] = 'Version Css';
$LANG['main_colors'] = 'Couleurs dominantes';
$LANG['width'] = 'Largeur';
$LANG['exensible'] = 'Extensible';
$LANG['del_theme'] = 'Suppression du thème';
$LANG['del_theme_files'] = 'Supprimer tous les fichiers du thème';
$LANG['explain_default_theme'] = 'Le thème par défaut ne peut pas être désinstallé, désactivé ou réservé';
$LANG['activ_left_column'] = 'Activer la colonne de gauche';
$LANG['activ_right_column'] = 'Activer la colonne de droite';
$LANG['manage_theme_columns'] = 'Gérer les colonnes du thème';

//Langues
$LANG['upload_lang'] = 'Uploader une langue';
$LANG['lang_on_serv'] = 'Langues disponibles sur le serveur';
$LANG['no_lang_on_serv'] = 'Aucune langue disponible sur le serveur';
$LANG['lang_management'] = 'Gestion des langues';
$LANG['lang_add'] = 'Ajouter une langue';
$LANG['lang'] = 'Langue';
$LANG['e_lang_already_exist'] = 'La langue existe déjà';
$LANG['del_lang'] = 'Suppression de la langue';
$LANG['del_lang_files'] = 'Supprimer les fichiers de la langue';
$LANG['explain_default_lang'] = 'La langue par défaut ne peut pas être désinstallée, désactivée ou réservée';

//Gestion membre
$LANG['job'] = 'Emploi';
$LANG['hobbies'] = 'Loisirs';
$LANG['members_management'] = 'Gestion des Membres';
$LANG['members_add'] = 'Ajouter un membre';
$LANG['members_config'] = 'Configuration des membres';
$LANG['members_punishment'] = 'Gestion des sanctions';
$LANG['members_msg'] = 'Message à tous les membres';
$LANG['search_member'] = 'Rechercher un membre';
$LANG['joker'] = 'Utilisez * pour joker';
$LANG['no_result'] = 'Aucun résultat';
$LANG['life'] = 'A vie';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais ne peut plus poster sur le site entier (commentaires, etc...)';
$LANG['weeks'] = 'semaines';
$LANG['life'] = 'A vie';
$LANG['readonly_user'] = 'Membre en lecture seule';

//Règlement
$LANG['explain_terms'] = 'Entrez ci-dessous le règlement à afficher lors de l\'enregistrement des membres, ils devront l\'accepter pour s\'enregistrer. Laissez vide pour aucun règlement.';

//Gestion des groupes
$LANG['groups_management'] = 'Gestion des groupes';
$LANG['groups_add'] = 'Ajouter un groupe';
$LANG['auth_flood'] = 'Autorisation de flooder';
$LANG['pm_group_limit'] = 'Limite de messages privés';
$LANG['pm_group_limit_explain'] = 'Mettre -1 pour illimité';
$LANG['data_group_limit'] = 'Limite de données uploadables';
$LANG['data_group_limit_explain'] = 'Mettre -1 pour illimité';
$LANG['color_group'] = 'Couleur';
$LANG['color_group_explain'] = 'Couleur associée au groupe en hexadécimal (ex: #FF6600)';
$LANG['img_assoc_group'] = 'Image associée au groupe';
$LANG['img_assoc_group_explain'] = 'Mettre dans le dossier images/group/';
$LANG['add_mbr_group'] = 'Ajouter un membre au groupe';
$LANG['mbrs_group'] = 'Membres du groupe';
$LANG['auths'] = 'Autorisations';
$LANG['auth_access'] = 'Autorisation d\'accès';
$LANG['auth_read'] = 'Droits de lecture';
$LANG['auth_write'] = 'Droits d\'écriture';
$LANG['auth_edit'] = 'Droits de modération';
$LANG['upload_group'] = 'Uploader une image de groupe';

//Robots
$LANG['robot'] = 'Robot';
$LANG['robots'] = 'Robots';
$LANG['erase_rapport'] = 'Effacer le rapport';
$LANG['number_r_visit'] = 'Nombre de visite(s)';

//Statistiques
$LANG['stats'] = 'Statistiques';
$LANG['more_stats'] = 'Plus de stats';
$LANG['site'] = 'Site';
$LANG['browser_s'] = 'Navigateurs';
$LANG['fai'] = 'Fournisseurs d\'accès Internet';
$LANG['all_fai'] = 'Voir la liste complète des fournisseurs d\'accès Internet';
$LANG['10_fai'] = 'Voir les 10 principaux fournisseurs d\'accès Internet';
$LANG['os'] = 'Systèmes d\'exploitation';
$LANG['number'] = 'Nombre ';
$LANG['start'] = 'Création du site';
$LANG['stat_lang'] = 'Pays des visiteurs';
$LANG['all_langs'] = 'Voir la liste complète des pays des visiteurs';
$LANG['10_langs'] = 'Voir les 10 principaux pays des visiteurs';
$LANG['visits_year'] = 'Voir les statistiques de l\'année';
$LANG['unknown'] = 'Inconnu';
$LANG['last_member'] = 'Dernier membre';
$LANG['top_10_posters'] = 'Top 10 : posteurs';
$LANG['version'] = 'Version';
$LANG['colors'] = 'Couleurs';
$LANG['calendar'] = 'Calendrier';
$LANG['events'] = 'Evénements';

// Updates
$LANG['website_updates'] = 'Mises à jour';
$LANG['kernel'] = 'Noyau';
$LANG['themes'] = 'Thèmes';
$LANG['update_available'] = 'Le %1$s %2$s est disponible dans sa version %3$s';
$LANG['kernel_update_available'] = 'PHPBoost est disponible dans sa nouvelle version %s';
$LANG['app_update__download'] = 'Téléchargement';
$LANG['app_update__download_pack'] = 'Pack complet';
$LANG['app_update__update_pack'] = 'Pack de mise à jour';
$LANG['author'] = 'Auteur';
$LANG['authors'] = 'Auteurs';
$LANG['new_features'] = 'Nouvelles Fonctionnalités';
$LANG['improvments'] = 'Améliorations';
$LANG['fixed_bugs'] = 'Corrections de bugs';
$LANG['security_improvments'] = 'Améliorations de sécurité';
$LANG['unexisting_update'] = 'La mise à jour recherchée n\'existe pas';
$LANG['updates_are_available'] = 'Des mises à jours sont disponibles.<br />Veuillez les effectuer au plus vite.';
$LANG['availables_updates'] = 'Mises à jour disponibles';
$LANG['details'] = 'Détails';
$LANG['more_details'] = 'Plus de détails';
$LANG['download_the_complete_pack'] = 'Téléchargez le pack complet';
$LANG['download_the_update_pack'] = 'Téléchargez le pack de mise à jour';
$LANG['no_available_update'] = 'Aucune mise à jour n\'est disponible pour l\'instant.';
$LANG['incompatible_php_version'] = 'Impossible de vérifier la présence de mise à jour.
Veuillez utiliser la version %s ou ultérieure de PHP.<br />Si vous ne pouvez utiliser PHP5,
veuillez vérifier la présence de ces mises à jour sur notre <a href="http://www.phpboost.com">site officiel</a>.';
$LANG['check_for_updates_now'] = 'Vérifier la présence de mises à jour';
?>