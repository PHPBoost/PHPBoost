<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

####################################################
#                     French                       #
####################################################

$lang = array(
	'configuration' => 'Configuration',
	//Mail config
	'mail' => 'Envoi de mail',
	'mail-config' => 'Configuration de l\'envoi de mail',
	'mail-config.general_mail_config' => 'Configuration générale',
	'mail-config.default_mail_sender' => 'Adresse de l\'expéditeur par défaut',
	'mail-config.default_mail_sender_explain' => 'Adresse qui sera utilisée quand l\'adresse de l\'expéditeur n\'est pas spécifiée.',
	'mail-config.administrators_mails' => 'Adresse des administrateurs',
	'mail-config.administrators_mails_explain' => 'Liste des adresses mail (séparées par des virgules) qui seront utilisées pour envoyer les mails destinés aux administrateurs.',
	'mail-config.mail_signature' => 'Signature des mails',
	'mail-config.mail_signature_explain' => 'La signature sera ajoutée à la fin de chaque mail envoyé par PHPBoost',
	'mail-config.send_protocol' => 'Protocole d\'envoi',
	'mail-config.send_protocol_explain' => 'Généralement, les hébergeurs configurent correctement le serveur pour qu\'il soit directement capable d\'envoyer des mails.
			Cependant, certains utilisateurs souhaitent modifier la façon dont le serveur expédie les mails. Dans ce cas, il faut utiliser une configuration SMTP spécifique
			qui s\'active en cochant la case ci-dessous. Une fois le serveur SMTP configuré, il sera utilisé pour tous les envois de mail de PHPBoost.',
	'mail-config.use_custom_smtp_configuration' => 'Utiliser une configuration SMTP spécifique',
	'mail-config.custom_smtp_configuration' => 'Configuration SMTP personnalisée',
	'mail-config.smtp_host' => 'Hôte',
	'mail-config.smtp_port' => 'Port',
	'mail-config.smtp_login' => 'Login',
	'mail-config.smtp_password' => 'Mot de passe',
	'mail-config.smtp_secure_protocol' => 'Protocole sécurisé',
	'mail-config.smtp_secure_protocol_none' => 'Aucun',
	'mail-config.smtp_secure_protocol_tls' => 'TLS',
	'mail-config.smtp_secure_protocol_ssl' => 'SSL',

	//General config
	'general-config' => 'Configuration générale',
	'general-config.site_name' => 'Nom du site',
	'general-config.site_slogan' => 'Slogan du site',
	'general-config.site_description' => 'Description du site',
	'general-config.site_description-explain' => 'Description de votre site dans les moteurs de recherche',
	'general-config.default_language' => 'Langue (par défaut) du site',
	'general-config.default_theme' => 'Thème (par défaut) du site',
	'general-config.theme_picture' => 'Aperçu du thème',
	'general-config.theme_preview_click' => 'Cliquez pour prévisualiser',
	'general-config.view_real_preview' => 'Voir en taille réelle',
	'general-config.start_page' => 'Page de démarrage du site',
	'general-config.other_start_page' => 'Autre adresse relative ou absolue',
	'general-config.visit_counter' => 'Compteur de visites',
	'general-config.page_bench' => 'Benchmark',
	'general-config.page_bench-explain' => 'Affiche le temps de rendu de la page et le nombre de requêtes SQL',
	'general-config.display_theme_author' => 'Info sur le thème',
	'general-config.display_theme_author-explain' => 'Affiche des informations sur le thème dans le pied de page',

	//Advanced config
	'advanced-config' => 'Configuration avancée',
	'advanced-config.site_url' => 'URL du serveur',
	'advanced-config.site_url-explain' => 'Ex : https://www.phpboost.com',
	'advanced-config.site_path' => 'Chemin de PHPBoost',
	'advanced-config.site_path-explain' => 'Vide par défaut : site à la racine du serveur',
	'advanced-config.site_timezone' => 'Choix du fuseau horaire',
	'advanced-config.site_timezone-explain' => 'Permet d\'ajuster l\'heure à votre localisation',

	'advanced-config.redirection_www_enabled' => 'Activation de la redirection de domaine',
	'advanced-config.redirection_www_enabled.local' => 'La redirection ne peut pas être activée sur les sites en local',
	'advanced-config.redirection_www_enabled.subdomain' => 'La redirection ne peut pas être activée pour les sites en sous-domaine',
	'advanced-config.redirection_www_mode' => 'Mode de redirection',
	'advanced-config.redirection_www.with_www' => 'Redirige les urls ' . AppContext::get_request()->get_domain_name() . ' vers www.'. AppContext::get_request()->get_domain_name(),
	'advanced-config.redirection_www.without_www' => 'Redirige les urls www.' . AppContext::get_request()->get_domain_name(). ' vers '. AppContext::get_request()->get_domain_name(),

	'advanced-config.redirection_https_enabled' => 'Activation de la redirection HTTPS',
	'advanced-config.redirection_https_enabled.explain' => 'Redirige les urls HTTP vers HTTPS',
	'advanced-config.redirection_https_enabled.explain-disable' => 'Votre site n\'utilise actuellement pas le protocole HTTPS, activation de ce mode impossible',
	'advanced-config.hsts_security_enabled' => 'Activation de la fonctionnalité HSTS',
	'advanced-config.hsts_security.explain' => 'HTTP Strict Transport Security (souvent abrégé par HSTS) est un dispositif de sécurité par lequel un site web peut déclarer aux navigateurs qu\'ils doivent communiquer avec lui en utilisant exclusivement le protocole HTTPS, au lieu du HTTP.<br /><span class="text-strong error">Il est fortement recommandé de ne pas activer l\'option si le passage en HTTPS n\'est pas définitif.</span>',
	'advanced-config.hsts_security_duration' => 'Delai de renouvellement de la sécurité HSTS',
	'advanced-config.hsts_security_duration.explain' =>  'En jours. 30 jours conseillé.',
	'advanced-config.hsts_security_subdomain' => 'Inclusion des sous-domaines',
	'advanced-config.hsts_security_subdomain.explain' => 'Activation de HSTS sur les sous-domaines',
	'advanced-config.url-rewriting' => 'Activation de la réécriture des urls',
	'advanced-config.url-rewriting.explain' => 'L\'activation de la réécriture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compréhensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre référencement sera grandement optimisé grâce à cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les hébergeurs. Cette page va vous permettre de tester si votre serveur supporte la réécriture des urls. Si après le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> à la racine de votre site via accès FTP à votre serveur, puis revenez sur cette page et désactivez la réécriture.',

	'advanced-config.config.not-available' => 'Non disponible sur votre serveur',
	'advanced-config.config.available' => 'Disponible sur votre serveur',
	'advanced-config.config.unknown' => 'Inconnu sur votre serveur',

	'advanced-config.htaccess-manual-content' => 'Contenu du fichier .htaccess',
	'advanced-config.htaccess-manual-content.explain' => 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier .htaccess qui se trouve à la racine du site, par exemple pour forcer une configuration du serveur web Apache.',

	'advanced-config.robots-content' => 'Contenu du fichier robots.txt',
	'advanced-config.robots-content.explain' => 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier robots.txt qui se trouve à la racine du site, par exemple pour empêcher les robots d\'indexer certaines parties du site.',

	'advanced-config.sessions-config' => 'Connexion utilisateurs',
	'advanced-config.cookie-name' => 'Nom du cookie des sessions',
	'advanced-config.cookie-name.style-wrong' => 'Le nom du cookie doit obligatoirement être une suite de lettres et de chiffres',
	'advanced-config.cookie-duration' => 'Durée de la session',
	'advanced-config.cookie-duration.explain' => '3600 secondes conseillé',
	'advanced-config.active-session-duration' => 'Durée utilisateurs actifs',
	'advanced-config.active-session-duration.explain' => '300 secondes conseillé',
	'advanced-config.integer-required' => 'La valeur doit être un nombre',

	'advanced-config.miscellaneous' => 'Divers',
	'advanced-config.output-gziping-enabled' => 'Activation de la compression des pages, ceci accélère la vitesse d\'affichage',
	'advanced-config.debug-mode' => 'Mode Debug',
	'advanced-config.debug-mode.explain' => 'Ce mode est particulièrement utile pour les développeurs car les erreurs sont affichées explicitement. Il est déconseillé d\'utiliser ce mode sur un site en production.',
	'advanced-config.debug-mode.type' => 'Sélection du mode debug',
	'advanced-config.debug-mode.type.normal' => 'Normal',
	'advanced-config.debug-mode.type.strict' => 'Strict',
	'advanced-config.debug-display-database-query-enabled' => 'Activer l\'affichage et le suivi des requêtes SQL',

	//Cookie Bar
	'advanced-config.cookiebar-config' => 'Configuration des avertissement des cookies',
	'advanced-config.cookiebar-more' => 'La loi impose désormais aux responsables de sites et aux fournisseurs de solutions d\'informer les internautes et de recueillir leur consentement avant l\'insertion de cookies ou autres traceurs. ',
	'advanced-config.cookiebar-activation' => 'Activation de l\'avertissement des cookies',
	'advanced-config.cookiebar-activation.desc' => 'Nous vous recommandons de laisser l\'alerte afin d\'être certain de respecter la loi',
	'advanced-config.cookiebar-duration' => 'Durée du cookie',
	'advanced-config.cookiebar-duration.desc' => 'La loi limite la durée de vie à 13 mois maximum',
	'advanced-config.cookiebar-tracking.choice' => 'Choisissez votre mode de fonctionnement',
	'advanced-config.cookiebar-tracking.track' => 'Utilisation de cookies traceurs',
	'advanced-config.cookiebar-tracking.notrack' => 'Utilisation de cookies techniques',
	'advanced-config.cookiebar-content' => 'Message affiché dans la barre d\'avertissement',
	'advanced-config.cookiebar-content.explain' => 'Vous pouvez personnaliser le message, mais celui-çi doit impérativement préciser si vous utilisez des cookies traceurs',
	'advanced-config.cookiebar-aboutcookie-title' => 'Titre de la page "<a href="' . UserUrlBuilder::aboutcookie()->rel() . '">En savoir plus</a>"',
	'advanced-config.cookiebar-aboutcookie' => 'Message affiché dans la page "En savoir plus"',
	'advanced-config.cookiebar-aboutcookie.explain' => 'Vous pouvez personnaliser le message en indiquant les cookies traceurs que vous utilisez',

);
?>
