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
#                    French                        #
####################################################

$LANG['xml_lang'] = 'fr';
$LANG['administration'] = 'Administration';
$LANG['no_administration'] = 'Aucune administration associée avec ce module!';

//Titre Modules par défauts
$LANG['index'] = 'Index';
$LANG['tools'] = 'Outils';
$LANG['link_management'] = 'Gestion des liens';
$LANG['menu_management'] = 'Menus';
$LANG['moderation'] = 'Panneau modération';
$LANG['maintain'] = 'Maintenance';
$LANG['updater'] = 'Mises à jour';
$LANG['extend_field'] = 'Champs membres';
$LANG['ranks'] = 'Rangs';
$LANG['terms'] = 'Règlement';
$LANG['pages'] = 'Pages';
$LANG['files'] = 'Fichiers';
$LANG['themes'] = 'Thèmes';
$LANG['languages'] = 'Langues';
$LANG['smile'] = 'Smileys';
$LANG['comments'] = 'Commentaires';
$LANG['group'] = 'Groupes';
$LANG['stats'] = 'Statistiques';
$LANG['errors'] = 'Erreurs archivées';
$LANG['server'] = 'Serveur';
$LANG['phpinfo'] = 'PHP info';
$LANG['cache'] = 'Cache';
$LANG['punishement'] = 'Sanction';
$LANG['extend_menu'] = 'Menu étendu';

//Form
$LANG['add'] = 'Ajouter';

//Alertes formulaires
$LANG['alert_same_pass'] = 'Les mots de passe ne sont pas identiques!';
$LANG['alert_max_dim'] = 'Le fichier dépasse les largeurs et hauteurs maximales spécifiées !';
$LANG['alert_error_avatar'] = 'Erreur d\'enregistrement de l\'avatar!';
$LANG['alert_error_img'] = 'Erreur d\'enregistrement de l\'image!';
$LANG['alert_invalid_file'] = 'Le fichier image n\'est pas valide (jpg, gif, png!)';
$LANG['alert_max_weight'] = 'Image trop lourde';
$LANG['alert_s_already_use'] = 'Code du smiley déjà utilisé!';
$LANG['alert_no_cat'] = 'Aucun nom/catégorie saisi';
$LANG['alert_fct_unlink'] = 'Suppression des miniatures impossible. Vous devez supprimer manuellement sur le ftp!';
$LANG['alert_no_login'] = 'Le pseudo entré n\'existe pas!';

//Requis
$LANG['require'] = 'Les Champs marqués * sont obligatoires!';
$LANG['require_title'] = 'Veuillez entrer un titre !';
$LANG['require_text'] = 'Veuillez entrer un texte!';
$LANG['require_password'] = 'Veuillez entrer un password!';
$LANG['require_cat'] = 'Veuillez entrer une catégorie!';
$LANG['require_cat_create'] = 'Aucune catégorie trouvée, veuillez d\'abord en créer une';
$LANG['require_serv'] = 'Veuillez entrer un nom pour le serveur!';
$LANG['require_name'] = 'Veuillez entrer un nom!';
$LANG['require_cookie_name'] = 'Veuillez entrer un nom de cookie!';
$LANG['require_session_time'] = 'Veuillez entrer une durée pour la session!';
$LANG['require_session_invit'] = 'Veuillez entrer une durée pour la session invité!';
$LANG['require_pass'] = 'Veuillez entrer un mot de passe!';
$LANG['require_rank'] = 'Veuillez entrer un rang!';
$LANG['require_code'] = 'Veuillez entrer un code pour le smiley!';
$LANG['require_max_width'] = 'Veuillez entrer une largeur maximale pour les avatars!';
$LANG['require_height'] = 'Veuillez entrer une hauteur maximale pour les avatars!';
$LANG['require_weight'] = 'Veuillez entrer un poids maximum pour les avatars!';
$LANG['require_rank_name'] = 'Veuillez entrer un nom pour le rang!';
$LANG['require_nbr_msg_rank'] = 'Veuillez entrer un nombre de messages pour le rang!';
$LANG['require_subcat'] = 'Veuillez sélectionner une sous-catégorie!';
$LANG['require_file_name'] = 'Vous devez saisir un nom de fichier';

