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

// --- Titre du module

$lang['module.title'] = 'Bac à sable';

// --- Page d'accueil

$lang['title.form.builder'] = 'Formulaires';
$lang['title.css'] = 'CSS';
$lang['title.bbcode'] = 'BBCode';
$lang['title.menu'] = 'Menus Cssmenu';
$lang['title.icons'] = 'Icônes Font-Awesome';
$lang['title.table.builder'] = 'Tableaux';
$lang['title.mail.sender'] = 'Envoi de mail';
$lang['title.string.template'] = 'Génération de template';

$lang['welcome.message'] = '<p>Bienvenue dans le module Bac à sable.</p>
<br />
<p>Vous pouvez ici tester plusieurs parties du framework PHPBoost :</p>
<ul class="sandbox-home-list">
<li><i class="fa fa-fw fa-asterisk"></i> Le rendu des différents champs utilisables avec le <a href="' . SandboxUrlBuilder::form()->absolute() . '" title="formulaires">constructeur de formulaires</a></li>
<li><i class="fa fa-fw fa-css3"></i> Le rendu des principales <a href="' . SandboxUrlBuilder::css()->absolute() . '" title="Classes CSS">classes CSS</a></li>
<li><i class="fa fa-fw fa-file-code-o"></i> Le rendu des styles spécifiques du <a href="' . SandboxUrlBuilder::bbcode()->absolute() . '" title="Styles BBCode">BBCode</a></li>
<li><i class="fa fa-fw fa-flag-o"></i> Un tutoriel sur l\'utilisation des icônes de la librairie <a href="' . SandboxUrlBuilder::icons()->absolute() . '" title="Icônes Font-Awesome">Font Awesome</a></li>
<li><i class="fa fa-fw fa-list"></i> Le rendu des <a href="' . SandboxUrlBuilder::menu()->absolute() . '" title="Menus de navigation">menus de navigation cssmenu</a></li>
<li><i class="fa fa-fw fa-table"></i> La génération de <a href="' . SandboxUrlBuilder::table()->absolute() . '" title="Tableaux">tableaux dynamiques</a></li>
<li><i class="fa fa-fw fa-at"></i> L\'<a href="' . SandboxUrlBuilder::mail()->absolute() . '" title="Emails">envoi d\'emails</a></li>
<li><i class="fa fa-fw fa-code"></i> La <a href="' . SandboxUrlBuilder::template()->absolute() . '" title="Génération de Thème">génération de template</a> avec ou sans cache</li>
</ul>
<br />
';

// --- Framework lorem

