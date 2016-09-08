<?php
/*##################################################
 *                           admin-config-common.php
 *                            -------------------
 *   begin                : April 12, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
	'mail-config.administrators_mails_explain' => 'Liste des adresses mail (séparées par des virgules) à qui seront envoyés les mails destinés aux administrateurs.',
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
	'general-config.visit_counter' => 'Compteur de visite',
	'general-config.page_bench' => 'Benchmark',
	'general-config.page_bench-explain' => 'Affiche le temps de rendu de la page et le nombre de requêtes SQL',
	'general-config.display_theme_author' => 'Info sur le thème',
	'general-config.display_theme_author-explain' => 'Affiche des informations sur le thème dans le pied de page',
	
	//Advanced config
	'advanced-config' => 'Configuration avancée',
	'advanced-config.site_url' => 'URL du serveur',
	'advanced-config.site_url-explain' => 'Ex : http://www.phpboost.com',
	'advanced-config.site_path' => 'Chemin de PHPBoost',
	'advanced-config.site_path-explain' => 'Vide par défaut : site à la racine du serveur',
	'advanced-config.site_timezone' => 'Choix du fuseau horaire',
	'advanced-config.site_timezone-explain' => 'Permet d\'ajuster l\'heure à votre localisation',
	
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
);
?>
