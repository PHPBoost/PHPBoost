<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : December 17, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 #						French						#
 ####################################################

// --- Titre
$lang['module_title'] = 'Bac à sable';

// --- Welcome

$lang['title.form_builder'] = 'Formulaires';
$lang['title.table_builder'] = 'Tableaux';
$lang['title.icons'] = 'Icônes';
$lang['title.css'] = 'CSS';
$lang['title.menu'] = 'Cssmenu';
$lang['title.mail_sender'] = 'Envoi de mail';
$lang['title.string_template'] = 'Génération de template';

$lang['welcome_message'] = 'Bienvenue dans le module Bac à sable.<br /><br />
Vous pouvez ici tester plusieurs parties du framework PHPBoost :<br />
<ul>
<li>Le rendu des différents champs utilisables avec le <a href="' . SandboxUrlBuilder::form()->absolute() . '">constructeur de formulaires</a></li>
<li>La <a href="' . SandboxUrlBuilder::table()->absolute() . '">génération de tableaux dynamiques</a></li>
<li>La <a href="' . SandboxUrlBuilder::icons()->absolute() . '">liste des icônes</a> de la librairie Font Awesome utilisées dans les modules</li>
<li>Le rendu des principales <a href="' . SandboxUrlBuilder::css()->absolute() . '">classes CSS</a></li>
<li>Le rendu des menus cssmenu <a href="' . SandboxUrlBuilder::menu()->absolute() . '">menus cssmenu</a></li>
<li>L\'<a href="' . SandboxUrlBuilder::mail()->absolute() . '">envoi de mails</a></li>
<li>La <a href="' . SandboxUrlBuilder::template()->absolute() . '">génération de template</a> avec ou sans cache</li>
</ul>
<br />
';

// --- Form