//Confirmations.
$LANG['redirect'] = 'Redirection en cours...';
$LANG['del_entry'] = 'Supprimer l\'entrée?';
$LANG['confirm_del_member'] = 'Supprimer le membre? (définitif !)';
$LANG['confirm_del_admin'] = 'Supprimer un admin? (irréversible !)';
$LANG['confirm_theme'] = 'Supprimer le thème?';
$LANG['confirm_del_smiley'] = 'Supprimer le smiley?';
$LANG['confirm_del_cat'] = 'Supprimer cette catégorie ?';
$LANG['confirm_del_article'] = 'Supprimer cet article?';
$LANG['confirm_del_rank'] = 'Supprimer ce rang ?';
$LANG['confirm_del_group'] = 'Supprimer ce groupe ?';
$LANG['confirm_del_member_group'] = 'Supprimer ce membre du groupe ?';

//bbcode
$LANG['bb_bold'] = 'Texte en gras : [b]texte[/b]';
$LANG['bb_italic'] = 'Texte en italique : [i]texte[/i]';
$LANG['bb_underline'] = 'Texte souligné : [u]texte[/u]';
$LANG['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$LANG['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$LANG['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$LANG['bb_color'] = 'Couleur du texte : [color=X]texte de taille X[/color]';
$LANG['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$LANG['bb_code'] = 'Insérer du code (PHP coloré) [code]texte[/code]';
$LANG['bb_left'] = 'Positionner à gauche : [align=left]objet à gauche[/align]';
$LANG['bb_center'] = 'Centrer : [align=center]objet centré[/align]';
$LANG['bb_right'] = 'Positionner à droite : [align=right]objet à droite[/align]';

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
$LANG['date'] = 'Date';
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
$LANG['flood_block'] = 'Il vous reste %d essai(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
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

//Champs supplémentaires
$LANG['extend_field_management'] = 'Gestion des champs membres';
$LANG['extend_field_add'] = 'Ajouter un champ membre';
$LANG['require_field'] = 'Champ requis';
$LANG['required_field_explain'] = 'Obligatoire dans le profil du membre et à son inscription.';
$LANG['required'] = 'Requis';
$LANG['not_required'] = 'Non requis';
$LANG['regex'] = 'Contrôle de la forme de l\'entrée';
$LANG['regex_explain'] = 'Permet d\'effectuer un contrôle sur la forme de ce que l\'utilisateur a entrée. Par exemple, si il s\'agit d\'une adresse mail, on peut contrôler que sa forme est correcte. <br />Vous pouvez effectuer un contrôle personnalié en tapant une expression régulière (utilisateurs expérimentés seulement).';
$LANG['possible_values'] = 'Valeurs possibles';
$LANG['possible_values_explain'] = 'Séparez les différentes valeurs par le symbole |';
$LANG['default_values'] = 'Valeurs par défaut';
$LANG['default_values_explain'] = 'Séparez les différentes valeurs par le symbole |';
$LANG['short_text'] = 'Texte court (max 255 caractères)';
$LANG['long_text'] = 'Texte long (illimité)';
$LANG['sel_uniq'] = 'Sélection unique (parmi plusieurs valeurs)';
$LANG['sel_mult'] = 'Sélection multiple (parmi plusieurs valeurs)';
$LANG['check_uniq'] = 'Choix unique (parmi plusieurs valeurs)';
$LANG['check_mult'] = 'Choix multiples (parmi plusieurs valeurs)';
$LANG['personnal_regex'] = 'Expression régulière personnalisée';
$LANG['predef_regexp'] = 'Forme prédéfinie';
$LANG['figures'] = 'Chiffres';
$LANG['letters'] = 'Lettres';
$LANG['figures_letters'] = 'Chiffres et lettres';
$LANG['default_field_possible_values'] = 'Oui|Non';
$LANG['extend_field_edit'] = 'Editer le champs';

//Index
$LANG['update_available'] = 'Mises à jour disponibles';
$LANG['core_update_available'] = 'Nouvelle version <strong>%s</strong> du noyau disponible, pensez à mettre à jour PHPBoost! <a href="http://www.phpboost.com">Plus d\'informations</a>';
$LANG['no_core_update_available'] = 'Aucune nouvelle version disponible, le système est à jour!';
$LANG['module_update_available'] = 'Des mises à jour des modules sont disponibles!';
$LANG['no_module_update_available'] = 'Aucune mise à jour des modules, vous êtes à jour!';
$LANG['unknow_update'] = 'Impossible de déterminer si une mise à jour est disponible!';
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
	
//Config
$LANG['config_main'] = 'Configuration générale';
$LANG['config_advanced'] = 'Configuration avancée';
$LANG['serv_name'] = 'URL du serveur';
$LANG['serv_path'] = 'Chemin de PHPBoost';
$LANG['serv_path_explain'] = 'Vide par défaut : site à la racine du serveur';
$LANG['site_name'] = 'Nom du site';
$LANG['serv_name_explain'] = 'Ex : http://www.phpboost.com';
$LANG['site_desc'] = 'Description du site';
$LANG['site_desc_explain'] = '(facultatif) Utile pour le référencement dans les moteurs de recherche';
$LANG['site_keywords'] = 'Mots clés du site';
$LANG['site_keywords_explain'] = '(facultatif) A rentrer séparés par des virgules, ils servent au référencement dans les moteurs de recherche';
$LANG['default_language'] = 'Langue (par défaut) du site';
$LANG['default_theme'] = 'Thème (par défaut) du site';
$LANG['start_page'] = 'Page de démarrage du site';
$LANG['no_module_starteable'] = 'Aucun module de démarrage trouvé';
$LANG['other_start_page'] = 'Autre adresse relative ou absolue';
$LANG['activ_gzhandler'] = 'Activation de la compression des pages, ceci accélère la vitesse d\'affichage';
$LANG['activ_gzhandler_explain'] = 'Attention votre serveur doit le supporter';
$LANG['view_com'] = 'Affichage des commentaires';
$LANG['rewrite'] = 'Activation de la réécriture des urls';
$LANG['explain_rewrite'] = 'L\'activation de la réécriture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compréhensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre référencement sera grandement optimisé grâce à cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les hébergeurs. Cette page va vous permettre de tester si votre serveur supporte la réécriture des urls. Si après le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> à la racine de votre site via accès FTP à votre serveur, puis revenez sur cette page et désactivez la réécriture.';
$LANG['server_rewrite'] = 'Réécriture des urls sur votre serveur';
$LANG['htaccess_manual_content'] = 'Contenu du fichier .htaccess';
$LANG['htaccess_manual_content_explain'] = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier .htaccess qui se trouve à la racine du site, par exemple pour forcer une configuration du serveur web Apache.';
$LANG['current_page'] = 'Page courante';
$LANG['new_page'] = 'Nouvelle fenêtre';
$LANG['compt'] = 'Compteur';
$LANG['bench'] = 'Benchmark';
$LANG['bench_explain'] = 'Affiche le temps de rendu de la page et le nombre de requêtes SQL';
$LANG['theme_author'] = 'Info sur le thème';
$LANG['theme_author_explain'] = 'Affiche des informations sur le thème dans le pied de page';
$LANG['debug_mode'] = 'Mode Debug';
$LANG['debug_mode_explain'] = 'Ce mode est particulièrement utile pour les développeurs car les erreurs sont affichées explicitement. Il est déconseillé d\'utiliser ce mode sur un site en production.';
$LANG['user_connexion'] = 'Connexion utilisateurs';
$LANG['cookie_name'] = 'Nom du cookie des sessions';
$LANG['session_time'] = 'Durée de la session';
$LANG['session_time_explain'] = '3600 secondes conseillé';
$LANG['session invit'] = 'Durée utilisateurs actifs';
$LANG['session invit_explain'] = '300 secondes conseillé';
$LANG['post_management'] = 'Gestion des posts';
$LANG['pm_max'] = 'Nombre maximum de messages privés';
$LANG['anti_flood'] = 'Anti-flood';
$LANG['int_flood'] = 'Intervalle minimal de temps entre les messages';
$LANG['pm_max_explain'] = 'Illimité pour administrateurs et modérateurs';
$LANG['anti_flood_explain'] = 'Empêche les messages trop rapprochés, sauf si les visiteurs sont autorisés';
$LANG['int_flood_explain'] = '7 secondes par défaut';
$LANG['email_management'] = 'Gestion des emails';
$LANG['email_admin_exp'] = 'Email d\'expédition';
$LANG['email_admin_explain_exp'] = 'Email qui sera vu par le destinataire';
$LANG['email_admin'] = 'Emails des administrateurs';
$LANG['admin_sign'] = 'Signature du mail';
$LANG['email_admin_explain'] = 'Séparez les mails par ;';
$LANG['admin_sign_explain'] = 'En bas de tous les mails envoyés par le site';
$LANG['cache_success'] = 'Le cache a été régénéré avec succès!';
$LANG['explain_site_cache'] = 'Régénération totale du cache du site à partir de la base de données.
<br /><br />Le cache permet d\'améliorer notablement la vitesse d\'exécution des pages, et allège le travail du serveur SQL. A noter que si vous faites des modifications vous-même dans la base de données, elles ne seront visibles qu\'après avoir régénéré le cache';
$LANG['explain_site_cache_syndication'] = 'Régénération totale du cache des flux RSS et ATOM du site à partir de la base de données.
<br /><br />Le cache permet d\'améliorer notablement la vitesse d\'exécution des pages, et allège le travail du serveur SQL. A noter que si vous faites des modifications vous-même dans la base de données, elles ne seront visibles qu\'après avoir régénéré le cache';
$LANG['confirm_unlock_admin'] = 'Un email va vous être envoyé avec le code de déverrouillage';
$LANG['unlock_admin_confirm'] = 'Le code de déverrouillage a été renvoyé avec succès';
$LANG['unlock_admin'] = 'Code de déverrouillage';
$LANG['unlock_admin_explain'] = 'Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné.';
$LANG['send_unlock_admin'] = 'Renvoyer le code de déverrouillage';
$LANG['unlock_title_mail'] = 'Mail à conserver';
$LANG['unlock_mail'] = 'Code à conserver (Il ne vous sera plus délivré) : %s

Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné.
Il vous sera demandé dans le formulaire de connexion directe à l\'administration (votreserveur/admin/admin_index.php)

' . $CONFIG['sign'];

//Maintain
$LANG['maintain_auth'] = 'Autorisation d\'accès au site durant la maintenance';
$LANG['maintain_for'] = 'Mettre le site en maintenance';
$LANG['maintain_delay'] = 'Afficher la durée de la maintenance';
$LANG['maintain_display_admin'] = 'Afficher la durée de la maintenance à l\'administrateur';
$LANG['maintain_text'] = 'Texte à afficher lorsque la maintenance du site est en cours';
	
//Gestion des modules
$LANG['modules_management'] = 'Gestion des modules';
$LANG['add_modules'] = 'Ajouter un module';
$LANG['update_modules'] = 'Mettre à jour un module';
$LANG['update_module'] = 'Mettre à jour';
$LANG['upload_module'] = 'Uploader un module';
$LANG['del_module'] = 'Supprimer le module';
$LANG['del_module_data'] = 'Les données du module vont être supprimées, attention vous ne pourrez plus les récupérer!';
$LANG['del_module_files'] = 'Supprimer les fichiers du module';
$LANG['author'] = 'Auteurs';
$LANG['compat'] = 'Compatibilité';
$LANG['use_sql'] = 'Utilise SQL';
$LANG['use_cache'] = 'Utilise le cache';
$LANG['alternative_css'] = 'Utilise un css alternatif';
$LANG['modules_installed'] = 'Modules installés';
$LANG['modules_available'] = 'Modules disponibles';
$LANG['no_modules_installed'] = 'Aucun module installé';
$LANG['no_modules_available'] = 'Aucun module disponible';
$LANG['install'] = 'Installer';
$LANG['uninstall'] = 'Désinstaller';
$LANG['starteable_page'] = 'Page de démarrage';
$LANG['table'] = 'Table';
$LANG['tables'] = 'Tables';
$LANG['new_version'] = 'Nouvelle version';
$LANG['installed_version'] = 'Version installée';
$LANG['e_config_conflict'] = 'Conflit avec la configuration du module, installation impossible!';

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
$LANG['system_report_summerization_explain'] = 'Ceci est le récapitulatif du rapport. Cela vous sera particulièrement utile lorsque pour du support on vous demandera la configuration de votre système';

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
$LANG['confirm_del_menu'] = 'Supprimer ce menu?';
$LANG['confirm_delete_element'] = 'Voulez vous vraiment supprimer cet élément?';
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

//Gestion du contenu
$LANG['content_config'] = 'Contenu';
$LANG['content_config_extend'] = 'Configuration du contenu';
$LANG['default_formatting_language'] = 'Langage de formatage du contenu par défaut du site
<span style="display:block;">Chaque utilisateur pourra choisir</span>';
$LANG['content_language_config'] = 'Langage de formatage';
$LANG['content_html_language'] = 'Langage HTML';
$LANG['content_auth_use_html'] = 'Niveau d\'autorisation pour insérer du langage HTML
<span style="display:block">Attention : le code HTML peut contenir du code Javascript qui peut constituer une source de faille de sécurité si quelqu\'un y insère un code malveillant. Veillez donc à n\'autoriser seulement les personnes de confiance à insérer du HTML.</span>';

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
$LANG['no_theme_on_serv'] = 'Aucun thème <strong>compatible</strong> disponible sur le serveur';
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
$LANG['explain_default_theme'] = 'Le thème par défaut ne peut pas être désinstallé, désactivé, ou réservé';
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
	
//Comments
$LANG['com_management'] = 'Gestion des commentaires';
$LANG['com_config'] = 'Configuration des commentaires';
$LANG['com_max'] = 'Nombre de commentaires par page';
$LANG['rank_com_post'] = 'Rang pour pouvoir poster des commentaires';
$LANG['display_topic_com'] = 'Voir la discussion';
$LANG['display_recent_com'] = 'Voir les derniers commentaires';

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
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'heure';
$LANG['hours'] = 'heures';
$LANG['day'] = 'jour';
$LANG['days'] = 'jours';
$LANG['week'] = 'semaine';
$LANG['month'] = 'mois';
$LANG['life'] = 'A vie';
$LANG['confirm_password'] = 'Confirmer le mot de passe';
$LANG['confirm_password_explain'] = 'Remplir seulement en cas de modification';
$LANG['hide_mail'] = 'Cacher l\'email';
$LANG['hide_mail_explain'] = 'Aux autres utilisateurs';
$LANG['website_explain'] = 'Valide sinon non pris en compte';
$LANG['member_sign'] = 'Signature';
$LANG['member_sign_explain'] = 'Apparaît sous chacun de vos messages';
$LANG['avatar_management'] = 'Gestion avatar';
$LANG['activ_up_avatar'] = 'Autoriser l\'upload d\'avatar sur le serveur';
$LANG['current_avatar'] = 'Avatar actuel';
$LANG['upload_avatar'] = 'Uploader avatar';
$LANG['upload_avatar_where'] = 'Avatar directement hébergé sur le serveur';
$LANG['avatar_link'] = 'Lien avatar';
$LANG['avatar_link_where'] = 'Adresse directe de l\'avatar';
$LANG['avatar_del'] = 'Supprimer l\'avatar courant';
$LANG['no_avatar'] = 'Aucun avatar';
$LANG['weight_max'] = 'Poids maximum';
$LANG['height_max'] = 'Hauteur maximale';
$LANG['width_max'] = 'Largeur maximale';
$LANG['sex'] = 'Sexe';
$LANG['male'] = 'Homme';
$LANG['female'] = 'Femme';
$LANG['verif_code'] = 'Code de vérification visuel';
$LANG['verif_code_explain'] = 'Bloque les robots';
$LANG['delay_activ_max'] = 'Durée après laquelle les membres non activés sont effacés';
$LANG['delay_activ_max_explain'] = 'Laisser vide pour ignorer cette option (Non pris en compte si validation par administrateur)';
$LANG['activ_mbr'] = 'Mode d\'activation du compte membre';
$LANG['no_activ_mbr'] = 'Automatique';
$LANG['allow_theme_mbr'] = 'Permission aux membres de choisir leur thème';
$LANG['width_max_avatar'] = 'Largeur maximale de l\'avatar';
$LANG['width_max_avatar_explain'] = 'Par défaut 120';
$LANG['height_max_avatar'] = 'Hauteur maximale de l\'avatar';
$LANG['height_max_avatar_explain'] = 'Par défaut 120';
$LANG['weight_max_avatar'] = 'Poids maximal de l\'avatar en ko';
$LANG['weight_max_avatar_explain'] = 'Par défaut 20';
$LANG['avatar_management'] = 'Gestion des avatars';
$LANG['activ_defaut_avatar'] = 'Activer l\'avatar par défaut';
$LANG['activ_defaut_avatar_explain'] = 'Met un avatar aux membres qui n\'en ont pas';
$LANG['url_defaut_avatar'] = 'Adresse de l\'avatar par défaut';
$LANG['url_defaut_avatar_explain'] = 'Mettre dans le dossier images de votre thème ';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais ne peut plus poster sur le site entier (commentaires, etc...)';
$LANG['weeks'] = 'semaines';
$LANG['life'] = 'A vie';
$LANG['readonly_user'] = 'Membre en lecture seule';
$LANG['activ_register'] = 'Activer l\'inscription des membres';

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

//Erreurs
$LANG['all_errors'] = 'Afficher toutes les erreurs';
$LANG['error_management'] = 'Gestionnaire d\'erreurs';

//Divers
$LANG['select_type_bbcode'] = 'BBCode';
$LANG['select_type_html'] = 'HTML';

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
$LANG['january'] = 'Janvier';
$LANG['february'] = 'Février';
$LANG['march'] = 'Mars';
$LANG['april'] = 'Avril';
$LANG['may'] = 'Mai';
$LANG['june'] = 'Juin';
$LANG['july'] = 'Juillet';
$LANG['august'] = 'Août';
$LANG['september'] = 'Septembre';
$LANG['october'] = 'Octobre';
$LANG['november'] = 'Novembre';
$LANG['december'] = 'Décembre';
$LANG['monday'] = 'Lun';
$LANG['tuesday'] = 'Mar';
$LANG['wenesday'] = 'Mer';
$LANG['thursday'] = 'Jeu';
$LANG['friday'] = 'Ven';
$LANG['saturday'] = 'Sam';
$LANG['sunday']	= 'Dim';

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