$lang['framework.lorem.mini'] = 'Etiam hendrerit, tortor et faucibus dapibus, eros orci porta eros, in facilisis ipsum ipsum at nisl';
$lang['framework.lorem.medium'] = 'Fusce vitae consequat nisl. Fusce vestibulum porta ipsum ac consectetur. Duis finibus mauris eu feugiat congue.
Aenean aliquam accumsan ipsum, ac dapibus dui ultricies non. In hac habitasse platea dictumst. Aenean mi nibh, varius vel lacus at, tincidunt luctus eros.
In hac habitasse platea dictumst. Vestibulum luctus lorem nisl, et hendrerit lectus dapibus ut. Phasellus sit amet nisl tortor.
Aenean pulvinar tellus nulla, sit amet mattis nisl semper eu. Phasellus efficitur nisi a laoreet dignissim. Aliquam erat volutpat.';
$lang['framework.lorem.large'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit odio urna, blandit pharetra elit
scelerisque tempor. Nulla dapibus felis orci, at consectetur orci auctor eget. Donec eros lectus, mollis eget auctor vel, convallis ac mauris.
Cras imperdiet, erat ac semper volutpat, libero orci varius mi, et ullamcorper quam urna vitae augue. Maecenas maximus vitae diam vel porta.
Pellentesque dignissim dolor eu neque aliquet viverra. Maecenas tincidunt, mi non gravida tincidunt, lectus elit gravida massa,
sed viverra tortor diam pretium metus. In hac habitasse platea dictumst. Ut velit turpis, sollicitudin non risus et, pretium efficitur leo.
Integer elementum faucibus finibus. Nullam et felis sit amet felis blandit iaculis. Vestibulum massa arcu, finibus id enim ac, commodo aliquam metus.
Vestibulum feugiat urna nunc, et eleifend velit posuere ac. Vestibulum sagittis tempus nunc, sit amet dignissim ipsum sollicitudin eget.';


// --- Formulaires

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

// --- CSS

// Pages
$lang['css.page.title'] = 'Titre de la page';
$lang['css.more'] = 'Auteur | Date de création | Nombre de commentaires ...';
$lang['css.picture'] = 'Image';

$lang['css.options'] = 'Options';
$lang['css.options.sort_by'] = 'Trier selon';
$lang['css.options.sort_by.alphabetical'] = 'Alphabétique';
$lang['css.options.sort_by.size'] = 'Taille';
$lang['css.options.sort_by.date'] = 'Date';
$lang['css.options.sort_by.popularity'] = 'Vues';
$lang['css.options.sort_by.note'] = 'Note';
$lang['css.modules_menus.direction.up'] = 'Croissant';
$lang['css.modules_menus.direction.down'] = 'Décroissant';


// Typogrphie
$lang['css.title.typography'] = 'Typographie';
$lang['css.titles'] = 'Titres';
$lang['css.title'] = 'Titre';

$lang['css.styles'] = 'Styles';
$lang['css.text_bold'] = 'Texte en gras';
$lang['css.text_italic'] = 'Texte en italique';
$lang['css.text_underline'] = 'Texte souligné';
$lang['css.text_strike'] = 'Texte barré';

$lang['css.title.sizes'] = 'Tailles de texte';
$lang['css.text'] = 'Texte';
$lang['css.text.smaller'] = 'Texte en très petit';
$lang['css.text.small'] = 'Texte en petit';
$lang['css.text.big'] = 'Texte en grand';
$lang['css.text.bigger'] = 'Texte en plus grand';
$lang['css.text.biggest'] = 'Texte très grand';
$lang['css.link'] = 'Lien hypertext';

$lang['css.rank_color'] = 'Couleur selon rang de l\'utilisateur';
$lang['css.admin'] = 'Administrateur';
$lang['css.modo'] = 'Modérateur';
$lang['css.member'] = 'Membre';

// Divers
$lang['css.miscellaneous'] = 'Divers';
$lang['css.main_actions_icons'] = 'Icônes des principales actions';
$lang['css.rss_feed'] = 'Flux RSS';
$lang['css.edit'] = 'Éditer';
$lang['css.delete'] = 'Supprimer';
$lang['css.delete.confirm'] = 'Supprimer (contrôle automatique JS avec confirmation de suppression)';
$lang['css.delete.confirm.custom'] = 'Supprimer (contrôle automatique JS avec confirmation personnalisée)';
$lang['css.delete.custom_message'] = 'Message personnalisé';

$lang['css.lists'] = 'Listes';
$lang['css.element'] = 'Élément';

$lang['css.progress_bar'] = 'Barre de progression';
$lang['css.progress_bar.util_infos'] = 'Informations utiles';
$lang['css.progress_bar.votes'] = '3 votes';

$lang['css.explorer'] = 'Explorateur';
$lang['css.root'] = 'Racine';
$lang['css.tree'] = 'Arborescence';
$lang['css.cat'] = 'Catégorie';
$lang['css.file'] = 'Fichier';

$lang['css.button'] = 'Boutons';
$lang['css.button.other'] = 'Autres boutons';

$lang['css.sortable'] = 'Sortable Drag & Drop';
$lang['css.static.sortable'] = 'Sortable positionné';
$lang['css.moved.sortable'] = 'Sortable en mouvement';
$lang['css.dropzone'] = 'déplacer ici';
$lang['css.sortable.move'] = 'Déplacer';

//Blockquote
$lang['css.quote'] = 'Citation';
$lang['css.code'] = 'Code';
$lang['css.code.php'] = 'Code PHP';
$lang['css.hidden'] = 'Texte caché';

//Pagination
$lang['css.pagination'] = 'Pagination';

//Tables
$lang['css.table'] = 'Tableau';
$lang['css.table.description'] = 'Description du tableau';
$lang['css.table.name'] = 'Nom';
$lang['css.table.description'] = 'Description';
$lang['css.table.author'] = 'Auteur';
$lang['css.table.test'] = 'Test';
$lang['css.table.header'] = 'Entête';
$lang['css.table.sort.up'] = 'Trier par ordre croissant';
$lang['css.table.sort.down'] = 'Trier par ordre décroissant';

//Messages
$lang['css.messages.and.coms'] = 'Messages et commentaires';
$lang['css.messages.login'] = 'admin';
$lang['css.messages.level'] = 'Administrateur';
$lang['css.messages.date'] = '05/09/2013 à 15h37';
$lang['css.messages.content'] = 'Ceci est un commentaire';

$lang['css.alert.messages'] = 'Messages d\'alerte';
$lang['css.message.success'] = 'Ceci est un message de succès';
$lang['css.message.notice'] = 'Ceci est un message d\'information';
$lang['css.message.warning'] = 'Ceci est un message d\'avertissement';
$lang['css.message.error'] = 'Ceci est un message d\'erreur';
$lang['css.message.question'] = 'Ceci est une question:<br /> est-ce que l\'affichage sur deux lignes fonctionne correctement ?';

//Blocs
$lang['css.blocks'] = 'Blocs';
$lang['css.block.title'] = 'Titre de l\'article';
$lang['css.blocks.large'] = '1 par ligne (.block)';
$lang['css.blocks.medium'] = '2 par ligne (.medium-block)';
$lang['css.blocks.small'] = '3 par ligne (.small-block)';

// --- Styles spécifiques du BBCode

$lang['bbcode.title.typography'] = 'Typographie';

$lang['bbcode.titles'] = 'Titres';
$lang['bbcode.title_1'] = 'Titre 1';
$lang['bbcode.title_2'] = 'Titre 2';
$lang['bbcode.title_3'] = 'Titre 3';
$lang['bbcode.title_4'] = 'Titre 4';

$lang['bbcode.title.lists'] = 'Listes';
$lang['bbcode.element'] = 'Elément';
$lang['bbcode.element_1'] = 'Elément 1';
$lang['bbcode.element_2'] = 'Elément 2';
$lang['bbcode.element_3'] = 'Elément 3';

$lang['bbcode.title.blocks'] = 'Blocs';
$lang['bbcode.paragraph'] = 'Paragraphe';
$lang['bbcode.block'] = 'Bloc';
$lang['bbcode.fieldset'] = 'Bloc champ';
$lang['bbcode.legend'] = 'Légende du bloc champ';

$lang['bbcode.title.media'] = 'Media';
$lang['bbcode.image'] = 'Image';
$lang['bbcode.lightbox'] = 'Lightbox';
$lang['bbcode.youtube'] = 'Youtube';
$lang['bbcode.movie'] = 'Movie';
$lang['bbcode.flash'] = 'SWF';
$lang['bbcode.flash.alert'] = 'Votre navigateur ne supporte pas Adobe Flash';
$lang['bbcode.audio'] = 'Sound';

$lang['bbcode.title.code'] = 'Blocs code';
$lang['bbcode.quote'] = 'Citation';
$lang['bbcode.hidden'] = 'Caché';
$lang['bbcode.code.php'] = 'Code';

$lang['bbcode.title.table'] = 'Tableaux';
$lang['bbcode.table.header'] = 'Entête';
$lang['bbcode.table.name'] = 'Nom';
$lang['bbcode.table.description'] = 'Description';

// --- Wiki
$lang['wiki.conditions'] = 'Vous devez porter le module wiki dans votre thème pour que cette visualisation soit active.';
$lang['wiki.module'] = 'Module Wiki';
$lang['wiki.table.of.contents'] = 'Table des matières';
$lang['wiki.contents'] = 'Contenu du wiki';

// --- Icônes Font-Awesome

$lang['iconfa.sample'] = 'Quelques exemples';
$lang['iconfa.social'] = 'Réseaux sociaux';
$lang['iconfa.screen'] = 'Ecrans';
$lang['iconfa.icon'] = 'Icône';
$lang['iconfa.name'] = 'Nom';
$lang['iconfa.code'] = 'Code';
$lang['iconfa.list'] = 'La liste complète des icônes et de leur code associé : ';

$lang['iconfa.howto'] = 'Comment ça marche ?';
$lang['iconfa.howto.explain'] = 'Font-Awesome est une icon-font, une police de caractère qui permet d\'afficher des icônes simplement';
$lang['iconfa.howto.update'] = 'Elle est implémentée depuis la version 4.1 de PHPBoost. Chaque mise à jour de Font-Awesome est implémentée dans la mise à jour suivante de PHPBoost.';
$lang['iconfa.howto.html'] = 'En html';
$lang['iconfa.howto.html.class'] = 'On utilise le nom de l\'icône en tant que classe : ';
$lang['iconfa.howto.html.class.result.i'] = 'Nous donnera l\'icône "edit" suivi du texte : ';
$lang['iconfa.howto.html.class.result.a'] = 'Nous donnera le lien précédé de l\'icône "globe" : ';
$lang['iconfa.howto.html.class.result.all'] = 'Il en est de même pour tout type de balise html.';
$lang['iconfa.howto.css'] = 'En CSS';
$lang['iconfa.howto.css.class'] = 'Il faut définir votre classe, puis le code de votre icône en tant que contenu du ::before ou du ::after de la classe :';
$lang['iconfa.howto.css.css.code'] = 'Code CSS :';
$lang['iconfa.howto.css.html.code'] = 'Code HTML :';
$lang['iconfa.howto.bbcode'] = 'En BBCode';
$lang['iconfa.howto.bbcode.some.icons'] = 'Les icônes les plus utilisées dans PHPBoost sont déjà implémentées dans le menu bbcode. Vous pouvez les sélectionner en cliquant sur l\'icône du menu:';
$lang['iconfa.howto.bbcode.tag'] = 'Si l\'icône que vous désirez utiliser n\'apparait pas dans la liste, vous pouvez utiliser la balise [fa] comme suit:';
$lang['iconfa.howto.bbcode.icon.name'] = '[fa]nom de l\'icône[/fa]';
$lang['iconfa.howto.bbcode.icon.test'] = 'Par exemple, [fa]cubes[/fa] nous donnera l\'icône:';
$lang['iconfa.howto.bbcode.icon.variants'] = 'Les variantes (cf paragraphe suivant) sont possibles en BBCode et sont expliquées dans la documentation de PHPBoost.';
$lang['iconfa.howto.variants'] = 'Les variantes';
$lang['iconfa.howto.variants.explain'] = 'Font-Awesome propose une panoplie de variantes telles que la taille de l\'icône, l\'animation, la rotation, l\'empilement et bien d\'autres.';
$lang['iconfa.howto.variants.list'] = 'Leur fonctionnement est expliqué ici (anglais) : ';
$lang['iconfa.howto.variants.spinner'] = 'Nous donnera l\'icone "spinner", défini en rotation et faisant 2 fois sa taille initiale : ';

// --- Cssmenu

$lang['cssmenu.site.title'] = 'Menus de navigation Cssmenu';
$lang['cssmenu.site.slogan'] = 'Bac à sable - le design des cssmenu';
$lang['cssmenu.breadcrumb.index'] = 'Accueil';
$lang['cssmenu.breadcrumb.sandbox'] = 'Bac à sable';
$lang['cssmenu.breadcrumb.cssmenu'] = 'cssmenu';
$lang['cssmenu.h2'] = 'Les différents menus cssmenu';
$lang['cssmenu.element'] = 'Item du menu';
$lang['cssmenu.sub.element'] = 'Sous menu';
$lang['cssmenu.horizontal.sub.header'] = 'Menu de sous-entête';
$lang['cssmenu.sub.admin'] = 'Administration';
$lang['cssmenu.horizontal.top'] = 'Menu horizontal Header';
$lang['cssmenu.horizontal.scrolling'] = 'Menu horizontal déroulant';
$lang['cssmenu.vertical.scrolling'] = 'Menu vertical déroulant';
$lang['cssmenu.vertical.img'] = 'Menu avec images';
$lang['cssmenu.vertical.scrolling.left'] = 'Menu vertical déroulant à gauche';
$lang['cssmenu.vertical.scrolling.right'] = 'Menu vertical déroulant à droite';
$lang['cssmenu.actionslinks.sandbox'] = 'Bac à sable';
$lang['cssmenu.actionslinks.index'] = 'Accueil';
$lang['cssmenu.actionslinks.form'] = 'Formulaires';
$lang['cssmenu.actionslinks.css'] = 'CSS';
$lang['cssmenu.actionslinks.bbcode'] = 'BBCode';
$lang['cssmenu.actionslinks.menu'] = 'Cssmenu';
$lang['cssmenu.actionslinks.icons'] = 'Icônes';
$lang['cssmenu.actionslinks.table'] = 'Tableaux';
$lang['cssmenu.actionslinks.mail'] = 'Envoi de mail';
$lang['cssmenu.actionslinks.template'] = 'Génération de templates';
$lang['cssmenu.actionslinks'] = 'Menu options des modules';
$lang['cssmenu.group'] = 'Menu groupes';
$lang['cssmenu.static'] = 'Menu statique';
$lang['cssmenu.static.footer'] = 'Menu statique pied de page';

$lang['cssmenu.warning'] = 'Cette page a un design spécifique de manière à afficher tous les types de menus en fonction des emplacements potentiels susceptibles d\'être utilisés.
Il peut être mal adapté si votre design ne respecte pas l\'arborescence et les noms des classes/id du thème Base.';


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

?>
