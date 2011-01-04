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
 
$lang = array();

// Dates
$lang['xml_lang'] = 'fr';
$lang['date_format_tiny'] = 'd/m';
$lang['date_format_short'] = 'd/m/y';
$lang['date_format'] = 'd/m/y \à H\hi';
$lang['date_format_long'] = 'd/m/y \à H\hi\m\i\ns\s';
$lang['from_date'] = 'du';
$lang['to_date'] = 'au';
$lang['now'] = 'Maintenant';

//Unités
$lang['unit_megabytes'] = 'Mo';
$lang['unit_kilobytes'] = 'Ko';
$lang['unit_bytes'] = 'Octets';
$lang['unit_pixels'] = 'px';
$lang['unit_hour'] = 'h';
$lang['unit_seconds'] = 'Secondes';
$lang['unit_seconds_short'] = 's';
	
//Erreurs
$lang['error'] = 'Erreur';
$lang['error_fatal'] = '<strong>Erreur fatale :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$lang['error_warning_tiny'] = '<strong>Attention :</strong> %s %s %s';
$lang['error_warning'] = '<strong>Attention :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$lang['error_notice_tiny'] = '<strong>Remarque :</strong> %s %s %s';
$lang['error_notice'] = '<strong>Remarque :</strong> %s<br /><br /><br /><strong>Ligne %s : %s</strong>';
$lang['error_success'] = '<strong>Succès :</strong> %s %s %s';
$lang['error_unknow'] = '<strong>Erreur :</strong> Cause inconnue %s %s %s';

//Titres divers
$lang['title_pm'] = 'Messages privés';
$lang['title_error'] = 'Erreur';
$lang['title_com'] = 'Commentaires';
$lang['title_register'] = 'S\'enregistrer';
$lang['title_forget'] = 'Mot de passe oublié';

//Form
$lang['submit'] = 'Envoyer';
$lang['update'] = 'Modifier';
$lang['reset'] = 'Défaut';
$lang['erase'] = 'Effacer';
$lang['preview'] = 'Prévisualiser';
$lang['search'] = 'Recherche';
$lang['connect'] = 'Se connecter';
$lang['disconnect'] = 'Se déconnecter';
$lang['autoconnect'] = 'Connexion auto';
$lang['password'] = 'Mot de passe';
$lang['respond'] = 'Répondre';
$lang['go'] = 'Go';

$lang['pseudo'] = 'Pseudo';
$lang['message'] = 'Message';
$lang['message_s'] = 'Messages';
$lang['forget_pass'] = 'Mot de passe oublié';

$lang['require'] = 'Les champs marqués * sont obligatoires !';
$lang['required_field'] = 'Le champs \"%s\" est obligatoire !';

//Alertes formulaires
$lang['require_title'] = 'Veuillez entrer un titre !';
$lang['require_text'] = 'Veuillez entrer un texte !';
$lang['require_pseudo'] = 'Veuillez entrer un pseudo !';
$lang['require_mail'] = 'Veuillez entrer un mail valide !';
$lang['require_subcat'] = 'Veuillez sélectionner une sous-catégorie !';
$lang['require_url'] = 'Veuillez entrer une url valide !';
$lang['require_password'] = 'Veuillez entrer un mot de passe !';
$lang['require_recipient'] = 'Veuillez entrer le destinataire du message !';

//Action
$lang['redirect'] = 'Redirection en cours';
$lang['delete'] = 'Supprimer';
$lang['edit'] = 'Editer';
$lang['register'] = 'S\'inscrire';

//Alertes
$lang['alert_delete_msg'] = 'Supprimer le/les message(s) ?';
$lang['alert_delete_file'] = 'Supprimer ce fichier ?';

