<?php
/*##################################################
 *                                main.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis
 *   email                : mickaelhemri@gmail.com
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
#                                                           French                                                                #
####################################################

$LANG['xml_lang'] = 'fr';
$LANG['date_format_tiny'] = 'd/m';
$LANG['date_format_short'] = 'd/m/y';
$LANG['date_format'] = 'd/m/y \� H\hi';
$LANG['date_format_long'] = 'd/m/y \� H\hi\m\i\ns\s';

//Unit�s
$LANG['unit_megabytes'] = 'Mo';
$LANG['unit_kilobytes'] = 'Ko';
$LANG['unit_bytes'] = 'Octets';
$LANG['unit_pixels'] = 'px';
$LANG['unit_seconds'] = 'Secondes';
$LANG['unit_seconds_short'] = 's';
	
//Erreurs
$LANG['error'] = 'Erreur';
$LANG['error_fatal'] = '<strong>Erreur fatale :</strong> %s<br/><br/><br/><strong>Ligne %s : %s</strong>';
$LANG['error_warning'] = '<strong>Attention :</strong> %s %s %s';
$LANG['error_notice'] = '<strong>Remarque :</strong> %s %s %s';
$LANG['error_success'] = '<strong>Succ�s :</strong> %s %s %s';
$LANG['error_unknow'] = '<strong>Erreur :</strong> Cause inconnue %s %s %s';

//Titres divers
$LANG['title_pm'] = 'Messages priv�s';
$LANG['title_error'] = 'Erreur';
$LANG['title_com'] = 'Commentaires';
$LANG['title_register'] = 'S\'enregistrer';
$LANG['title_forget'] = 'Mot de passe oubli�';

//Form
$LANG['submit'] = 'Envoyer';
$LANG['update'] = 'Modifier';
$LANG['reset'] = 'D�faut';
$LANG['erase'] = 'Effacer';
$LANG['preview'] = 'Pr�visualiser';
$LANG['search'] = 'Recherche';
$LANG['connect'] = 'Se connecter';
$LANG['disconnect'] = 'Se d�connecter';
$LANG['autoconnect'] = 'Connexion auto';
$LANG['password'] = 'Mot de passe';
$LANG['respond'] = 'R�pondre';
$LANG['go'] = 'Go';

$LANG['pseudo'] = 'Pseudo';
$LANG['message'] = 'Message';
$LANG['message_s'] = 'Messages';
$LANG['forget_pass'] = 'Mot de passe oubli�';

$LANG['require'] = 'Les champs marqu�s * sont obligatoires!';

//Alertes formulaires
$LANG['require_title'] = 'Veuillez entrer un titre !';
$LANG['require_text'] = 'Veuillez entrer un texte!';
$LANG['require_pseudo'] = 'Veuillez entrer un pseudo!';
$LANG['require_mail'] = 'Veuillez entrer un mail!';
$LANG['require_subcat'] = 'Veuillez s�lectionner une sous-cat�gorie !';
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
$LANG['bb_underline'] = 'Texte soulign� : [u]texte[/u]';
$LANG['bb_strike'] = 'Texte barr� : [s]texte[/s]';
$LANG['bb_link'] = 'Ajouter un lien : [url]lien[/url], ou [url=lien]nom du lien[/url]';
$LANG['bb_picture'] = 'Ajouter une image : [img]url image[/img]';
$LANG['bb_size'] = 'Taille du texte (X entre 0 - 49) : [size=X]texte de taille X[/size]';
$LANG['bb_color'] = 'Couleur du texte : [color=X]texte de couleur X[/color]';
$LANG['bb_quote'] = 'Faire une citation [quote=pseudo]texte[/quote]';
$LANG['bb_left'] = 'Positionner � gauche : [align=left]objet � gauche[/align]';
$LANG['bb_center'] = 'Centrer : [align=center]objet centr�[/align]';
$LANG['bb_right'] = 'Positionner � droite : [align=right]objet � droite[/align]';
$LANG['bb_code'] = 'Ins�rer du code [code]texte[/code]';
$LANG['bb_math'] = 'Ins�rer du code math�matique [math]texte[/math]';
$LANG['bb_swf'] = 'Ins�rer du flash [swf=largeur,hauteur]adresse animation[/swf]';
$LANG['bb_small'] = 'R�duire le champ texte';
$LANG['bb_large'] = 'Agrandir le champ texte';
$LANG['bb_title'] = 'Titre [title=x]texte[/title]';
$LANG['bb_subtitle'] = 'Sous-titre [stitle=x]texte[/stitle]';
$LANG['bb_style'] = 'Style [style=x]texte[/style]';
$LANG['bb_hide'] = 'Cache le texte, affiche lors du clic [hide]texte[/hide]';
$LANG['bb_float_left'] = 'Objet flottant � gauche [float=left]texte[/float]';
$LANG['bb_float_right'] = 'Objet flottant � droite [float=right]texte[/float]';
$LANG['bb_list'] = 'Liste [list][*]texte1[*]texte2[/list]';
$LANG['bb_table'] = 'Tableau [table][row][col]texte[/col][col]texte2[/col][/row][/table]';
$LANG['bb_indent'] = 'Indentation [indent]texte[/indent]';
$LANG['bb_sup'] = 'Exposant [sup]texte[/sup]';
$LANG['bb_sub'] = 'Indice [sub]texte[/sub]';
$LANG['bb_anchor'] = 'Ancre vers un endroit de la page [anchor=x]texte[/anchor]';
$LANG['bb_sound'] = 'Son [sound]adresse du son[/sound]';
$LANG['bb_movie'] = 'Vid�o [movie=largeur,hauteur]adresse du fichier[/movie]';
$LANG['bb_help'] = 'Aide BBcode';
$LANG['bb_upload'] = 'Attacher un fichier';
$LANG['bb_url_prompt'] = 'Adresse du lien?';
$LANG['bb_text'] = 'Texte';
$LANG['bb_script'] = 'Script';
$LANG['bb_web'] = 'Web';
$LANG['bb_prog'] = 'Programmation';
$LANG['lines'] = 'Nombre de lignes';
$LANG['cols'] = 'Nombre de colonnes';
$LANG['head_table'] = 'Ent�te';
$LANG['head_add'] = 'Ajouter l\'ent�te';
$LANG['insert_table'] = 'Ins�rer le tableau';
$LANG['ordered_list'] = 'Liste ordonn�e';
$LANG['insert_list'] = 'Ins�rer la liste';
$LANG['forbidden_tags'] = 'Balises BBcode interdites :';

//Connexion
$LANG['connect_private_message'] = 'Messagerie priv�e';
$LANG['connect_private_profil'] = 'Mon profil';

//Maintain
$LANG['maintain'] = 'Le site est actuellement en maintenance merci de votre patience, seul(s) le(s) administrateur(s) du site peuvent y acc�der.';
$LANG['maintain_delay'] = 'D�lai estim� avant r�ouverture du site :';
$LANG['title_maintain'] = 'Site en maintenance';
$LANG['loading'] = 'Chargement';

//Commun
$LANG['user'] = 'Utilisateur'; 
$LANG['user_s'] = 'Utilisateurs'; 
$LANG['guest'] = 'Visiteur';
$LANG['guest_s'] = 'Visiteurs'; 
$LANG['member'] = 'Membre';
$LANG['member_s'] = 'Membres';
$LANG['modo'] = 'Mod�rateur';
$LANG['modo_s'] = 'Mod�rateurs';   
$LANG['admin'] = 'Administrateur';
$LANG['admin_s'] = 'Administrateurs';
$LANG['index'] = 'Accueil';
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
$LANG['image'] = 'Image';
$LANG['note'] = 'Note';
$LANG['notes'] = 'Notes';
$LANG['previous'] = 'Pr�c�dent';
$LANG['next'] = 'Suivant';
$LANG['mail'] = 'Mail';
$LANG['objet'] = 'Objet';
$LANG['contents'] = 'Contenu';
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
$LANG['liste'] = 'Liste';
$LANG['welcome'] = 'Bienvenue';
$LANG['atually'] = 'Actuellement';
$LANG['place'] = 'Lieu';
$LANG['quote'] = 'Citer';
$LANG['quotation'] = 'Citation';
$LANG['hide'] = 'Cach�';
$LANG['default'] = 'Normal';
$LANG['type'] = 'Type';
$LANG['status'] = 'Statut';
$LANG['url'] = 'Url';
$LANG['replies'] = 'R�ponses';
$LANG['back'] = 'Retour';
$LANG['close'] = 'Fermer';
$LANG['smiley'] = 'Smiley';
$LANG['all_smiley'] = 'Tous les smileys';
$LANG['total'] = 'Total';
$LANG['average'] = 'Moyenne';
$LANG['page'] = 'Page';
$LANG['illimited'] = 'Illimit�';
$LANG['seconds'] = 'secondes';
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'heure';
$LANG['hours'] = 'heures';
$LANG['day'] = 'jour';
$LANG['days'] = 'jours';
$LANG['week'] = 'semaine';
$LANG['unspecified'] = 'Non sp�cifi�';
$LANG['admin_panel'] = 'Panel administration';
$LANG['modo_panel'] = 'Panel mod�ration';
$LANG['group'] = 'Groupe';
$LANG['groups'] = 'Groupes';
$LANG['size'] = 'Taille';
$LANG['theme'] = 'Th�me';
$LANG['online'] = 'En ligne';
$LANG['modules'] = 'Modules';
$LANG['no_result'] = 'Aucun r�sulat';
$LANG['during'] = 'Pendant';
$LANG['until'] = 'Jusqu\'au';
$LANG['lock'] = 'Verrouiller';
$LANG['unlock'] = 'D�verrouiller';
$LANG['upload'] = 'Uploader';
$LANG['subtitle'] = 'Sous-titre';
$LANG['style'] = 'Style';
$LANG['question'] = 'Question';
$LANG['notice'] = 'Remarque';
$LANG['warning'] = 'Attention';
$LANG['success'] = 'Succ�s';
$LANG['vote'] = 'Vote';
$LANG['votes'] = 'Votes';
$LANG['already_vote'] = 'Vous avez d�j� vot�';
$LANG['miscellaneous'] = 'Divers';
$LANG['unknow'] = 'Inconnu';
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['orderby'] = 'Ordonner par';
$LANG['direction'] = 'Direction';
$LANG['other'] = 'Autre';
$LANG['aprob'] = 'Approuver';
$LANG['unaprob'] = 'D�sapprouver';
$LANG['final'] = 'D�finitif';
$LANG['pm'] = 'Mp';
$LANG['code'] = 'Code';
$LANG['com'] = 'Commentaire';
$LANG['com_s'] = 'Commentaires';
$LANG['post_com'] = 'Poster commentaire';
$LANG['com_locked'] = 'Les commentaires sont verrouill�s pour cet �l�ment';
$LANG['add_msg'] = 'Ajout message';
$LANG['update_msg'] = 'Modifier le message';
$LANG['category'] = 'Cat�gorie';
$LANG['categories'] = 'Cat�gories';
$LANG['refresh'] = 'Rafraichir';

//Dates.
$LANG['on'] = 'Le';
$LANG['at'] = '�';
$LANG['and'] = 'et';
$LANG['by'] = 'Par';

//Gestion formulaires autorisation
$LANG['explain_select_multiple'] = 'Maintenez ctrl puis cliquez dans la liste pour faire plusieurs choix';
$LANG['advanced_authorization'] = 'Autorisations avanc�es';
$LANG['select_all'] = 'Tout s�lectionner';
$LANG['select_none'] = 'Tout d�s�lectionner';
$LANG['add_member'] = 'Ajouter un membre';
$LANG['alert_member_already_auth'] = 'Le membre est d�j� dans la liste';

//Calendar
$LANG['january'] = 'Janvier';
$LANG['february'] = 'F�vrier';
$LANG['march'] = 'Mars';
$LANG['april'] = 'Avril';
$LANG['may'] = 'Mai';
$LANG['june'] = 'Juin';
$LANG['july'] = 'Juillet';
$LANG['august'] = 'Ao�t';
$LANG['september'] = 'Septembre';
$LANG['october'] = 'Octobre';
$LANG['november'] = 'Novembre';
$LANG['december'] = 'D�cembre';
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
$LANG['profil'] = 'Profil';
$LANG['profil_edit'] = 'Edition du profil';
$LANG['previous_pass'] = 'Ancien mot de passe';
$LANG['edit_if_modif'] = 'Remplir seulement en cas de modification';
$LANG['new_pass'] = 'Nouveau mot de passe';
$LANG['confirm_pass'] = 'Retapez votre mot de passe';
$LANG['hide_mail'] = 'Cacher votre email';
$LANG['hide_mail_who'] = 'Aux autres utilisateurs';
$LANG['mail_track_topic'] = 'Etre averti par email lors d\'une r�ponse dans un sujet que vous suivez';
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
$LANG['sign_where'] = 'Appara�t sous chacun de vos messages';
$LANG['contact'] = 'Contact';
$LANG['avatar'] = 'Avatar';
$LANG['avatar_gestion'] = 'Gestion avatar';
$LANG['current_avatar'] = 'Avatar actuel';
$LANG['upload_avatar'] = 'Uploader avatar';
$LANG['upload_avatar_where'] = 'Avatar directement h�berg� sur le serveur';
$LANG['avatar_link'] = 'Lien avatar';
$LANG['avatar_link_where'] = 'Adresse directe de l\'avatar';
$LANG['avatar_del'] = 'Supprimer l\'avatar courant';
$LANG['no_avatar'] = 'Aucun avatar';
$LANG['registered'] = 'Inscrit';
$LANG['registered_s'] = 'Inscrits';
$LANG['registered_on'] = 'Inscrit le';
$LANG['last_connect'] = 'Derni�re connexion';
$LANG['private_message'] = 'Message(s) priv�(s)';
$LANG['nbr_message'] = 'Nombre de message(s)';
$LANG['member_msg_display'] = 'Afficher les messages du membre';
$LANG['member_msg'] = 'Messages du membre';
$LANG['member_online'] = 'Membres en ligne';
$LANG['no_member_online'] = 'Aucun membre connect�';
$LANG['del_member'] = 'Suppression du compte <span class="text_small">(D�finitif!)</span>';
$LANG['choose_lang'] = 'Langue par d�faut';
$LANG['choose_theme'] = 'Th�me par d�faut';
$LANG['choose_editor'] = 'Editeur texte par d�faut';
$LANG['theme_s'] = 'Th�mes';
$LANG['select_group'] = 'S�lectionnez un groupe';
$LANG['search_member'] = 'Chercher un membre';
$LANG['date_of_birth'] = 'Date de naissance';
$LANG['date_birth_format'] = 'JJ/MM/AAAA';
$LANG['date_birth_parse'] = 'DD/MM/YYYY';
$LANG['banned'] = 'Banni';
$LANG['go_msg'] = 'Aller au message';

//Register
$LANG['pseudo_how'] = 'Longueur minimale du pseudo : 3 caract�res';
$LANG['password_how'] = 'Longueur minimale du password : 6 caract�res';
$LANG['confirm_register'] = 'Merci de vous �tre enregistr� %s. Un mail vous sera envoy� pour confirmer votre inscription.';
$LANG['register_terms'] = 'R�glement';
$LANG['register_accept'] = 'J\'accepte';
$LANG['activ_mbr_mail'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoy� avant de pouvoir vous connecter!';
$LANG['activ_mbr_admin'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';
$LANG['activ_mbr_mail_success'] = 'Votre compte est d�sormais activ�, vous pouvez vous connecter avec vos identifiants!';
$LANG['activ_mbr_mail_error'] = 'Echec de l\'activation du compte';
$LANG['weight_max'] = 'Poids maximum';
$LANG['height_max'] = 'Hauteur maximale';
$LANG['width_max'] = 'Largeur maximale';
$LANG['verif_code'] = 'Code de v�rification';
$LANG['verif_code_explain'] = 'Recopier le code sur l\'image, attention aux majuscules';
$LANG['require_verif_code'] = 'Veuillez saisir le code de v�rification!';
$LANG['timezone_choose'] = 'Choix du fuseau horaire';
$LANG['timezone_choose_explain'] = 'Permet d\'ajuster l\'heure � votre localisation';
$LANG['register_title_mail'] = 'Confirmation inscription sur %s';
$LANG['register_ready'] = 'Vous pouvez d�sormais vous connecter � votre compte directement sur le site.';
$LANG['register_valid_email_confirm'] = 'Vous devrez activer votre compte dans l\'email que vous re�evrez avant de pouvoir vous connecter.';
$LANG['register_valid_email'] = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : %s';
$LANG['register_valid_admin'] = 'Attention : Votre compte devra �tre activ� par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$LANG['register_mail'] = 'Cher %s,

Tout d\'abord, merci de vous �tre inscrit sur %s. Vous faites partie d�s maintenant des membres du site. 
En vous inscrivant sur %s, vous obtenez un acc�s � la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, �tre reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le th�me par d�faut, �diter votre profil, acc�der � des cat�gories r�serv�es aux membres... Bref vous acc�dez � toute la communaut� du site.                                              

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe. 

Nous vous rappelons vos identifiants.

Identifiant : %s
Password : %s 

%s 

' . $CONFIG['sign'];

//Mp
$LANG['pm_box'] = 'Boite de r�ception';
$LANG['pm_track'] = 'Non lu par le destinataire';
$LANG['recipient'] = 'Destinataire';
$LANG['post_new_convers'] = 'Cr�er une nouvelle conversation';
$LANG['read'] = 'Lu';
$LANG['not_read'] = 'Non lu';
$LANG['last_message'] = 'Dernier message';
$LANG['mark_pm_as_read'] = 'Marquer tous les messages priv�s comme lus';
$LANG['participants'] = 'Participant(s)';
$LANG['no_pm'] = 'Aucun message';

//Oubli�
$LANG['forget_pass'] = 'Mot de passe oubli�';
$LANG['forget_pass_send'] = 'Validez pour recevoir un nouveau mot de passe par mail, avec une cl� d\'activation pour confirmer';
$LANG['forget_mail_activ_pass'] = 'Activation du mot de passe';
$LANG['forget_mail_pass'] = 'Cher %s

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui pr�tend l\'�tre) avez demand� � ce qu\'un nouveau mot de passe vous soit envoy� pour votre compte sur %s. Si vous n\'avez pas demand� de changement de mot de passe, veuillez l\'ignorer. Si vous continuez � le re�evoir, veuillez contacter l\'administrateur du site.

Pour utiliser le nouveau mot de passe, vous avez besoin de le confirmer. Pour le faire, cliquez sur le lien fourni ci-dessous.

%s/member/forget.php?activate=true&u=%d&activ=%s

Apr�s cela vous pourrez vous connecter avec le nouveau mot de passe suivant :

Mot de passe : %s

Vous pourrez bien sur changer vous-m�me ce mot de passe par la suite via votre profil membre. Si vous rencontrez des difficult�s, veuillez contacter l\'administrateur du site.

' . $CONFIG['sign'];

//Gestion des fichiers
$LANG['confim_del_file'] = 'Supprimer ce fichier?';
$LANG['confirm_del_folder'] = 'Supprimer ce dossier, et tout son contenu?';
$LANG['confirm_empty_folder'] = 'Vider tout le contenu de ce dossier?';
$LANG['file_forbidden_chars'] = 'Le nom du fichier ne peut contenir aucun des caract�res suivants : \\\ / . | ? < > \"';
$LANG['folder_forbidden_chars'] = 'Le nom du dossier ne peut contenir aucun des caract�res suivants : \\\ / . | ? < > \"';
$LANG['files_management'] = 'Gestion des fichiers';
$LANG['files_config'] = 'Configuration des fichiers';
$LANG['file_add'] = 'Ajouter un fichier';
$LANG['data'] = 'Total des donn�es';
$LANG['folders'] = 'R�pertoires';
$LANG['folders_up'] = 'R�pertoire parent';
$LANG['folder_new'] = 'Nouveau dossier';
$LANG['empty_folder'] = 'Ce dossier est vide';
$LANG['empty_member_folder'] = 'Vider ce dossier?';
$LANG['del_folder'] = 'Supprimer ce dossier?';
$LANG['folder_already_exist'] = 'Le dossier existe d�j�!';
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
$LANG['moveto'] = 'D�placer vers';
$LANG['success_upload'] = 'Votre fichier a bien �t� enregistr� !';
$LANG['upload_folder_contains_folder'] = 'Vous souhaitez placer cette cat�gorie dans une de ses cat�gories filles ou dans elle-m�me, ce qui est impossible !';

//gestion des cat�gories
$LANG['cats_managment_could_not_be_moved'] = 'Une erreur est survenue, la cat�gorie n\'a pas pu �tre d�plac�e';
$LANG['cats_managment_no_category_existing'] = 'Aucune cat�gorie n\'existe';
$LANG['cats_management_confirm_delete'] = 'Etes-vous sur de vouloir supprimer cette cat�gorie ?';

##########Panneau de mod�ration##########
$LANG['moderation_panel'] = 'Panneau de mod�ration';
$LANG['user_contact_pm'] = 'Contacter par message priv�';
$LANG['user_alternative_pm'] = 'Message priv� envoy� au membre <span class="text_small">(Laisser vide pour aucun message priv�)</span>. <br />Le membre averti ne pourra pas r�pondre � ce message, et ne conna�tra pas l\'expediteur.';

//Gestion des sanctions
$LANG['punishment'] = 'Sanctions';
$LANG['punishment_management'] = 'Gestion des sanctions';
$LANG['user_punish_until'] = 'Sanction jusqu\'au';
$LANG['no_punish'] = 'Aucun membre sanctionn�';
$LANG['user_readonly_explain'] = 'Membre en lecture seule, celui-ci peut lire mais plus poster sur le site entier (commentaires, etc...)';
$LANG['weeks'] = 'semaines';
$LANG['life'] = 'A vie';
$LANG['readonly_user'] = 'Membre en lecture seule';
$LANG['read_only_title'] = 'Sanction';
$LANG['user_readonly_changed'] = 'Vous avez �t� mis en lecture seule par un membre de l\'�quipe de mod�ration, vous ne pourrez plus poster pendant %date%.


Ceci est un message semi-automatique.';

//Gestion des utilisateurs avertis
$LANG['warning'] = 'Avertissements';
$LANG['warning_management'] = 'Gestion des avertissements';
$LANG['user_warning_level'] = 'Niveau d\'avertissement';
$LANG['no_user_warning'] = 'Il n\'y a aucun utilisateur averti.';
$LANG['user_warning_explain'] = 'Niveau d\'avertissement du membre. Vous pouvez le modifier, mais sachez qu\'� 100% le membre est banni.';
$LANG['change_user_warning'] = 'Changer le niveau';
$LANG['warning_title'] = 'Avertissement';
$LANG['user_warning_level_changed'] = 'Vous avez �t� averti par un membre de l\'�quipe de mod�ration, votre niveau d\'avertissement est pass� � %level%%. Attention � votre comportement, si vous atteignez 100% vous serez banni d�finitivement.


Ceci est un message semi-automatique.';
$LANG['warning_user'] = 'Membre averti';

//Gestion des utilisateurs bannis.
$LANG['bans'] = 'Bannissements';
$LANG['ban_management'] = 'Gestion des bannissements';
$LANG['user_ban_until'] = 'Banni jusqu\'au';
$LANG['ban_user'] = 'Bannir';
$LANG['no_ban'] = 'Il n\'y a aucun utilisateur banni.';
$LANG['user_ban_delay'] = 'Dur�e du bannissement';
$LANG['ban_title_mail'] = 'Banni';
$LANG['ban_mail'] = 'Bonjour,

Vous avez �t� banni sur : %s !
S\'il s\'agit d\'une erreur veuillez contacter l\'administrateur du site.


%s';

//Barre de chargement.
$LANG['query_loading'] = 'Chargement de la requ�te au serveur';
$LANG['query_sent'] = 'Requ�te envoy�e au serveur, attente d\'une r�ponse';
$LANG['query_processing'] = 'Traitement de la requ�te en cours';
$LANG['query_success'] = 'Traitement termin�';
$LANG['query_failure'] = 'Traitement �chou�';

//Footer
$LANG['powered_by'] = 'Boost� par';
$LANG['phpboost_right'] = '&copy; 2005-2008';
$LANG['sql_req'] = 'Requ�tes';
$LANG['achieved'] = 'Ex�cut� en';
?>