$lang['form.title'] = 'Formulaire';
$lang['form.desc'] = 'Ceci est une description';
$lang['form.input.text'] = 'Champ texte';
$lang['form.input.text.desc'] = 'Contraintes: lettres, chiffres et tiret bas';
$lang['form.input.text.lorem'] = 'Lorem ipsum';
$lang['form.input.text.disabled'] = 'Champ désactivé';
$lang['form.input.text.disabled.desc'] = 'Désactivé';
$lang['form.input.url'] = 'Site web';
$lang['form.input.url.desc'] = 'Url valide';
$lang['form.input.url.placeholder'] = 'https://www.phpboost.com';
$lang['form.input.email'] = 'Email';
$lang['form.input.email.desc'] = 'Email valide';
$lang['form.input.email.placeholder'] = 'lorem@phpboost.com';
$lang['form.input.email.multiple'] = 'Email multiple';
$lang['form.input.email.multiple.desc'] = 'Emails valides, séparés par une virgule';
$lang['form.input.email.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['form.input.phone'] = 'Numéro de téléphone';
$lang['form.input.phone.desc'] = 'Numéro de téléphone valide';
$lang['form.input.phone.placeholder'] = '0123456789';
$lang['form.input.text.required'] = 'Champ requis';
$lang['form.input.text.required.filled'] = 'Champ requis rempli';
$lang['form.input.text.required.empty'] = 'Champ requis vide';
$lang['form.input.number'] = 'Nombre';
$lang['form.input.number.desc'] = 'intervalle: de 10 à 100';
$lang['form.input.number.placeholder'] = '20';
$lang['form.input.number.decimal'] = 'Nombre décimal';
$lang['form.input.number.decimal.desc'] = 'Utiliser la virgule';
$lang['form.input.number.decimal.placeholder'] = '5.5';
$lang['form.input.length'] = 'Slider';
$lang['form.input.length.desc'] = 'Faites glisser';
$lang['form.input.length.placeholder'] = '4';
$lang['form.input.password'] = 'Mot de passe';
$lang['form.input.password.desc'] = ' caractères minimum';
$lang['form.input.password.placeholder'] = 'aaaaaa';
$lang['form.input.password.confirm'] = 'Confirmation du mot de passe';
$lang['form.input.multiline.medium'] = 'Champ texte multi lignes moyen';
$lang['form.input.multiline'] = 'Champ texte multi lignes';
$lang['form.input.multiline.desc'] = 'Description';
$lang['form.input.multiline.lorem'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['form.input.rich.text'] = 'Champ texte avec éditeur';
$lang['form.input.rich.text.placeholder'] = 'Créer un site <strong>facilement</strong>';
$lang['form.input.checkbox'] = 'Case à cocher';
$lang['form.input.multiple.checkbox'] = 'Case à cocher multiple';
$lang['form.input.radio'] = 'Boutons radio';
$lang['form.input.select'] = 'Liste déroulante';
$lang['form.input.multiple.select'] = 'Liste déroulante multiple';
$lang['form.input.choice.1'] = 'Choix 1';
$lang['form.input.choice.2'] = 'Choix 2';
$lang['form.input.choice.3'] = 'Choix 3';
$lang['form.input.choice.4'] = 'Choix 4';
$lang['form.input.choice.5'] = 'Choix 5';
$lang['form.input.choice.6'] = 'Choix 6';
$lang['form.input.choice.7'] = 'Choix 7';
$lang['form.input.choice.group.1'] = 'Groupe 1';
$lang['form.input.choice.group.2'] = 'Groupe 2';
$lang['form.input.timezone'] = 'TimeZone';
$lang['form.input.user.completion'] = 'Auto complétion utilisateurs';
$lang['form.send.button'] = 'Envoyer';

$lang['form.title.2'] = 'Formulaire 2';
$lang['form.input.hidden'] = 'Champ caché';
$lang['form.free.html'] = 'Champ libre';
$lang['form.date'] = 'Date';
$lang['form.date.hm'] = 'Date/heure/minutes';
$lang['form.color'] = 'Couleur';
$lang['form.search'] = 'Recherche';
$lang['form.file.picker'] = 'Fichier';
$lang['form.multiple.file.picker'] = 'Plusieurs fichiers';
$lang['form.file.upload'] = 'Lien vers un fichier';

$lang['form.authorization'] = 'Autorisation';
$lang['form.authorization.1'] = 'Action 1';
$lang['form.authorization.1.desc'] = 'Autorisations pour l\'action 1';
$lang['form.authorization.2'] = 'Action 2';

$lang['form.vertical.desc'] = 'Formulaire vertical';
$lang['form.horizontal.desc'] = 'Formulaire horizontal';

$lang['form.preview'] = 'Prévisualiser';
$lang['form.button'] = 'Bouton';

// --- Cssmenu

$lang['css.menu.site.title'] = 'Menus cssmenu';
$lang['css.menu.site.slogan'] = 'Bac à sable - le design des cssmenu';
$lang['css.menu.breadcrumb.index'] = 'Accueil';
$lang['css.menu.breadcrumb.sandbox'] = 'Bac à sable';
$lang['css.menu.breadcrumb.cssmenu'] = 'cssmenu';
$lang['css.menu.h2'] = 'Les différents menus cssmenu';
$lang['css.menu.element'] = 'Item du menu';
$lang['css.menu.sub.element'] = 'Sous menu';
$lang['css.menu.horizontal.sub.header'] = 'Menu de sous-entête';
$lang['css.menu.sub.admin'] = 'Administration';
$lang['css.menu.horizontal.top'] = 'Menu horizontal Header';
$lang['css.menu.horizontal.scrolling'] = 'Menu horizontal déroulant';
$lang['css.menu.vertical.scrolling'] = 'Menu vertical scroll';
$lang['css.menu.vertical.img'] = 'Menu avec images';
$lang['css.menu.vertical.scrolling.left'] = 'Menu vert scroll à gauche';
$lang['css.menu.vertical.scrolling.right'] = 'Menu vert scroll à droite';
$lang['css.menu.actionslinks.sandbox'] = 'Bac à sable';
$lang['css.menu.actionslinks.index'] = 'Accueil';
$lang['css.menu.actionslinks.form'] = 'Formulaires';
$lang['css.menu.actionslinks.css'] = 'CSS';
$lang['css.menu.actionslinks.menu'] = 'Cssmenu';
$lang['css.menu.actionslinks.icons'] = 'Icônes';
$lang['css.menu.actionslinks.table'] = 'Tableaux';
$lang['css.menu.actionslinks.template'] = 'Génération de templates';
$lang['css.menu.actionslinks.mail'] = 'Envoi de mail';
$lang['css.menu.actionslinks'] = 'Menu options des modules';
$lang['css.menu.group'] = 'Menu groupes';
$lang['css.menu.static'] = 'Menu statique';
$lang['css.menu.static.footer'] = 'Menu statique pied de page';

// --- lorem ipsum pour Cssmenu

$lang['css.menu.content'] = 'Cette page a un design spécifique de manière à afficher tous les types de menus en fonction des emplacements potentiels susceptibles d\'être utilisés';
$lang['lorem.ipsum'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis condimentum eros. Vestibulum fermentum eleifend consectetur.
Nulla efficitur molestie vulputate. Sed finibus dolor in est faucibus egestas. Nullam odio elit, rutrum ut tempor in, elementum ut nisi.
Nunc placerat convallis dolor, vitae semper justo placerat vel. Nulla porta quis nisl vitae commodo. Aliquam et tortor viverra, porttitor nulla nec,
pretium ligula. Sed eleifend consequat tincidunt.';

// --- Icônes

$lang['css.icon.sample'] = 'Quelques exemples';
$lang['css.icon.social'] = 'Réseaux sociaux';
$lang['css.icon.screen'] = 'Ecrans';
$lang['css.icon.icon'] = 'Icône';
$lang['css.icon.name'] = 'Nom';
$lang['css.icon.code'] = 'Code';
$lang['css.icon.list'] = 'La liste complète des icônes et de leur code associé : ';

$lang['css.icon.howto'] = 'Comment ça marche ?';
$lang['css.icon.howto.explain'] = 'Font-Awesome est une icon-font, une police de caractère qui permet d\'afficher des icônes simplement';
$lang['css.icon.howto.update'] = 'Elle est implémentée depuis la version 4.1 de PHPBoost. Chaque mise à jour de Font-Awesome est implémentée dans la mise à jour de PHPBoost qui suit.';
$lang['css.icon.howto.html'] = 'En html';
$lang['css.icon.howto.html.class'] = 'On utilise le nom de l\'icône en tant que classe : ';
$lang['css.icon.howto.html.class.result.i'] = 'Nous donnera l\'icône "edit" suivi du texte : ';
$lang['css.icon.howto.html.class.result.a'] = 'Nous donnera le lien précédé de l\'icône "globe" : ';
$lang['css.icon.howto.html.class.result.all'] = 'Il en est de même pour tout type de balise html.';
$lang['css.icon.howto.css'] = 'En CSS';
$lang['css.icon.howto.css.class'] = 'Il faut définir votre classe, puis le code de votre icône en tant que contenu du ::before ou du ::after de la classe :';
$lang['css.icon.howto.css.css.code'] = 'Code CSS :';
$lang['css.icon.howto.css.html.code'] = 'Code HTML :';
$lang['css.icon.howto.variants'] = 'Les variantes';
$lang['css.icon.howto.variants.explain'] = 'Font-Awesome propose une panoplie de variantes telles que la taille de l\'icône, l\'animation, la rotation, l\'empilement et bien d\'autres.';
$lang['css.icon.howto.variants.list'] = 'Leur fonctionnement est expliqué ici (anglais) : ';
$lang['css.icon.howto.variants.spinner'] = 'Nous donnera l\'icone "spinner", défini en pulsation et faisant 2 fois sa taille initiale : ';

// --- CSS

//Typogrphie
$lang['css.typography'] = 'Typographie';
$lang['css.titles'] = 'Titres';
$lang['css.title'] = 'Titre';
$lang['css.specific_titles'] = 'Titres spécifiques (BBCode)';

$lang['css.styles'] = 'Styles';
$lang['css.text_bold'] = 'Texte en gras';
$lang['css.text_italic'] = 'Texte en italique';
$lang['css.text_underline'] = 'Texte souligné';
$lang['css.text_strike'] = 'Texte barré';

$lang['css.sizes'] = 'Tailles';
$lang['css.link'] = 'Lien';
$lang['css.link_smaller'] = 'Lien en très petit';
$lang['css.link_small'] = 'Lien en petit';
$lang['css.link_big'] = 'Lien en grand';
$lang['css.link_bigger'] = 'Lien en plus grand';
$lang['css.link_biggest'] = 'Lien très grand';

$lang['css.rank_color'] = 'Couleur selon rang de l\'utilisateur';
$lang['css.admin'] = 'Administrateur';
$lang['css.modo'] = 'Modérateur';
$lang['css.member'] = 'Membre';

//Divers
$lang['css.miscellaneous'] = 'Divers';
$lang['css.main_actions_icons'] = 'Icônes des principales actions';
$lang['css.rss_feed'] = 'Flux RSS';
$lang['css.edit'] = 'Editer';
$lang['css.delete'] = 'Supprimer';
$lang['css.delete.confirm'] = 'Supprimer (contrôle automatique JS avec confirmation de suppression)';
$lang['css.delete.confirm.custom'] = 'Supprimer (contrôle automatique JS avec confirmation personnalisée)';
$lang['css.delete.custom_message'] = 'Message personnalisé';

$lang['css.lists'] = 'Listes';
$lang['css.element'] = 'Elément';

$lang['css.progress_bar'] = 'Barre de progression';
$lang['css.progress_bar.util_infos'] = 'Informations utiles';
$lang['css.progress_bar.votes'] = '3 votes';

$lang['css.explorer'] = 'Explorateur';
$lang['css.root'] = 'Racine';
$lang['css.tree'] = 'Arborescence';
$lang['css.cat'] = 'Catégorie';
$lang['css.file'] = 'Fichier';

$lang['css.options'] = 'Options';
$lang['css.options.sort_by'] = 'Trier selon';
$lang['css.options.sort_by.alphabetical'] = 'Alphabétique';
$lang['css.options.sort_by.size'] = 'Taille';
$lang['css.options.sort_by.date'] = 'Date';
$lang['css.options.sort_by.popularity'] = 'Popularité';
$lang['css.options.sort_by.note'] = 'Note';

$lang['css.modules_menus.direction.up'] = 'Croissant';
$lang['css.modules_menus.direction.down'] = 'Décroissant';

$lang['css.button'] = 'Boutons';

$lang['css.sortable'] = 'Sortable Drag & Drop';
$lang['css.static.sortable'] = 'Sortable positionné';
$lang['css.moved.sortable'] = 'Sortable en mouvement';
$lang['css.dropzone'] = 'déplacer ici';

//Blockquote
$lang['css.quote'] = 'Citation';
$lang['css.code'] = 'Code';
$lang['css.code.php'] = 'Code PHP';
$lang['css.hidden'] = 'Texte caché';

//Pagination
$lang['css.pagination'] = 'Pagination';

//Tables
$lang['css.table'] = 'Tableau';
$lang['css.table_description'] = 'Description du tableau';
$lang['css.table.name'] = 'Nom';
$lang['css.table.description'] = 'Description';
$lang['css.table.author'] = 'Auteur';
$lang['css.table.test'] = 'Test';
$lang['css.specific.table'] = 'Tableau spécifique (bbcode)';
$lang['css.table.header'] = 'Entête';

//Messages
$lang['css.messages_and_coms'] = 'Messages et commentaires';
$lang['css.messages.login'] = 'admin';
$lang['css.messages.level'] = 'Administrateur';
$lang['css.messages.date'] = '05/09/2013 à 15h37';
$lang['css.messages.content'] = 'Ceci est un commentaire';
$lang['css.error_messages'] = 'Messages d\'erreurs';

$lang['css.message_success'] = 'Ceci est un message de succès';
$lang['css.message_notice'] = 'Ceci est un message d\'information';
$lang['css.message_warning'] = 'Ceci est un message d\'avertissement';
$lang['css.message_error'] = 'Ceci est un message d\'erreur';
$lang['css.message_question'] = 'Ceci est une question, est-ce que l\'affichage sur deux lignes fonctionne correctement ?';

//Pages
$lang['css.page'] = 'Page';
$lang['css.page.title'] = 'Titre de la page';
$lang['css.page.subtitle'] = 'Sous-Titre';
$lang['css.page.subsubtitle'] = 'Sous-Sous-Titre';
$lang['css.blocks'] = 'Blocs';
$lang['css.block.title'] = 'Titre du bloc';
$lang['css.blocks.medium'] = 'Blocs (2 sur une ligne)';
$lang['css.blocks.small'] = 'Blocs (3 sur une ligne)';

// --- Mail

$lang['mail.title'] = 'Email';
$lang['mail.sender_mail'] = 'Email de l\'expéditeur';
$lang['mail.sender_name'] = 'Nom de l\'expéditeur';
$lang['mail.recipient_mail'] = 'Email du destinataire';
$lang['mail.recipient_name'] = 'Nom du destinataire';
$lang['mail.subject'] = 'Objet de l\'email';
$lang['mail.content'] = 'Contenu';
$lang['mail.smtp_config'] = 'Configuration SMTP';
$lang['mail.smtp_config.explain'] = 'Cochez la case si vous voulez utiliser une connexion SMTP directe pour envoyer l\'email.';
$lang['mail.use_smtp'] = 'Utiliser SMTP';
$lang['mail.smtp_configuration'] = 'Configuration des paramètres SMTP pour l\'envoi';
$lang['mail.smtp.host'] = 'Nom d\'hôte';
$lang['mail.smtp.port'] = 'Port';
$lang['mail.smtp.login'] = 'Identifiant';
$lang['mail.smtp.password'] = 'Mot de passe';
$lang['mail.smtp.secure_protocol'] = 'Protocole de sécurisation';
$lang['mail.smtp.secure_protocol.none'] = 'Aucun';
$lang['mail.smtp.secure_protocol.tls'] = 'TLS';
$lang['mail.smtp.secure_protocol.ssl'] = 'SSL';
$lang['mail.success'] = 'L\'email a été envoyé';

//Template
$lang['string_template.result'] = 'Temps de génération du template sans cache : :non_cached_time secondes<br />Temps de génération du template avec cache : :cached_time secondes<br />Longueur de la chaîne : :string_length caractères.';

//Wiki
$lang['wiki.module'] = 'Module Wiki';
$lang['wiki.table.of.contents'] = 'Table des matières';
$lang['wiki.contents'] = 'Contenu du wiki';

?>
