<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 26
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################


$lang['configuration.title']         = 'Configuration';
$lang['configuration.not.available'] = 'Non disponible sur votre serveur';
$lang['configuration.available']     = 'Disponible sur votre serveur';
$lang['configuration.unknown']       = 'Inconnu sur votre serveur';

// General config
$lang['configuration.general']                   = 'Configuration générale';
$lang['configuration.site.name']                 = 'Nom du site';
$lang['configuration.site.slogan']               = 'Slogan du site';
$lang['configuration.site.description']          = 'Description du site';
$lang['configuration.site.description.clue']     = 'Description de votre site dans les moteurs de recherche';
$lang['configuration.default.language']          = 'Langue par défaut du site';
$lang['configuration.default.theme']             = 'Thème par défaut du site';
$lang['configuration.theme.picture']             = 'Aperçu du thème';
$lang['configuration.theme.preview']             = 'Cliquez pour prévisualiser';
$lang['configuration.no.module.startable']       = 'Aucun module configurable en page de démarage';
$lang['configuration.start.page']                = 'Page de démarrage du site';
$lang['configuration.other.start.page']          = 'Autre adresse relative ou absolue';
$lang['configuration.visit.counter']             = 'Compteur de visites';
$lang['configuration.page.bench']                = 'Benchmark';
$lang['configuration.page.bench.clue']           = 'Affiche le temps de rendu de la page et le nombre de requêtes SQL';
$lang['configuration.display.theme.author']      = 'Info sur le thème';
$lang['configuration.display.theme.author.clue'] = 'Affiche des informations sur le thème dans le pied de page';

// Advanced config
$lang['configuration.advanced']           = 'Configuration avancée';
$lang['configuration.site.url']           = 'URL du serveur';
$lang['configuration.site.url.clue']      = 'Ex : https://www.phpboost.com';
$lang['configuration.site.path']          = 'Chemin de PHPBoost';
$lang['configuration.site.path.clue']     = 'Vide par défaut : site à la racine du serveur';
$lang['configuration.site.timezone']      = 'Choix du fuseau horaire';
$lang['configuration.site.timezone.clue'] = 'Permet d\'ajuster l\'heure à votre localisation';
	// Redirections
$lang['configuration.enable.redirection']         = 'Activation de la redirection de domaine';
$lang['configuration.redirection.local']          = 'La redirection ne peut pas être activée sur les sites en local';
$lang['configuration.redirection.subdomain']      = 'La redirection ne peut pas être activée pour les sites en sous-domaine';
$lang['configuration.redirection.mode']           = 'Mode de redirection';
$lang['configuration.redirection.with.www']       = 'Redirige les urls ' . AppContext::get_request()->get_domain_name() . ' vers www.'. AppContext::get_request()->get_domain_name();
$lang['configuration.redirection.without.www']    = 'Redirige les urls www.' . AppContext::get_request()->get_domain_name(). ' vers '. AppContext::get_request()->get_domain_name();
$lang['configuration.enable.redirection.https']   = 'Activation de la redirection HTTPS';
$lang['configuration.redirection.https.clue']     = 'Redirige les urls HTTP vers HTTPS';
$lang['configuration.redirection.https.disabled'] = 'Votre site n\'utilise actuellement pas le protocole HTTPS, activation de ce mode impossible';
$lang['configuration.enable.hsts']                = 'Activation de la fonctionnalité HSTS';
$lang['configuration.hsts.clue']                  = 'HTTP Strict Transport Security (souvent abrégé par HSTS) est un dispositif de sécurité par lequel un site web peut déclarer aux navigateurs qu\'ils doivent communiquer avec lui en utilisant exclusivement le protocole HTTPS, au lieu du HTTP.<br /><span class="text-strong error">Il est fortement recommandé de ne pas activer l\'option si le passage en HTTPS n\'est pas définitif.</span>';
$lang['configuration.hsts.duration']              = 'Delai de renouvellement de la sécurité HSTS';
$lang['configuration.hsts.duration.clue']         = 'En jours. 30 jours conseillé.';
$lang['configuration.hsts.subdomain']             = 'Inclusion des sous-domaines';
$lang['configuration.hsts.subdomain.clue']        = 'Activation de HSTS sur les sous-domaines';
	// Rewriting
$lang['configuration.url.rewriting']      = 'Activation de la réécriture des urls';
$lang['configuration.url.rewriting.clue'] = '
	L\'activation de la réécriture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site.
	Ces adresses seront donc bien mieux compréhensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre référencement sera grandement optimisé grâce à cette option.<br /><br />
	Cette option n\'est malheureusement pas disponible chez tous les hébergeurs. Cette page va vous permettre de tester si votre serveur supporte la réécriture des urls.
	Si après le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> à la racine de votre site via accès FTP à votre serveur, puis revenez sur cette page et désactivez la réécriture.
';
	// Server access
$lang['configuration.htaccess.manual.content']      = 'Contenu du fichier .htaccess';
$lang['configuration.htaccess.manual.content.clue'] = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier .htaccess qui se trouve à la racine du site, par exemple pour forcer une configuration du serveur web Apache.';
$lang['configuration.nginx.manual.content']         = 'Contenu du fichier nginx.conf';
$lang['configuration.nginx.manual.content.clue']    = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier nginx.conf qui se trouve à la racine du site, par exemple pour forcer une configuration du serveur web.';
$lang['configuration.robots.content']               = 'Contenu du fichier robots.txt';
$lang['configuration.robots.content.clue']          = 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier robots.txt qui se trouve à la racine du site, par exemple pour empêcher les robots d\'indexer certaines parties du site.';
	// Session
