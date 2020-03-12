<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 05
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
$lang['title.admin.fwkboost'] = 'Rendus dans l\'admin';
$lang['title.theme.fwkboost'] = 'Dans le thème';
$lang['title.builder'] = 'Constructeur PHP';
$lang['title.fwkboost'] = 'FWKBoost';
$lang['title.component'] = 'Composants';
$lang['title.layout'] = 'Mise en page';
$lang['title.multitabs'] = 'Multitabs';
$lang['title.plugins'] = 'Plugins';
$lang['title.bbcode'] = 'BBCode';
$lang['title.menu'] = 'Menus de navigation';
$lang['title.icons'] = 'Icônes';
$lang['title.miscellaneous'] = 'Divers';
$lang['title.table'] = 'Tableaux';
$lang['title.email'] = 'Email';
$lang['title.template'] = 'Template';

$lang['see.render'] = 'Voir le rendu';

// --- Page d'accueil
$lang['welcome.message'] = '
    <p>Bienvenue dans le module Bac à sable.</p>
    <p class="align-center">Vous pouvez tester dans ce module les différents composants du fwkboost de PHPBoost : <span class="pinned visitor big"><i class="fa iboost fa-iboost-phpboost"></i> FWKBoost</span></p>
    <p>Le menu <i class="fa fa-hard-hat"></i> ci-dessus vous permet de naviguer rapidement entre et dans les différentes pages.</p>
';

$lang['welcome.see'] = 'Voir';
$lang['welcome.admin'] = 'En admin';

$lang['welcome.builder'] = 'Le rendu des différentes fonctionnailtés utilisables avec le constructeur php: champs de formulaire, maps, menus de liens, etc.';
$lang['welcome.fwkboost'] = 'Le rendu des différents éléments du framework HTML/CSS/JS FWKBoost de PHPBoost.';
$lang['welcome.bbcode'] = 'Le rendu des éléments spécifiques déclarés en bbcode qui apportent un design différent du FWKBoost.';
$lang['welcome.menu'] = 'Le rendu des menus de navigations selon les emplacements potentiels.';
$lang['welcome.icons'] = 'Un aperçu de ce qu\'il est possible de faire dans phpboost au niveau des icônes-font.';
$lang['welcome.misc'] = 'Diverses pages de test en php.';

// Lorem
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

// Common
$lang['sandbox.summary'] = 'Sommaire';
$lang['sandbox.source.code'] = 'Voir le code source';
$lang['sandbox.pbt.doc'] = 'la documentation de PHPBoost';

// Mail
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

// Template
$lang['string_template.result'] = 'Temps de génération du template sans cache : :non_cached_time secondes<br />Temps de génération du template avec cache : :cached_time secondes<br />Longueur de la chaîne : :string_length caractères.';

?>
