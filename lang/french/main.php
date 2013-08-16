<?php
/*##################################################
 *                                main.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : mickaelhemri@gmail.com
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
#                      French                      #
 ####################################################

// Dates
$LANG['xml_lang'] = 'fr';
$LANG['date_format_day_month'] = 'd/m';
$LANG['date_format_day_month_year'] = 'd/m/Y';
$LANG['date_format_day_month_year_hour_minute'] = 'd/m/Y \à H\hi';
$LANG['date_format_day_month_year_long'] = 'j F Y';
$LANG['date_format_day_month_year_text'] = 'l j F Y';
$LANG['date_format_day_month_year_hour_minute_text'] = 'l j F Y \à H\hi';
$LANG['from_date'] = 'du';
$LANG['to_date'] = 'au';
$LANG['now'] = 'Maintenant';

//Unités
$LANG['unit_megabytes'] = 'Mo';
$LANG['unit_kilobytes'] = 'Ko';
$LANG['unit_bytes'] = 'Octets';
$LANG['unit_pixels'] = 'px';
$LANG['unit_hour'] = 'h';
$LANG['unit_seconds'] = 'Secondes';
$LANG['unit_seconds_short'] = 's';
	
//Erreurs
$LANG['error'] = 'Erreur';
$LANG['error_fatal'] = '<strong>Erreur fatale :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$LANG['error_warning_tiny'] = '<strong>Attention :</strong> %s %s %s';
$LANG['error_warning'] = '<strong>Attention :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$LANG['error_notice_tiny'] = '<strong>Remarque :</strong> %s %s %s';
$LANG['error_notice'] = '<strong>Remarque :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$LANG['error_success'] = '<strong>Succès :</strong> %s %s %s';
$LANG['error_unknow'] = '<strong>Erreur :</strong> Cause inconnue %s %s %s';

//Titres divers
$LANG['title_pm'] = 'Messages privés';
$LANG['title_error'] = 'Erreur';
$LANG['title_com'] = 'Commentaires';
$LANG['title_register'] = 'S\'enregistrer';

//Form
$LANG['submit'] = 'Envoyer';
$LANG['update'] = 'Modifier';
$LANG['reset'] = 'Défaut';
$LANG['erase'] = 'Effacer';
$LANG['preview'] = 'Prévisualiser';
$LANG['search'] = 'Recherche';
$LANG['connect'] = 'Se connecter';
$LANG['disconnect'] = 'Se déconnecter';
$LANG['autoconnect'] = 'Connexion auto';
$LANG['password'] = 'Mot de passe';
$LANG['respond'] = 'Répondre';
$LANG['go'] = 'Go';

$LANG['pseudo'] = 'Pseudo';
$LANG['message'] = 'Message';
$LANG['message_s'] = 'Messages';

$LANG['require'] = 'Les champs marqués * sont obligatoires !';
$LANG['required_field'] = 'Le champs \"%s\" est obligatoire !';

//Alertes formulaires
$LANG['require_title'] = 'Veuillez entrer un titre !';
$LANG['require_text'] = 'Veuillez entrer un texte !';
$LANG['require_pseudo'] = 'Veuillez entrer un pseudo !';
$LANG['require_mail'] = 'Veuillez entrer un mail valide !';
$LANG['require_subcat'] = 'Veuillez sélectionner une sous-catégorie !';
$LANG['require_url'] = 'Veuillez entrer une url valide !';
$LANG['require_password'] = 'Veuillez entrer un mot de passe !';
$LANG['require_recipient'] = 'Veuillez entrer le destinataire du message !';

//Action
$LANG['redirect'] = 'Redirection en cours';
$LANG['delete'] = 'Supprimer';
$LANG['edit'] = 'Editer';
$LANG['register'] = 'S\'inscrire';

//Alertes
$LANG['alert_delete_msg'] = 'Supprimer le/les message(s) ?';
$LANG['alert_delete_file'] = 'Supprimer ce fichier ?';

//Impression
$LANG['printable_version'] = 'Version imprimable';

//Connexion
$LANG['private_messaging'] = 'Messagerie privée';
$LANG['my_private_profile'] = 'Mon profil';

//Maintain
$LANG['maintain'] = 'Le site est actuellement en maintenance. Merci de votre patience.';
$LANG['maintain_delay'] = 'Délai estimé avant réouverture du site :';
$LANG['title_maintain'] = 'Site en maintenance';
$LANG['loading'] = 'Chargement';

//Commun
$LANG['user'] = 'Utilisateur';
$LANG['user_s'] = 'Utilisateurs';
$LANG['guest'] = 'Visiteur';
$LANG['guest_s'] = 'Visiteurs';
$LANG['member'] = 'Membre';
$LANG['member_s'] = 'Membres';
$LANG['members_list'] = 'Liste des membres';
$LANG['modo'] = 'Modérateur';
$LANG['modo_s'] = 'Modérateurs';
$LANG['admin'] = 'Administrateur';
$LANG['admin_s'] = 'Administrateurs';
$LANG['home'] = 'Accueil';
$LANG['date'] = 'Date';
$LANG['today'] = 'Aujourd\'hui';
$LANG['day'] = 'Jour';
$LANG['day_s'] = 'Jours';
$LANG['month'] = 'Mois';
$LANG['months'] = 'Mois';
$LANG['year'] = 'An';
$LANG['years'] = 'Ans';
$LANG['description'] = 'Description';
$LANG['view'] = 'Vu';
$LANG['views'] = 'Vues';
$LANG['name'] = 'Nom';
$LANG['properties'] = 'Propriétés';
$LANG['image'] = 'Image';
$LANG['note'] = 'Note';
$LANG['notes'] = 'Notes';
$LANG['valid_note'] = 'Noter';
$LANG['no_note'] = 'Aucune note';
$LANG['previous'] = 'Précédent';
$LANG['next'] = 'Suivant';
$LANG['mail'] = 'Mail';
$LANG['objet'] = 'Objet';
$LANG['content'] = 'Contenu';
$LANG['options'] = 'Options';
$LANG['all'] = 'Tout';
$LANG['title'] = 'Titre';
$LANG['title_s'] = 'Titres';
$LANG['n_time'] = 'Fois';
$LANG['written_by'] = 'Ecrit par';
$LANG['valid'] = 'Valide';
$LANG['info'] = 'Informations';
$LANG['asc'] = 'Croissant';
$LANG['desc'] = 'Décroissant';
$LANG['list'] = 'Liste';
$LANG['welcome'] = 'Bienvenue';
$LANG['currently'] = 'Actuellement';
$LANG['place'] = 'Lieu';
$LANG['quote'] = 'Citer';
$LANG['quotation'] = 'Citation';
$LANG['hide'] = 'Caché';
$LANG['default'] = 'Défaut';
$LANG['type'] = 'Type';
$LANG['status'] = 'Statut';
$LANG['url'] = 'Url';
$LANG['replies'] = 'Réponses';
$LANG['back'] = 'Retour';
$LANG['close'] = 'Fermer';
$LANG['smiley'] = 'Smiley';
$LANG['all_smiley'] = 'Tous les smileys';
$LANG['total'] = 'Total';
$LANG['average'] = 'Moyenne';
$LANG['page'] = 'Page';
$LANG['illimited'] = 'Illimité';
$LANG['seconds'] = 'secondes';
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'heure';
$LANG['hours'] = 'heures';
$LANG['day'] = 'jour';
$LANG['days'] = 'jours';
$LANG['week'] = 'semaine';
$LANG['unspecified'] = 'Non spécifié';
$LANG['admin_panel'] = 'Panneau d\'administration';
$LANG['modo_panel'] = 'Panneau de modération';
$LANG['group'] = 'Groupe';
$LANG['groups'] = 'Groupes';
$LANG['size'] = 'Taille';
$LANG['theme'] = 'Thème';
$LANG['online'] = 'En ligne';
$LANG['modules'] = 'Modules';
$LANG['no_result'] = 'Aucun résulat';
$LANG['during'] = 'Pendant';
$LANG['until'] = 'Jusqu\'au';
$LANG['lock'] = 'Verrouiller';
$LANG['unlock'] = 'Déverrouiller';
$LANG['upload'] = 'Uploader';
$LANG['subtitle'] = 'Sous-titre';
$LANG['style'] = 'Style';
$LANG['question'] = 'Question';
$LANG['notice'] = 'Remarque';
$LANG['warning'] = 'Attention';
$LANG['success'] = 'Succès';
$LANG['vote'] = 'Vote';
$LANG['votes'] = 'Votes';
$LANG['already_vote'] = 'Vous avez déjà voté';
$LANG['miscellaneous'] = 'Divers';
$LANG['unknow'] = 'Inconnu';
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['orderby'] = 'Ordonner par';
$LANG['direction'] = 'Direction';
$LANG['other'] = 'Autre';
$LANG['aprob'] = 'Approuver';
$LANG['unaprob'] = 'Désapprouver';
$LANG['unapproved'] = 'Désapprouvé';
$LANG['final'] = 'Définitif';
$LANG['pm'] = 'Mp';
$LANG['code'] = 'Code';
$LANG['code_tag'] = 'Code :';
$LANG['code_langage'] = 'Code %s :';
$LANG['com'] = 'Commentaire';
$LANG['com_s'] = 'Commentaires';
$LANG['no_comment'] = 'Aucun commentaire';
$LANG['post_com'] = 'Poster commentaire';
$LANG['com_locked'] = 'Les commentaires sont verrouillés pour cet élément';
$LANG['add_msg'] = 'Ajout message';
$LANG['update_msg'] = 'Modifier le message';
$LANG['category'] = 'Catégorie';
$LANG['categories'] = 'Catégories';
$LANG['refresh'] = 'Rafraichir';
$LANG['ranks'] = 'Rangs';
$LANG['previous_page'] = 'Page précédente';
$LANG['next_page'] = 'Page suivante';
$LANG['never'] = 'Jamais';

//Dates.
$LANG['on'] = 'Le';
$LANG['at'] = 'à';
$LANG['and'] = 'et';
$LANG['by'] = 'Par';

//Gestion formulaires autorisation
$LANG['authorizations'] = 'Autorisations';
$LANG['explain_select_multiple'] = 'Maintenez ctrl puis cliquez dans la liste pour faire plusieurs choix';
$LANG['advanced_authorization'] = 'Autorisations avancées';
$LANG['select_all'] = 'Tout sélectionner';
$LANG['select_none'] = 'Tout désélectionner';
$LANG['add_member'] = 'Ajouter un membre';
$LANG['alert_member_already_auth'] = 'Le membre est déjà dans la liste';

//Membres
$LANG['member_area'] = 'Zone membre';
$LANG['profile'] = 'Profil';
$LANG['fill_only_if_modified'] = 'Remplir seulement en cas de modification';
$LANG['mail_track_topic'] = 'Etre averti par email lors d\'une réponse dans un sujet que vous suivez';
$LANG['web_site'] = 'Site web';
$LANG['localisation'] = 'Localisation';
$LANG['job'] = 'Emploi';
$LANG['hobbies'] = 'Loisirs';
$LANG['sex'] = 'Sexe';
$LANG['male'] = 'Homme';
$LANG['female'] = 'Femme';
$LANG['age'] = 'Age';
$LANG['biography'] = 'Biographie';
$LANG['years_old'] = 'Ans';
$LANG['sign'] = 'Signature';
$LANG['sign_where'] = 'Apparaît sous chacun de vos messages';
$LANG['contact'] = 'Contact';
$LANG['avatar'] = 'Avatar';
$LANG['avatar_gestion'] = 'Gestion avatar';
$LANG['current_avatar'] = 'Avatar actuel';
$LANG['upload_avatar'] = 'Uploader avatar';
$LANG['upload_avatar_where'] = 'Avatar directement hébergé sur le serveur';
$LANG['avatar_link'] = 'Lien avatar';
$LANG['avatar_link_where'] = 'Adresse directe de l\'avatar';
$LANG['avatar_del'] = 'Supprimer l\'avatar courant';
$LANG['no_avatar'] = 'Aucun avatar';
$LANG['registered'] = 'Inscrit';
$LANG['registered_s'] = 'Inscrits';
$LANG['registered_on'] = 'Inscrit le';
$LANG['last_connect'] = 'Dernière connexion';
$LANG['private_message'] = 'Message(s) privé(s)';
$LANG['nbr_message'] = 'Nombre de message(s)';
$LANG['member_msg_display'] = 'Afficher les messages du membre';
$LANG['member_msg'] = 'Messages du membre';
$LANG['member_online'] = 'Membres en ligne';
$LANG['no_member_online'] = 'Aucun membre connecté';
$LANG['del_member'] = 'Suppression du compte <span class="text_small">(Définitif!)</span>';
$LANG['choose_lang'] = 'Langue par défaut';
$LANG['choose_theme'] = 'Thème par défaut';
$LANG['choose_editor'] = 'Editeur de texte par défaut';
$LANG['theme_s'] = 'Thèmes';
$LANG['select_group'] = 'Sélectionnez un groupe';
$LANG['search_member'] = 'Chercher un membre';
$LANG['date_of_birth'] = 'Date de naissance';
$LANG['date_birth_format'] = 'JJ/MM/AAAA';
$LANG['date_birth_parse'] = 'DD/MM/YYYY';
$LANG['banned'] = 'Banni';
$LANG['go_msg'] = 'Aller au message';
$LANG['display'] = 'Afficher';
$LANG['site_config_msg_mbr'] = 'Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.';
$LANG['register_agreement'] = 'Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d\'être poli et courtois dans vos interventions.
Merci, l\'équipe du site.';

//Mp
$LANG['pm_box'] = 'Boîte de réception';
$LANG['pm_track'] = 'Non lu par le destinataire';
$LANG['recipient'] = 'Destinataire';
$LANG['post_new_convers'] = 'Créer une nouvelle conversation';
$LANG['read'] = 'Lu';
$LANG['not_read'] = 'Non lu';
$LANG['last_message'] = 'Dernier message';
$LANG['mark_pm_as_read'] = 'Marquer tous les messages privés comme lus';
$LANG['participants'] = 'Participant(s)';
$LANG['no_pm'] = 'Aucun message';
$LANG['quote_last_msg'] = 'Reprise du message précédent';

//Gestion des fichiers
$LANG['confim_del_file'] = 'Supprimer ce fichier ?';
$LANG['confirm_del_folder'] = 'Supprimer ce dossier, et tout son contenu ?';
$LANG['confirm_empty_folder'] = 'Vider tout le contenu de ce dossier ?';
$LANG['file_forbidden_chars'] = 'Le nom du fichier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$LANG['folder_forbidden_chars'] = 'Le nom du dossier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$LANG['files_management'] = 'Gestion des fichiers';
$LANG['files_config'] = 'Configuration des fichiers';
$LANG['file_add'] = 'Ajouter un fichier';
$LANG['data'] = 'Total des données';
$LANG['folders'] = 'Répertoires';
$LANG['folders_up'] = 'Répertoire parent';
$LANG['folder_new'] = 'Nouveau dossier';
$LANG['empty_folder'] = 'Ce dossier est vide';
$LANG['empty_member_folder'] = 'Vider ce dossier ?';
$LANG['del_folder'] = 'Supprimer ce dossier ?';
$LANG['folder_already_exist'] = 'Le dossier existe déjà !';
$LANG['empty'] = 'Vider';
$LANG['root'] = 'Racine';
$LANG['files'] = 'Fichiers';
$LANG['files_del_failed'] = 'La suppression des fichiers a échouée, veuillez le faire manuellement';
$LANG['folder_size'] = 'Taille du dossier';
$LANG['file_type'] = 'Fichier %s';
$LANG['image_type'] = 'Image %s';
$LANG['audio_type'] = 'Fichier audio %s';
$LANG['zip_type'] = 'Archive %s';
$LANG['adobe_pdf'] = 'Adobe Document';
$LANG['document_type'] = 'Document %s';
$LANG['moveto'] = 'Déplacer vers';
$LANG['success_upload'] = 'Votre fichier a bien été enregistré !';
$LANG['upload_folder_contains_folder'] = 'Vous souhaitez placer cette catégorie dans une de ses catégories filles ou dans elle-même, ce qui est impossible !';
$LANG['popup_insert'] = 'Insérer le code dans le formulaire';

//gestion des catégories
$LANG['cats_managment_could_not_be_moved'] = 'Une erreur est survenue, la catégorie n\'a pas pu être déplacée';
$LANG['cats_managment_visibility_could_not_be_changed'] = 'Une erreur est survenue, la visibilité de la catégorie n\'a pas pu être changée';
$LANG['cats_managment_no_category_existing'] = 'Aucune catégorie n\'existe';
$LANG['cats_management_confirm_delete'] = 'Etes-vous sur de vouloir supprimer cette catégorie ?';
$LANG['cats_management_hide_cat'] = 'Rendre la catégorie invisible';
$LANG['cats_management_show_cat'] = 'Rendre la catégorie visible';

##########Panneau de modération##########
$LANG['moderation_panel'] = 'Panneau de modération';
$LANG['user_contact_pm'] = 'Contacter par message privé';
$LANG['user_alternative_pm'] = 'Message privé envoyé au membre <span class="text_small">(Laisser vide pour aucun message privé)</span>. <br />Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.';

//Gestion des sanctions
$LANG['punishment'] = 'Sanctions';
$LANG['punishment_management'] = 'Gestion des sanctions';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['no_punish'] = 'Il n\'y a aucun utilisateur sanctionné.';
$LANG['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais plus poster sur le site entier (commentaires, etc...)';
$LANG['weeks'] = 'semaines';
$LANG['life'] = 'A vie';
$LANG['readonly_user'] = 'Membre en lecture seule';
$LANG['read_only_title'] = 'Sanction';
$LANG['user_readonly_changed'] = 'Vous avez été mis en lecture seule par un membre de l\'équipe de modération, vous ne pourrez plus poster pendant %date%.


Ceci est un message semi-automatique.';

//Gestion des utilisateurs avertis
$LANG['warning'] = 'Avertissements';
$LANG['warning_management'] = 'Gestion des avertissements';
$LANG['user_warning_level'] = 'Niveau d\'avertissement';
$LANG['no_user_warning'] = 'Il n\'y a aucun utilisateur averti.';
$LANG['user_warning_explain'] = 'Niveau d\'avertissement du membre. Vous pouvez le modifier, mais sachez qu\'à 100% le membre est banni.';
$LANG['change_user_warning'] = 'Changer le niveau';
$LANG['warning_title'] = 'Avertissement';
$LANG['user_warning_level_changed'] = 'Vous avez été averti par un membre de l\'équipe de modération, votre niveau d\'avertissement est passé à %level%%. Attention à votre comportement, si vous atteignez 100% vous serez banni définitivement.


Ceci est un message semi-automatique.';
$LANG['warning_user'] = 'Membre averti';

//Gestion des utilisateurs bannis.
$LANG['bans'] = 'Bannissements';
$LANG['ban_management'] = 'Gestion des bannissements';
$LANG['user_ban_until'] = 'Banni jusqu\'au';
$LANG['ban_user'] = 'Bannir';
$LANG['no_ban'] = 'Il n\'y a aucun utilisateur banni.';
$LANG['user_ban_delay'] = 'Durée du bannissement';
$LANG['ban_title_mail'] = 'Banni';
$LANG['ban_mail'] = 'Bonjour,

Vous avez été banni sur : %s !
S\'il s\'agit d\'une erreur veuillez contacter l\'administrateur du site.


%s';


//Panneau de contribution
$LANG['contribution_panel'] = 'Panneau de contribution';
$LANG['contribution'] = 'Contribution';
$LANG['contribution_status_unread'] = 'Non traitée';
$LANG['contribution_status_being_processed'] = 'En cours';
$LANG['contribution_status_processed'] = 'Traitée';
$LANG['contribution_entitled'] = 'Intitulé';
$LANG['contribution_description'] = 'Description';
$LANG['contribution_edition'] = 'Edition d\'une contribution';
$LANG['contribution_status'] = 'Statut';
$LANG['contributor'] = 'Contributeur';
$LANG['contribution_creation_date'] = 'Date de création';
$LANG['contribution_fixer'] = 'Responsable';
$LANG['contribution_fixing_date'] = 'Date de clôture';
$LANG['contribution_module'] = 'Module';
$LANG['process_contribution'] = 'Traiter la contribution';
$LANG['confirm_delete_contribution'] = 'Etes-vous sûr de vouloir supprimer cette contribution ?';
$LANG['no_contribution'] = 'Aucune contribution à afficher';
$LANG['contribution_list'] = 'Liste des contributions';
$LANG['contribute'] = 'Contribuer';
$LANG['contribute_in_modules_explain'] = 'Les modules suivants permettent aux utilisateurs de contribuer. Cliquez sur un module pour vous rendre dans son interface de contribution.';
$LANG['contribute_in_module_name'] = 'Contribuer dans le module %s';
$LANG['no_module_to_contribute'] = 'Aucun module supportant la contribution n\'est installé.';

//Barre de chargement.
$LANG['query_loading'] = 'Chargement de la requête au serveur';
$LANG['query_sent'] = 'Requête envoyée au serveur, attente d\'une réponse';
$LANG['query_processing'] = 'Traitement de la requête en cours';
$LANG['query_success'] = 'Traitement terminé';
$LANG['query_failure'] = 'Traitement échoué';

//Footer
$LANG['powered_by'] = 'Boosté par';
$LANG['phpboost_right'] = '';
$LANG['sql_req'] = 'Requêtes';
$LANG['achieved'] = 'Exécuté en';

//Flux
$LANG['syndication'] = 'Syndication';
$LANG['rss'] = 'RSS';
$LANG['atom'] = 'ATOM';

$LANG['enabled'] = 'Activé';
$LANG['disabled'] = 'Désactivé';

//Dictionnaire pour le captcha.
$LANG['_code_dictionnary'] = array('image', 'php', 'requete', 'azerty', 'exit', 'genre', 'design', 'web', 'inter', 'cache', 'media', 'cms', 'cesar', 'watt', 'site', 'mail', 'email', 'spam', 'index', 'membre',
'date', 'jour', 'mois', 'nom', 'noter', 'objet', 'options', 'titre', 'valide', 'liste', 'citer', 'fermer', 'minute', 'heure', 'semaine', 'groupe', 'taille', 'modules', 'pendant', 'style', 'divers', 'autre', 'erreur',
'editer', 'banni', 'niveau', 'dossier', 'fichier', 'racine', 'vider', 'archive', 'boite');
$LANG['verif_code'] = 'Code de vérification';
$LANG['verif_code_explain'] = 'Recopier le code sur l\'image, attention aux majuscules';
$LANG['require_verif_code'] = 'Veuillez saisir le code de vérification !';

$LANG['csrf_attack'] = 'Jeton de session invalide. Veuillez réessayer car l\'opération n\'a pas pu être effectuée.';

$LANG['forbidden_tags'] = 'Types de formatage interdits';
?>