//bbcode
$lang['bb_smileys'] = 'Smileys';
$lang['bb_bold'] = 'Texte en gras : [b]texte[/b]';
$lang['bb_italic'] = 'Texte en italique : [i]texte[/i]';
$lang['bb_underline'] = 'Texte souligné : [u]texte[/u]';
$lang['bb_strike'] = 'Texte barré : [s]texte[/s]';
$lang['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$lang['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$lang['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$lang['bb_color'] = 'Couleur du texte : [color=X]texte de couleur X[/color]';
$lang['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$lang['bb_left'] = 'Positionner à gauche : [align=left]objet à gauche[/align]';
$lang['bb_center'] = 'Centrer : [align=center]objet centré[/align]';
$lang['bb_right'] = 'Positionner à droite : [align=right]objet à droite[/align]';
$lang['bb_justify'] = 'Justifier : [align=justify]objet justifié[/align]';
$lang['bb_code'] = 'Insérer du code [code]texte[/code]';
$lang['bb_math'] = 'Insérer du code mathématique [math]texte[/math]';
$lang['bb_swf'] = 'Insérer du flash [swf=largeur,hauteur]adresse animation[/swf]';
$lang['bb_small'] = 'Réduire le champ texte';
$lang['bb_large'] = 'Agrandir le champ texte';
$lang['bb_title'] = 'Titre [title=x]texte[/title]';
$lang['bb_html'] = 'Code html [html]code[/html]';
$lang['bb_container'] = 'Conteneur';
$lang['bb_block'] = 'Bloc';
$lang['bb_fieldset'] = 'Bloc champs';
$lang['bb_style'] = 'Style [style=x]texte[/style]';
$lang['bb_hide'] = 'Cache le texte, affiche lors du clic [hide]texte[/hide]';
$lang['bb_float_left'] = 'Objet flottant à gauche [float=left]texte[/float]';
$lang['bb_float_right'] = 'Objet flottant à droite [float=right]texte[/float]';
$lang['bb_list'] = 'Liste [list][*]texte1[*]texte2[/list]';
$lang['bb_table'] = 'Tableau [table][row][col]texte[/col][col]texte2[/col][/row][/table]';
$lang['bb_indent'] = 'Indentation [indent]texte[/indent]';
$lang['bb_sup'] = 'Exposant [sup]texte[/sup]';
$lang['bb_sub'] = 'Indice [sub]texte[/sub]';
$lang['bb_anchor'] = 'Ancre vers un endroit de la page [anchor=x]texte[/anchor]';
$lang['bb_sound'] = 'Son [sound]adresse du son[/sound]';
$lang['bb_movie'] = 'Vidéo [movie=largeur,hauteur]adresse du fichier[/movie]';
$lang['bb_help'] = 'Aide BBcode';
$lang['bb_upload'] = 'Attacher un fichier';
$lang['bb_url_prompt'] = 'Adresse du lien ?';
$lang['bb_text'] = 'Texte';
$lang['bb_script'] = 'Script';
$lang['bb_web'] = 'Web';
$lang['bb_prog'] = 'Programmation';
$lang['lines'] = 'Nombre de lignes';
$lang['cols'] = 'Nombre de colonnes';
$lang['head_table'] = 'Entête';
$lang['head_add'] = 'Ajouter l\'entête';
$lang['insert_table'] = 'Insérer le tableau';
$lang['ordered_list'] = 'Liste ordonnée';
$lang['insert_list'] = 'Insérer la liste';
$lang['forbidden_tags'] = 'Types de formatage interdits';
$lang['phpboost_languages'] = 'PHPBoost';
$lang['wikipedia_subdomain'] = 'fr'; //Sous domaine sur wikipédia (ex fr pour fr.wikipedia.org)
$lang['code_too_long_error'] = 'Le code que vous voulez colorer est trop long et consommerait trop de ressources pour être interprété. Merci de réduire sa taille ou de l\'éclater en plusieurs morceaux.';
$lang['feed_tag_error'] = 'Le flux du module <em>:module</em> que vous souhaitez afficher n\'a pas pu être trouvé ou les options que vous avez rentrées ne sont pas correctes.';
$lang['format_bold'] = 'Gras';
$lang['format_italic'] = 'Italique';
$lang['format_underline'] = 'Souligné';
$lang['format_strike'] = 'Barré';
$lang['format_title'] = 'Titre';
$lang['format_style'] = 'Style';
$lang['format_url'] = 'Lien';
$lang['format_img'] = 'Image';
$lang['format_quote'] = 'Citation';
$lang['format_hide'] = 'Caché';
$lang['format_list'] = 'Liste';
$lang['format_color'] = 'Couleur';
$lang['format_bgcolor'] = 'Couleur de fond';
$lang['format_font'] = 'Police';
$lang['format_size'] = 'Taille';
$lang['format_align'] = 'Alignement';
$lang['format_float'] = 'Elément flottant';
$lang['format_sup'] = 'Exposant';
$lang['format_sub'] = 'Indice';
$lang['format_indent'] = 'Indentation';
$lang['format_pre'] = 'Préformaté';
$lang['format_table'] = 'Tableau';
$lang['format_flash'] = 'Flash';
$lang['format_movie'] = 'Vidéo';
$lang['format_sound'] = 'Son';
$lang['format_code'] = 'Code';
$lang['format_math'] = 'Mathématiques';
$lang['format_anchor'] = 'Ancre';
$lang['format_acronym'] = 'Acronyme';
$lang['format_block'] = 'Bloc';
$lang['format_fieldset'] = 'Zone de champs';
$lang['format_mail'] = 'Mail';
$lang['format_line'] = 'Ligne horizontale';
$lang['format_wikipedia'] = 'Lien Wikipédia';
$lang['format_html'] = 'Code HTML';
$lang['format_feed'] = 'Flux';

//Impression
$lang['printable_version'] = 'Version imprimable';

//Connexion
$lang['private_messaging'] = 'Messagerie privée';
$lang['my_private_profile'] = 'Mon profil';

//Maintain
$lang['maintain'] = 'Le site est actuellement en maintenance merci de votre patience, seul(s) le(s) administrateur(s) du site peuvent y accèder.';
$lang['maintain_delay'] = 'Délai estimé avant réouverture du site :';
$lang['title_maintain'] = 'Site en maintenance';
$lang['loading'] = 'Chargement';

//Commun
$lang['user'] = 'Utilisateur';
$lang['user_s'] = 'Utilisateurs';
$lang['guest'] = 'Visiteur';
$lang['guest_s'] = 'Visiteurs';
$lang['member'] = 'Membre';
$lang['member_s'] = 'Membres';
$lang['members_list'] = 'Liste des membres';
$lang['modo'] = 'Modérateur';
$lang['modo_s'] = 'Modérateurs';
$lang['admin'] = 'Administrateur';
$lang['admin_s'] = 'Administrateurs';
$lang['home'] = 'Accueil';
$lang['date'] = 'Date';
$lang['today'] = 'Aujourd\'hui';
$lang['day'] = 'Jour';
$lang['day_s'] = 'Jours';
$lang['month'] = 'Mois';
$lang['months'] = 'Mois';
$lang['year'] = 'An';
$lang['years'] = 'Ans';
$lang['description'] = 'Description';
$lang['view'] = 'Vu';
$lang['views'] = 'Vues';
$lang['name'] = 'Nom';
$lang['properties'] = 'Propriétés';
$lang['image'] = 'Image';
$lang['note'] = 'Note';
$lang['notes'] = 'Notes';
$lang['valid_note'] = 'Noter';
$lang['no_note'] = 'Aucune note';
$lang['previous'] = 'Précédent';
$lang['next'] = 'Suivant';
$lang['mail'] = 'Mail';
$lang['objet'] = 'Objet';
$lang['content'] = 'Contenu';
$lang['options'] = 'Options';
$lang['all'] = 'Tout';
$lang['title'] = 'Titre';
$lang['title_s'] = 'Titres';
$lang['n_time'] = 'Fois';
$lang['written_by'] = 'Ecrit par';
$lang['valid'] = 'Valide';
$lang['info'] = 'Informations';
$lang['asc'] = 'Croissant';
$lang['desc'] = 'Decroissant';
$lang['list'] = 'Liste';
$lang['welcome'] = 'Bienvenue';
$lang['currently'] = 'Actuellement';
$lang['place'] = 'Lieu';
$lang['quote'] = 'Citer';
$lang['quotation'] = 'Citation';
$lang['hide'] = 'Caché';
$lang['default'] = 'Défaut';
$lang['type'] = 'Type';
$lang['status'] = 'Statut';
$lang['url'] = 'Url';
$lang['replies'] = 'Réponses';
$lang['back'] = 'Retour';
$lang['close'] = 'Fermer';
$lang['smiley'] = 'Smiley';
$lang['all_smiley'] = 'Tous les smileys';
$lang['total'] = 'Total';
$lang['average'] = 'Moyenne';
$lang['page'] = 'Page';
$lang['illimited'] = 'Illimité';
$lang['seconds'] = 'secondes';
$lang['minute'] = 'minute';
$lang['minutes'] = 'minutes';
$lang['hour'] = 'heure';
$lang['hours'] = 'heures';
$lang['day'] = 'jour';
$lang['days'] = 'jours';
$lang['week'] = 'semaine';
$lang['unspecified'] = 'Non spécifié';
$lang['admin_panel'] = 'Panneau d\'administration';
$lang['modo_panel'] = 'Panneau de modération';
$lang['group'] = 'Groupe';
$lang['groups'] = 'Groupes';
$lang['size'] = 'Taille';
$lang['theme'] = 'Thème';
$lang['online'] = 'En ligne';
$lang['modules'] = 'Modules';
$lang['no_result'] = 'Aucun résulat';
$lang['during'] = 'Pendant';
$lang['until'] = 'Jusqu\'au';
$lang['lock'] = 'Verrouiller';
$lang['unlock'] = 'Déverrouiller';
$lang['upload'] = 'Uploader';
$lang['subtitle'] = 'Sous-titre';
$lang['style'] = 'Style';
$lang['question'] = 'Question';
$lang['notice'] = 'Remarque';
$lang['warning'] = 'Attention';
$lang['success'] = 'Succès';
$lang['vote'] = 'Vote';
$lang['votes'] = 'Votes';
$lang['already_vote'] = 'Vous avez déjà voté';
$lang['miscellaneous'] = 'Divers';
$lang['unknow'] = 'Inconnu';
$lang['yes'] = 'Oui';
$lang['no'] = 'Non';
$lang['orderby'] = 'Ordonner par';
$lang['direction'] = 'Direction';
$lang['other'] = 'Autre';
$lang['aprob'] = 'Approuver';
$lang['unaprob'] = 'Désapprouver';
$lang['unapproved'] = 'Désapprouvé';
$lang['final'] = 'Définitif';
$lang['pm'] = 'Mp';
$lang['code'] = 'Code';
$lang['code_tag'] = 'Code :';
$lang['code_langage'] = 'Code %s :';
$lang['com'] = 'Commentaire';
$lang['com_s'] = 'Commentaires';
$lang['no_comment'] = 'Aucun commentaire';
$lang['post_com'] = 'Poster commentaire';
$lang['com_locked'] = 'Les commentaires sont verrouillés pour cet élément';
$lang['add_msg'] = 'Ajout message';
$lang['update_msg'] = 'Modifier le message';
$lang['category'] = 'Catégorie';
$lang['categories'] = 'Catégories';
$lang['refresh'] = 'Rafraichir';
$lang['ranks'] = 'Rangs';
$lang['previous_page'] = 'Page précédente';
$lang['next_page'] = 'Page suivante';

//Dates.
$lang['on'] = 'Le';
$lang['at'] = 'à';
$lang['and'] = 'et';
$lang['by'] = 'Par';

//Gestion formulaires autorisation
$lang['authorizations'] = 'Autorisations';
$lang['explain_select_multiple'] = 'Maintenez ctrl puis cliquez dans la liste pour faire plusieurs choix';
$lang['advanced_authorization'] = 'Autorisations avancées';
$lang['select_all'] = 'Tout sélectionner';
$lang['select_none'] = 'Tout désélectionner';
$lang['add_member'] = 'Ajouter un membre';
$lang['alert_member_already_auth'] = 'Le membre est déjà dans la liste';

//Calendar
$lang['january'] = 'Janvier';
$lang['february'] = 'Février';
$lang['march'] = 'Mars';
$lang['april'] = 'Avril';
$lang['may'] = 'Mai';
$lang['june'] = 'Juin';
$lang['july'] = 'Juillet';
$lang['august'] = 'Août';
$lang['september'] = 'Septembre';
$lang['october'] = 'Octobre';
$lang['november'] = 'Novembre';
$lang['december'] = 'Décembre';
$lang['monday'] = 'Lun';
$lang['tuesday'] = 'Mar';
$lang['wenesday'] = 'Mer';
$lang['thursday'] = 'Jeu';
$lang['friday'] = 'Ven';
$lang['saturday'] = 'Sam';
$lang['sunday']	= 'Dim';

//Commentaires
$lang['add_comment'] = 'Ajout commentaire';
$lang['edit_comment'] = 'Editer commentaire';

//Membres
$lang['member_area'] = 'Zone membre';
$lang['profile'] = 'Profil';
$lang['profile_edition'] = 'Edition du profil';
$lang['previous_password'] = 'Ancien mot de passe';
$lang['fill_only_if_modified'] = 'Remplir seulement en cas de modification';
$lang['new_password'] = 'Nouveau mot de passe';
$lang['confirm_password'] = 'Retapez votre mot de passe';
$lang['hide_mail'] = 'Cacher votre email';
$lang['hide_mail_who'] = 'Aux autres utilisateurs';
$lang['mail_track_topic'] = 'Etre averti par email lors d\'une réponse dans un sujet que vous suivez';
$lang['web_site'] = 'Site web';
$lang['localisation'] = 'Localisation';
$lang['job'] = 'Emploi';
$lang['hobbies'] = 'Loisirs';
$lang['sex'] = 'Sexe';
$lang['male'] = 'Homme';
$lang['female'] = 'Femme';
$lang['age'] = 'Age';
$lang['biography'] = 'Biographie';
$lang['years_old'] = 'Ans';
$lang['sign'] = 'Signature';
$lang['sign_where'] = 'Apparaît sous chacun de vos messages';
$lang['contact'] = 'Contact';
$lang['avatar'] = 'Avatar';
$lang['avatar_gestion'] = 'Gestion avatar';
$lang['current_avatar'] = 'Avatar actuel';
$lang['upload_avatar'] = 'Uploader avatar';
$lang['upload_avatar_where'] = 'Avatar directement hébergé sur le serveur';
$lang['avatar_link'] = 'Lien avatar';
$lang['avatar_link_where'] = 'Adresse directe de l\'avatar';
$lang['avatar_del'] = 'Supprimer l\'avatar courant';
$lang['no_avatar'] = 'Aucun avatar';
$lang['registered'] = 'Inscrit';
$lang['registered_s'] = 'Inscrits';
$lang['registered_on'] = 'Inscrit le';
$lang['last_connect'] = 'Dernière connexion';
$lang['private_message'] = 'Message(s) privé(s)';
$lang['nbr_message'] = 'Nombre de message(s)';
$lang['member_msg_display'] = 'Afficher les messages du membre';
$lang['member_msg'] = 'Messages du membre';
$lang['member_online'] = 'Membres en ligne';
$lang['no_member_online'] = 'Aucun membre connecté';
$lang['del_member'] = 'Suppression du compte <span class="text_small">(Définitif!)</span>';
$lang['choose_lang'] = 'Langue par défaut';
$lang['choose_theme'] = 'Thème par défaut';
$lang['choose_editor'] = 'Editeur texte par défaut';
$lang['theme_s'] = 'Thèmes';
$lang['select_group'] = 'Sélectionnez un groupe';
$lang['search_member'] = 'Chercher un membre';
$lang['date_of_birth'] = 'Date de naissance';
$lang['date_birth_format'] = 'JJ/MM/AAAA';
$lang['date_birth_parse'] = 'DD/MM/YYYY';
$lang['banned'] = 'Banni';
$lang['go_msg'] = 'Aller au message';
$lang['display'] = 'Afficher';
$lang['site_config_msg_mbr'] = 'Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.';
$lang['register_agreement'] = 'Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d\'être poli et courtois dans vos interventions.<br /><br />Merci, l\'équipe du site.';

//Register
$lang['pseudo_how'] = 'Longueur minimale du pseudo : 3 caractères';
$lang['password_how'] = 'Longueur minimale du password : 6 caractères';
$lang['confirm_register'] = 'Merci de vous être enregistré %s. Un mail vous sera envoyé pour confirmer votre inscription.';
$lang['register_terms'] = 'Règlement';
$lang['register_accept'] = 'J\'accepte';
$lang['register_have_to_accept'] = 'Vous devez accepter le réglement pour pouvoir vous inscrire!';
$lang['activ_mbr_mail'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter!';
$lang['activ_mbr_admin'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';
$lang['member_registered_to_approbate'] = 'Un nouveau membre s\'est inscrit. Son compte doit être approuvé avant de pouvoir être utilisé.';
$lang['activ_mbr_mail_success'] = 'Votre compte est désormais activé, vous pouvez vous connecter avec vos identifiants!';
$lang['activ_mbr_mail_error'] = 'Echec de l\'activation du compte';
$lang['weight_max'] = 'Poids maximum';
$lang['height_max'] = 'Hauteur maximale';
$lang['width_max'] = 'Largeur maximale';
$lang['verif_code'] = 'Code de vérification';
$lang['verif_code_explain'] = 'Recopier le code sur l\'image, attention aux majuscules';
$lang['require_verif_code'] = 'Veuillez saisir le code de vérification!';
$lang['timezone_choose'] = 'Choix du fuseau horaire';
$lang['timezone_choose_explain'] = 'Permet d\'ajuster l\'heure à votre localisation';
$lang['register_title_mail'] = 'Confirmation inscription sur %s';
$lang['register_ready'] = 'Vous pouvez désormais vous connecter à votre compte directement sur le site.';
$lang['register_valid_email_confirm'] = 'Vous devrez activer votre compte dans l\'email que vous reçevrez avant de pouvoir vous connecter.';
$lang['register_valid_email'] = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : %s';
$lang['register_valid_admin'] = 'Attention : Votre compte devra être activé par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$lang['register_mail'] = 'Cher %s,

Tout d\'abord, merci de vous être inscrit sur %s. Vous faites parti dès maintenant des membres du site.
En vous inscrivant sur %s, vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : %s
Mot de passe : %s

%s

%s';

//Mp
$lang['pm_box'] = 'Boîte de réception';
$lang['pm_track'] = 'Non lu par le destinataire';
$lang['recipient'] = 'Destinataire';
$lang['post_new_convers'] = 'Créer une nouvelle conversation';
$lang['read'] = 'Lu';
$lang['not_read'] = 'Non lu';
$lang['last_message'] = 'Dernier message';
$lang['mark_pm_as_read'] = 'Marquer tous les messages privés comme lus';
$lang['participants'] = 'Participant(s)';
$lang['no_pm'] = 'Aucun message';
$lang['quote_last_msg'] = 'Reprise du message précédent';

//Oublié
$lang['forget_pass'] = 'Mot de passe oublié';
$lang['forget_pass_send'] = 'Validez pour recevoir un nouveau mot de passe par mail, avec une clé d\'activation pour confirmer';
$lang['forget_mail_activ_pass'] = 'Activation du mot de passe';
$lang['forget_mail_pass'] = 'Cher %s

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur %s. Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour utiliser le nouveau mot de passe, vous avez besoin de le confirmer. Pour le faire, cliquez sur le lien fourni ci-dessous.

%s/member/forget.php?activate=true&u=%d&activ=%s

Après cela vous pourrez vous connecter avec le nouveau mot de passe suivant :

Mot de passe : %s

Vous pourrez bien sur changer vous-même ce mot de passe par la suite via votre profil membre. Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

%s';

//Gestion des fichiers
$lang['confim_del_file'] = 'Supprimer ce fichier?';
$lang['confirm_del_folder'] = 'Supprimer ce dossier, et tout son contenu?';
$lang['confirm_empty_folder'] = 'Vider tout le contenu de ce dossier?';
$lang['file_forbidden_chars'] = 'Le nom du fichier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$lang['folder_forbidden_chars'] = 'Le nom du dossier ne peut contenir aucun des caractères suivants : \\\ / . | ? < > \"';
$lang['files_management'] = 'Gestion des fichiers';
$lang['files_config'] = 'Configuration des fichiers';
$lang['file_add'] = 'Ajouter un fichier';
$lang['data'] = 'Total des données';
$lang['folders'] = 'Répertoires';
$lang['folders_up'] = 'Répertoire parent';
$lang['folder_new'] = 'Nouveau dossier';
$lang['empty_folder'] = 'Ce dossier est vide';
$lang['empty_member_folder'] = 'Vider ce dossier?';
$lang['del_folder'] = 'Supprimer ce dossier?';
$lang['folder_already_exist'] = 'Le dossier existe déjà!';
$lang['empty'] = 'Vider';
$lang['root'] = 'Racine';
$lang['files'] = 'Fichiers';
$lang['files_del_failed'] = 'Echec suppression des fichiers, veuillez le faire manuellement';
$lang['folder_size'] = 'Taille du dossier';
$lang['file_type'] = 'Fichier %s';
$lang['image_type'] = 'Image %s';
$lang['audio_type'] = 'Fichier audio %s';
$lang['zip_type'] = 'Archive %s';
$lang['adobe_pdf'] = 'Adobe Document';
$lang['document_type'] = 'Document %s';
$lang['moveto'] = 'Déplacer vers';
$lang['success_upload'] = 'Votre fichier a bien été enregistré !';
$lang['upload_folder_contains_folder'] = 'Vous souhaitez placer cette catégorie dans une de ses catégories filles ou dans elle-même, ce qui est impossible !';
$lang['popup_insert'] = 'Insérer le code dans le formulaire';

//gestion des catégories
$lang['cats_managment_could_not_be_moved'] = 'Une erreur est survenue, la catégorie n\'a pas pu être déplacée';
$lang['cats_managment_visibility_could_not_be_changed'] = 'Une erreur est survenue, la visibilité de la catégorie n\'a pas pu être changée';
$lang['cats_managment_no_category_existing'] = 'Aucune catégorie n\'existe';
$lang['cats_management_confirm_delete'] = 'Etes-vous sur de vouloir supprimer cette catégorie ?';
$lang['cats_management_hide_cat'] = 'Rendre invisible la catégorie';
$lang['cats_management_show_cat'] = 'Rendre visible la catégorie';

##########Panneau de modération##########
$lang['moderation_panel'] = 'Panneau de modération';
$lang['user_contact_pm'] = 'Contacter par message privé';
$lang['user_alternative_pm'] = 'Message privé envoyé au membre <span class="text_small">(Laisser vide pour aucun message privé)</span>. <br />Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.';

//Gestion des sanctions
$lang['punishment'] = 'Sanctions';
$lang['punishment_management'] = 'Gestion des sanctions';
$lang['user_punish_until'] = 'Sanction jusqu\'au';
$lang['no_punish'] = 'Aucun membre sanctionné';
$lang['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais plus poster sur le site entier (commentaires, etc...)';
$lang['weeks'] = 'semaines';
$lang['life'] = 'A vie';
$lang['readonly_user'] = 'Membre en lecture seule';
$lang['read_only_title'] = 'Sanction';
$lang['user_readonly_changed'] = 'Vous avez été mis en lecture seule par un membre de l\'équipe de modération, vous ne pourrez plus poster pendant %date%.


Ceci est un message semi-automatique.';

//Gestion des utilisateurs avertis
$lang['warning'] = 'Avertissements';
$lang['warning_management'] = 'Gestion des avertissements';
$lang['user_warning_level'] = 'Niveau d\'avertissement';
$lang['no_user_warning'] = 'Il n\'y a aucun utilisateur averti.';
$lang['user_warning_explain'] = 'Niveau d\'avertissement du membre. Vous pouvez le modifier, mais sachez qu\'à 100% le membre est banni.';
$lang['change_user_warning'] = 'Changer le niveau';
$lang['warning_title'] = 'Avertissement';
$lang['user_warning_level_changed'] = 'Vous avez été averti par un membre de l\'équipe de modération, votre niveau d\'avertissement est passé à %level%%. Attention à votre comportement, si vous atteignez 100% vous serez banni définitivement.


Ceci est un message semi-automatique.';
$lang['warning_user'] = 'Membre averti';

//Gestion des utilisateurs bannis.
$lang['bans'] = 'Bannissements';
$lang['ban_management'] = 'Gestion des bannissements';
$lang['user_ban_until'] = 'Banni jusqu\'au';
$lang['ban_user'] = 'Bannir';
$lang['no_ban'] = 'Il n\'y a aucun utilisateur banni.';
$lang['user_ban_delay'] = 'Durée du bannissement';
$lang['ban_title_mail'] = 'Banni';
$lang['ban_mail'] = 'Bonjour,

Vous avez été banni sur : %s !
S\'il s\'agit d\'une erreur veuillez contacter l\'administrateur du site.


%s';


//Panneau de contribution
$lang['contribution_panel'] = 'Panneau de contribution';
$lang['contribution'] = 'Contribution';
$lang['contribution_status_unread'] = 'Non traitée';
$lang['contribution_status_being_processed'] = 'En cours';
$lang['contribution_status_processed'] = 'Traitée';
$lang['contribution_entitled'] = 'Intitulé';
$lang['contribution_description'] = 'Description';
$lang['contribution_edition'] = 'Edition d\'une contribution';
$lang['contribution_status'] = 'Statut';
$lang['contributor'] = 'Contributeur';
$lang['contribution_creation_date'] = 'Date de création';
$lang['contribution_fixer'] = 'Responsable';
$lang['contribution_fixing_date'] = 'Date de clôture';
$lang['contribution_module'] = 'Module';
$lang['process_contribution'] = 'Traiter la contribution';
$lang['confirm_delete_contribution'] = 'Etes-vous sûr de vouloir supprimer cette contribution ?';
$lang['no_contribution'] = 'Aucune contribution à afficher';
$lang['contribution_list'] = 'Liste des contributions';
$lang['contribute'] = 'Contribuer';
$lang['contribute_in_modules_explain'] = 'Les modules suivants permettent aux utilisateurs de contribuer. Cliquez sur un module pour vous rendre dans son interface de contribution.';
$lang['contribute_in_module_name'] = 'Contribuer dans le module %s';
$lang['no_module_to_contribute'] = 'Aucun module dans lequel vous pouvez contribuer n\'est installé.';

//Barre de chargement.
$lang['query_loading'] = 'Chargement de la requête au serveur';
$lang['query_sent'] = 'Requête envoyée au serveur, attente d\'une réponse';
$lang['query_processing'] = 'Traitement de la requête en cours';
$lang['query_success'] = 'Traitement terminé';
$lang['query_failure'] = 'Traitement échoué';

//Footer
$lang['powered_by'] = 'Boosté par';
$lang['phpboost_right'] = '';
$lang['sql_req'] = 'Requêtes';
$lang['achieved'] = 'Exécuté en';

//Flux
$lang['syndication'] = 'Syndication';
$lang['rss'] = 'RSS';
$lang['atom'] = 'ATOM';

$lang['enabled'] = 'Activé';
$lang['disabled'] = 'Désactivé';

//Dictionnaire pour le captcha.
$lang['_code_dictionnary'] = array('image', 'php', 'requete', 'azerty', 'exit', 'genre', 'design', 'web', 'inter', 'cache', 'media', 'cms', 'cesar', 'watt', 'site', 'mail', 'email', 'spam', 'index', 'membre',
'date', 'jour', 'mois', 'nom', 'noter', 'objet', 'options', 'titre', 'valide', 'liste', 'citer', 'fermer', 'minute', 'heure', 'semaine', 'groupe', 'taille', 'modules', 'pendant', 'style', 'divers', 'autre', 'erreur',
'editer', 'banni', 'niveau', 'dossier', 'fichier', 'racine', 'vider', 'archive', 'boite');

$lang['csrf_attack'] = '<p>Vous avez été potentiellement victime d\'une attaque de type <acronym title="Cross-Site Request Forgery">CSRF</acronym> que PHPBoost a bloquée.</p>
<p>Pour plus d\'informations, visitez <a href="http://fr.wikipedia.org/wiki/Cross-Site_Request_Forgeries" title="Attaques CSRF" class="wikipedia_link">wikipedia</a></p>';

// DEPRECATED
global $LANG;
$LANG = array_merge($LANG, $lang);
?>