$lang['configuration.sessions']                     = 'Connexion utilisateurs';
$lang['configuration.cookie.name']                  = 'Nom du cookie des sessions';
$lang['configuration.cookie.duration']              = 'Durée de la session';
$lang['configuration.cookie.duration.clue']         = '3600 secondes conseillé';
$lang['configuration.active.session.duration']      = 'Durée utilisateurs actifs';
$lang['configuration.active.session.duration.clue'] = '300 secondes conseillé';
	// Cookie Bar
$lang['configuration.cookiebar']                   = 'Configuration des avertissement des cookies';
$lang['configuration.cookiebar.more']              = 'La loi impose désormais aux responsables de sites et aux fournisseurs de solutions d\'informer les internautes et de recueillir leur consentement avant l\'insertion de cookies ou autres traceurs. ';
$lang['configuration.enable.cookiebar']            = 'Activation de l\'avertissement des cookies';
$lang['configuration.cookiebar.clue']              = 'Nous vous recommandons de laisser l\'alerte afin d\'être certain de respecter la loi';
$lang['configuration.cookiebar.duration']          = 'Durée du cookie';
$lang['configuration.cookiebar.duration.clue']     = 'La loi limite la durée de vie à 13 mois maximum';
$lang['configuration.cookiebar.tracking.mode']     = 'Choisissez votre mode de fonctionnement';
$lang['configuration.cookiebar.trackers']          = 'Utilisation de cookies traceurs';
$lang['configuration.cookiebar.no.tracker']        = 'Utilisation de cookies techniques';
$lang['configuration.cookiebar.content']           = 'Message affiché dans la barre d\'avertissement';
$lang['configuration.cookiebar.content.clue']      = 'Vous pouvez personnaliser le message, mais celui-çi doit impérativement préciser si vous utilisez des cookies traceurs';
$lang['configuration.cookiebar.aboutcookie.title'] = 'Titre de la page "<a class="offload" href="' . UserUrlBuilder::aboutcookie()->rel() . '">En savoir plus</a>"';
$lang['configuration.cookiebar.aboutcookie']       = 'Message affiché dans la page "En savoir plus"';
$lang['configuration.cookiebar.aboutcookie.clue']  = 'Vous pouvez personnaliser le message en indiquant les cookies traceurs que vous utilisez';
	// Miscellaneous
$lang['configuration.miscellaneous']           = 'Divers';
$lang['configuration.enable.page.compression'] = 'Activation de la compression des pages, ceci accélère la vitesse d\'affichage';
$lang['configuration.debug.mode']              = 'Mode Debug';
$lang['configuration.debug.mode.clue']         = '
	Ce mode est particulièrement utile pour les développeurs car les erreurs sont affichées explicitement.
	Il est déconseillé d\'utiliser ce mode sur un site en production.
';
$lang['configuration.debug.mode.type']               = 'Sélection du mode debug';
$lang['configuration.debug.mode.type.normal']        = 'Normal';
$lang['configuration.debug.mode.type.strict']        = 'Strict';
$lang['configuration.enable.database.query.display'] = 'Activer l\'affichage et le suivi des requêtes SQL';

// Emails
$lang['configuration.email']                             = 'Envoi d\'email';
$lang['configuration.email.sending']                     = 'Configuration de l\'envoi d\'email';
$lang['configuration.email.default.sender']              = 'Adresse de l\'expéditeur par défaut';
$lang['configuration.email.default.sender.clue']         = 'Adresse qui sera utilisée quand l\'adresse de l\'expéditeur n\'est pas spécifiée.';
$lang['configuration.email.administrators.address']      = 'Adresse des administrateurs';
$lang['configuration.email.administrators.address.clue'] = 'Liste des adresses mail (séparées par des virgules) qui seront utilisées pour envoyer les mails destinés aux administrateurs.';
$lang['configuration.email.signature']                   = 'Signature des mails';
$lang['configuration.email.signature.clue']              = 'La signature sera ajoutée à la fin de chaque mail envoyé par PHPBoost';
$lang['configuration.email.send.protocol']               = 'Protocole d\'envoi';
$lang['configuration.email.send.protocol.clue'] = '
	Généralement, les hébergeurs configurent correctement le serveur pour qu\'il soit directement capable d\'envoyer des mails.
	Cependant, certains utilisateurs souhaitent modifier la façon dont le serveur expédie les mails. Dans ce cas, il faut utiliser une configuration SMTP spécifique
	qui s\'active en cochant la case ci-dessous. Une fois le serveur SMTP configuré, il sera utilisé pour tous les envois de mail de PHPBoost.
';
$lang['configuration.email.use.custom.smtp.configuration'] = 'Utiliser une configuration SMTP spécifique';
$lang['configuration.email.custom.smtp.configuration']     = 'Configuration SMTP personnalisée';
$lang['configuration.email.smtp.host']                     = 'Hôte';
$lang['configuration.email.smtp.port']                     = 'Port';
$lang['configuration.email.smtp.login']                    = 'Login';
$lang['configuration.email.smtp.password']                 = 'Mot de passe';
$lang['configuration.email.smtp.secure.protocol']          = 'Protocole sécurisé';
$lang['configuration.email.smtp.secure.protocol.none']     = 'Aucun';
$lang['configuration.email.smtp.secure.protocol.tls']      = 'TLS';
$lang['configuration.email.smtp.secure.protocol.ssl']      = 'SSL';

?>
