<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 01 14
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

// --- Module titles
$lang['sandbox.module.title'] = 'Bac à sable';

$lang['title.config'] = 'Configuration';
$lang['title.admin.form'] = 'Formulaires dans l\'admin';
$lang['title.form.builder'] = 'Formulaires';
$lang['title.css'] = 'CSS';
$lang['title.multitabs'] = 'Multitabs';
$lang['title.plugins'] = 'Plugins';
$lang['title.bbcode'] = 'BBCode';
$lang['title.menu'] = 'Menus Cssmenu';
$lang['title.icons'] = 'Icônes Font-Awesome';
$lang['title.table.builder'] = 'Tableaux';
$lang['title.mail.sender'] = 'Envoi de mail';
$lang['title.string.template'] = 'Génération de template';

// --- Page d'accueil
$lang['welcome.message'] = '<p>Bienvenue dans le module Bac à sable.</p>
<br />
<p>Vous pouvez ici tester plusieurs parties du framework PHPBoost :</p>
<ul class="sandbox-home-list">
<li><i class="fa fa-fw fa-asterisk"></i> Le rendu des différents champs utilisables avec le <a href="' . SandboxUrlBuilder::form()->absolute() . '">constructeur de formulaires</a></li>
<li><i class="fab fa-fw fa-css3"></i> Le rendu des principales <a href="' . SandboxUrlBuilder::css()->absolute() . '">classes CSS</a></li>
<li><i class="fa fa-fw fa-list"></i> Le rendu du <a href="' . SandboxUrlBuilder::multitabs()->absolute() . '">plugin  Multitabs</a></li>
<li><i class="fa fa-fw fa-cube"></i> Le rendu des <a href="' . SandboxUrlBuilder::plugins()->absolute() . '">plugins jQuery</a> intégrés dans PHPBoost</li>
<li><i class="far fa-fw fa-file-code"></i> Le rendu des styles spécifiques du <a href="' . SandboxUrlBuilder::bbcode()->absolute() . '">BBCode</a></li>
<li><i class="fab fa-fw fa-font-awesome-flag"></i> Un tutoriel sur l\'utilisation des icônes de la librairie <a href="' . SandboxUrlBuilder::icons()->absolute() . '">Font Awesome</a></li>
<li><i class="fa fa-fw fa-list"></i> Le rendu des <a href="' . SandboxUrlBuilder::menu()->absolute() . '">menus de navigation cssmenu</a></li>
<li><i class="fa fa-fw fa-table"></i> La génération de <a href="' . SandboxUrlBuilder::table()->absolute() . '">tableaux dynamiques</a></li>
<li><i class="fa fa-fw fa-at"></i> L\'<a href="' . SandboxUrlBuilder::mail()->absolute() . '">envoi d\'emails</a></li>
<li><i class="fa fa-fw fa-code"></i> La <a href="' . SandboxUrlBuilder::template()->absolute() . '">génération de template</a> avec ou sans cache</li>
</ul>
<br />
';

// --- Framework lorem

$lang['lorem.short.content'] = 'Etiam hendrerit, tortor et faucibus dapibus, eros orci porta eros, in facilisis ipsum ipsum at nisl';
$lang['lorem.medium.content'] = 'Fusce vitae consequat nisl. Fusce vestibulum porta ipsum ac consectetur. Duis finibus mauris eu feugiat congue.
Aenean aliquam accumsan ipsum, ac dapibus dui ultricies non. In hac habitasse platea dictumst. Aenean mi nibh, varius vel lacus at, tincidunt luctus eros.
In hac habitasse platea dictumst. Vestibulum luctus lorem nisl, et hendrerit lectus dapibus ut. Phasellus sit amet nisl tortor.
Aenean pulvinar tellus nulla, sit amet mattis nisl semper eu. Phasellus efficitur nisi a laoreet dignissim. Aliquam erat volutpat.';
$lang['lorem.large.content'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit odio urna, blandit pharetra elit
scelerisque tempor. Nulla dapibus felis orci, at consectetur orci auctor eget. Donec eros lectus, mollis eget auctor vel, convallis ac mauris.
Cras imperdiet, erat ac semper volutpat, libero orci varius mi, et ullamcorper quam urna vitae augue. Maecenas maximus vitae diam vel porta.
Pellentesque dignissim dolor eu neque aliquet viverra. Maecenas tincidunt, mi non gravida tincidunt, lectus elit gravida massa,
sed viverra tortor diam pretium metus. In hac habitasse platea dictumst. Ut velit turpis, sollicitudin non risus et, pretium efficitur leo.
Integer elementum faucibus finibus. Nullam et felis sit amet felis blandit iaculis. Vestibulum massa arcu, finibus id enim ac, commodo aliquam metus.
Vestibulum feugiat urna nunc, et eleifend velit posuere ac. Vestibulum sagittis tempus nunc, sit amet dignissim ipsum sollicitudin eget.';

//
$lang['sandbox.summary'] = 'Sommaire';
$lang['sandbox.source.code'] = 'Voir le code source';

// --- Wiki
$lang['wiki.not'] = 'Le module Wiki n\'est pas installé et/ou activé';
$lang['wiki.conditions'] = 'Vous devez porter le module wiki dans votre thème pour que vos modifications soient actives.';
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
