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
$LANG['date_format_tiny'] = 'd/m';
$LANG['date_format_short'] = 'd/m/y';
$LANG['date_format'] = 'd/m/y \à H\hi';
$LANG['date_format_long'] = 'd/m/y \à H\hi\m\i\ns\s';
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
$LANG['title_forget'] = 'Mot de passe oublié';

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
$LANG['forget_pass'] = 'Mot de passe oublié';

$LANG['require'] = 'Les champs marqués * sont obligatoires!';
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
$LANG['alert_delete_msg'] = 'Supprimer ce message ?';
$LANG['alert_delete_file'] = 'Supprimer ce fichier ?';

//bbcode
$LANG['bb_smileys'] = 'Smileys';
$LANG['bb_bold'] = 'Texte en gras : [b]texte[/b]';
$LANG['bb_italic'] = 'Texte en italique : [i]texte[/i]';
$LANG['bb_underline'] = 'Texte souligné : [u]texte[/u]';
$LANG['bb_strike'] = 'Texte barré : [s]texte[/s]';
$LANG['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$LANG['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$LANG['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$LANG['bb_color'] = 'Couleur du texte : [color=X]texte de couleur X[/color]';
$LANG['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$LANG['bb_left'] = 'Positionner à gauche : [align=left]objet à gauche[/align]';
$LANG['bb_center'] = 'Centrer : [align=center]objet centré[/align]';
$LANG['bb_right'] = 'Positionner à droite : [align=right]objet à droite[/align]';
$LANG['bb_justify'] = 'Justifier : [align=justify]objet justifié[/align]';
$LANG['bb_code'] = 'Insérer du code [code]texte[/code]';
$LANG['bb_math'] = 'Insérer du code mathématique [math]texte[/math]';
$LANG['bb_swf'] = 'Insérer du flash [swf=largeur,hauteur]adresse animation[/swf]';
$LANG['bb_small'] = 'Réduire le champ texte';
$LANG['bb_large'] = 'Agrandir le champ texte';
$LANG['bb_title'] = 'Titre [title=x]texte[/title]';
$LANG['bb_html'] = 'Code html [html]code[/html]';
$LANG['bb_container'] = 'Conteneur';
$LANG['bb_block'] = 'Bloc';
$LANG['bb_fieldset'] = 'Bloc champs';
$LANG['bb_style'] = 'Style [style=x]texte[/style]';
$LANG['bb_hide'] = 'Cache le texte, affiche lors du clic [hide]texte[/hide]';
$LANG['bb_float_left'] = 'Objet flottant à gauche [float=left]texte[/float]';
$LANG['bb_float_right'] = 'Objet flottant à droite [float=right]texte[/float]';
$LANG['bb_list'] = 'Liste [list][*]texte1[*]texte2[/list]';
$LANG['bb_table'] = 'Tableau [table][row][col]texte[/col][col]texte2[/col][/row][/table]';
$LANG['bb_indent'] = 'Indentation [indent]texte[/indent]';
$LANG['bb_sup'] = 'Exposant [sup]texte[/sup]';
$LANG['bb_sub'] = 'Indice [sub]texte[/sub]';
$LANG['bb_anchor'] = 'Ancre vers un endroit de la page [anchor=x]texte[/anchor]';
$LANG['bb_sound'] = 'Son [sound]adresse du son[/sound]';
$LANG['bb_movie'] = 'Vidéo [movie=largeur,hauteur]adresse du fichier[/movie]';
$LANG['bb_help'] = 'Aide BBcode';
$LANG['bb_upload'] = 'Attacher un fichier';
$LANG['bb_url_prompt'] = 'Adresse du lien ?';
$LANG['bb_text'] = 'Texte';
$LANG['bb_script'] = 'Script';
$LANG['bb_web'] = 'Web';
$LANG['bb_prog'] = 'Programmation';
$LANG['lines'] = 'Nombre de lignes';
$LANG['cols'] = 'Nombre de colonnes';
$LANG['head_table'] = 'Entête';
$LANG['head_add'] = 'Ajouter l\'entête';
$LANG['insert_table'] = 'Insérer le tableau';
$LANG['ordered_list'] = 'Liste ordonnée';
$LANG['insert_list'] = 'Insérer la liste';
$LANG['forbidden_tags'] = 'Types de formatage interdits';
$LANG['phpboost_languages'] = 'PHPBoost';
$LANG['wikipedia_subdomain'] = 'fr'; //Sous domaine sur wikipédia (ex fr pour fr.wikipedia.org)
$LANG['code_too_long_error'] = 'Le code que vous voulez colorer est trop long et consommerait trop de ressources pour être interprété. Merci de réduire sa taille ou de l\'éclater en plusieurs morceaux.';
$LANG['feed_tag_error'] = 'Le flux du module <em>:module</em> que vous souhaitez afficher n\'a pas pu être trouvé ou les options que vous avez rentrées ne sont pas correctes.';
$LANG['format_bold'] = 'Gras';
$LANG['format_italic'] = 'Italique';
$LANG['format_underline'] = 'Souligné';
$LANG['format_strike'] = 'Barré';
$LANG['format_title'] = 'Titre';
$LANG['format_style'] = 'Style';
$LANG['format_url'] = 'Lien';
$LANG['format_img'] = 'Image';
$LANG['format_quote'] = 'Citation';
$LANG['format_hide'] = 'Caché';
$LANG['format_list'] = 'Liste';
$LANG['format_color'] = 'Couleur';
$LANG['format_bgcolor'] = 'Couleur de fond';
$LANG['format_font'] = 'Police';
$LANG['format_size'] = 'Taille';
$LANG['format_align'] = 'Alignement';
$LANG['format_float'] = 'Elément flottant';
$LANG['format_sup'] = 'Exposant';
$LANG['format_sub'] = 'Indice';
$LANG['format_indent'] = 'Indentation';
$LANG['format_pre'] = 'Préformaté';
$LANG['format_table'] = 'Tableau';
$LANG['format_flash'] = 'Flash';
$LANG['format_movie'] = 'Vidéo';
$LANG['format_sound'] = 'Son';
$LANG['format_code'] = 'Code';
$LANG['format_math'] = 'Mathématiques';
$LANG['format_anchor'] = 'Ancre';
$LANG['format_acronym'] = 'Acronyme';
$LANG['format_block'] = 'Bloc';
$LANG['format_fieldset'] = 'Zone de champs';
$LANG['format_mail'] = 'Mail';
$LANG['format_line'] = 'Ligne horizontale';
$LANG['format_wikipedia'] = 'Lien Wikipédia';
$LANG['format_html'] = 'Code HTML';

//Impression
$LANG['printable_version'] = 'Version imprimable';

//Connexion
$LANG['private_messaging'] = 'Messagerie privée';
$LANG['my_private_profile'] = 'Mon profil';

//Maintain
$LANG['maintain'] = 'Le site est actuellement en maintenance merci de votre patience, seul(s) le(s) administrateur(s) du site peuvent y accèder.';
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
$LANG['desc'] = 'Decroissant';
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

//Calendar
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

//Commentaires
$LANG['add_comment'] = 'Ajout commentaire';
$LANG['edit_comment'] = 'Editer commentaire';

//Membres
$LANG['member_area'] = 'Zone membre';
$LANG['profile'] = 'Profil';
$LANG['profile_edition'] = 'Edition du profil';
$LANG['previous_password'] = 'Ancien mot de passe';
$LANG['fill_only_if_modified'] = 'Remplir seulement en cas de modification';
$LANG['new_password'] = 'Nouveau mot de passe';
$LANG['confirm_password'] = 'Retapez votre mot de passe';
$LANG['hide_mail'] = 'Cacher votre email';
$LANG['hide_mail_who'] = 'Aux autres utilisateurs';
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
$LANG['choose_editor'] = 'Editeur texte par défaut';
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
$LANG['register_agreement'] = 'Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d\'être poli et courtois dans vos interventions.<br /><br />Merci, l\'équipe du site.';

//Register
$LANG['pseudo_how'] = 'Longueur minimale du pseudo : 3 caractères';
$LANG['password_how'] = 'Longueur minimale du password : 6 caractères';
$LANG['confirm_register'] = 'Merci de vous être enregistré %s. Un mail vous sera envoyé pour confirmer votre inscription.';
$LANG['register_terms'] = 'Règlement';
$LANG['register_accept'] = 'J\'accepte';
$LANG['register_have_to_accept'] = 'Vous devez accepter le réglement pour pouvoir vous inscrire!';
$LANG['activ_mbr_mail'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter!';
$LANG['activ_mbr_admin'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';
$LANG['member_registered_to_approbate'] = 'Un nouveau membre s\'est inscrit. Son compte doit être approuvé avant de pouvoir être utilisé.';
$LANG['activ_mbr_mail_success'] = 'Votre compte est désormais activé, vous pouvez vous connecter avec vos identifiants!';
$LANG['activ_mbr_mail_error'] = 'Echec de l\'activation du compte';
$LANG['weight_max'] = 'Poids maximum';
$LANG['height_max'] = 'Hauteur maximale';
$LANG['width_max'] = 'Largeur maximale';
$LANG['verif_code'] = 'Code de vérification';
$LANG['verif_code_explain'] = 'Recopier le code sur l\'image, attention aux majuscules';
$LANG['require_verif_code'] = 'Veuillez saisir le code de vérification!';
$LANG['timezone_choose'] = 'Choix du fuseau horaire';
$LANG['timezone_choose_explain'] = 'Permet d\'ajuster l\'heure à votre localisation';
$LANG['register_title_mail'] = 'Confirmation inscription sur %s';
$LANG['register_ready'] = 'Vous pouvez désormais vous connecter à votre compte directement sur le site.';
$LANG['register_valid_email_confirm'] = 'Vous devrez activer votre compte dans l\'email que vous reçevrez avant de pouvoir vous connecter.';
$LANG['register_valid_email'] = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : %s';
$LANG['register_valid_admin'] = 'Attention : Votre compte devra être activé par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$LANG['register_mail'] = 'Cher %s,

Tout d\'abord, merci de vous être inscrit sur %s. Vous faites parti dès maintenant des membres du site.
En vous inscrivant sur %s, vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : %s
Mot de passe : %s

%s

%s';

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

//Oublié
$LANG['forget_pass'] = 'Mot de passe oublié';
$LANG['forget_pass_send'] = 'Validez pour recevoir un nouveau mot de passe par mail, avec une clé d\'activation pour confirmer';
$LANG['forget_mail_activ_pass'] = 'Activation du mot de passe';
$LANG['forget_mail_pass'] = 'Cher %s

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur %s. Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour utiliser le nouveau mot de passe, vous avez besoin de le confirmer. Pour le faire, cliquez sur le lien fourni ci-dessous.

%s/member/forget.php?activate=true&u=%d&activ=%s

Après cela vous pourrez vous connecter avec le nouveau mot de passe suivant :

Mot de passe : %s

Vous pourrez bien sur changer vous-même ce mot de passe par la suite via votre profil membre. Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

%s';

//Gestion des fichiers
$LANG['confim_del_file'] = 'Supprimer ce fichier?';
$LANG['confirm_del_folder'] = 'Supprimer ce dossier, et tout son contenu?';
$LANG['confirm_empty_folder'] = 'Vider tout le contenu de ce dossier?';
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
$LANG['empty_member_folder'] = 'Vider ce dossier?';
$LANG['del_folder'] = 'Supprimer ce dossier?';
$LANG['folder_already_exist'] = 'Le dossier existe déjà!';
$LANG['empty'] = 'Vider';
$LANG['root'] = 'Racine';
$LANG['files'] = 'Fichiers';
$LANG['files_del_failed'] = 'Echec suppression des fichiers, veuillez le faire manuellement';
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
$LANG['cats_management_hide_cat'] = 'Rendre invisible la catégorie';
$LANG['cats_management_show_cat'] = 'Rendre visible la catégorie';

##########Panneau de modération##########
$LANG['moderation_panel'] = 'Panneau de modération';
$LANG['user_contact_pm'] = 'Contacter par message privé';
$LANG['user_alternative_pm'] = 'Message privé envoyé au membre <span class="text_small">(Laisser vide pour aucun message privé)</span>. <br />Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.';

//Gestion des sanctions
$LANG['punishment'] = 'Sanctions';
$LANG['punishment_management'] = 'Gestion des sanctions';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['no_punish'] = 'Aucun membre sanctionné';
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
$LANG['no_module_to_contribute'] = 'Aucun module dans lequel vous pouvez contribuer n\'est installé.';

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

$LANG['csrf_attack'] = '<p>Vous avez été potentiellement victime d\'une attaque de type <acronym title="Cross-Site Request Forgery">CSRF</acronym> que PHPBoost a bloquée.</p>
<p>Pour plus d\'informations, visitez <a href="http://fr.wikipedia.org/wiki/Cross-Site_Request_Forgeries" title="Attaques CSRF" class="wikipedia_link">wikipedia</a></p>';
?>
