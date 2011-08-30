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
	'mail_config' => 'Configuration de l\'envoi de mail',
	'general_mail_config' => 'Configuration générale',
	'default_mail_sender' => 'Adresse de l\'expéditeur par défaut',
	'default_mail_sender_explain' => 'Adresse qui sera utilisée quand l\'adresse de l\'expéditeur n\'est pas spécifiée.',
	'administrators_mails' => 'Adresse des administrateurs',
	'administrators_mails_explain' => 'Liste des adresses mail (séparées par des virgules) à qui seront envoyés les mails destinés aux administrateurs.',
	'mail_signature' => 'Signature des mails',
	'mail_signature_explain' => 'La signature sera ajoutée à la fin de chaque mail envoyé par PHPBoost',
	'send_protocol' => 'Protocole d\'envoi',
	'send_protocol_explain' => 'Généralement, les hébergeurs configurent correctement le serveur pour qu\'il soit directement capable d\'envoyer des mails.
			Cependant, certains utilisateurs souhaitent modifier la façon dont le serveur expédie les mails, dans ce cas-là il faut utiliser une configuration SMTP spécifique qui se 
			qui s\'active en cochant la case ci-dessous. Une fois le serveur SMTP configuré, il sera utilisé par tous les envois de mail de PHPBoost.',
	'use_custom_smtp_configuration' => 'Utiliser une configuration SMTP spécifique',
	'custom_smtp_configuration' => 'Configuration SMTP personnalisée',
	'smtp_host' => 'Hôte',
	'smtp_port' => 'Port',
	'smtp_login' => 'Login',
	'smtp_password' => 'Mot de passe',
	'smtp_secure_protocol' => 'Protocole sécurisé',
	'smtp_secure_protocol_none' => 'Aucun',
	'smtp_secure_protocol_tls' => 'TLS',
	'smtp_secure_protocol_ssl' => 'SSL',
	'mail_config_saved' => 'La configuration a bien été enregistrée',
	
	//General config
	'general-config.success' => 'La configuration générale du site a été enregistrée avec succès',
	'general-config' => 'Configuration générale',
	'general-config.site_name' => 'Nom du site',
	'general-config.site_description' => 'Description du site',
	'general-config.site_description-explain' => '(facultatif) Utile pour le référencement dans les moteurs de recherche',
	'general-config.site_keywords' => 'Mots clés du site',
	'general-config.site_keywords-explain' => '(facultatif) A rentrer séparés par des virgules, ils servent au référencement dans les moteurs de recherche',
	
	'general-config.visit_counter' => 'Compteur',
	'general-config.page_bench' => 'Benchmark',
	'general-config.page_bench-explain' => 'Affiche le temps de rendu de la page et le nombre de requêtes SQL',
	'general-config.display_theme_author' => 'Info sur le thème',
	'general-config.display_theme_author-explain' => 'Affiche des informations sur le thème dans le pied de page',
	
	//Advanced config
	'advanced-config.success' => 'La configuration avancée du site a été enregistrée avec succès',
	'advanced-config' => 'Configuration avancée',
	'advanced-config.site_url' => 'URL du serveur',
	'advanced-config.site_url-explain' => 'Ex : http://www.phpboost.com',
	'advanced-config.site_path' => 'Chemin de PHPBoost',
	'advanced-config.site_path-explain' => 'Vide par défaut : site à la racine du serveur',
	'advanced-config.site_timezone' => 'Choix du fuseau horaire',
	'advanced-config.site_timezone-explain' => 'Permet d\'ajuster l\'heure à votre localisation',
	
	'url-rewriting' => 'Activation de la réécriture des urls',
	'url-rewriting.explain' => 'L\'activation de la réécriture des urls permet d\'obtenir des urls bien plus simples et claires sur votre site. Ces adresses seront donc bien mieux compréhensibles pour vos visiteurs, mais surtout pour les robots d\'indexation. Votre référencement sera grandement optimisé grâce à cette option.<br /><br />Cette option n\'est malheureusement pas disponible chez tous les hébergeurs. Cette page va vous permettre de tester si votre serveur supporte la réécriture des urls. Si après le test vous tombez sur des erreurs serveur, ou pages blanches, c\'est que votre serveur ne le supporte pas. Supprimez alors le fichier <strong>.htaccess</strong> à la racine de votre site via accès FTP à votre serveur, puis revenez sur cette page et désactivez la réécriture.',
	
	'config.not-available' => '<span style="color:#B22222;font-weight:bold;">Non disponible sur votre serveur</span>',
	'config.available' => '<span style="color:#008000;font-weight:bold;">Disponible sur votre serveur</span>',

	'htaccess-manual-content' => 'Contenu du fichier .htaccess',
	'htaccess-manual-content.explain' => 'Vous pouvez dans ce champ mettre les instructions que vous souhaitez intégrer au fichier .htaccess qui se trouve à la racine du site, par exemple pour forcer une configuration du serveur web Apache.',
	
	'sessions-config' => 'Connexion utilisateurs',
	'sessions-config.cookie-name' => 'Nom du cookie des sessions',
	'sessions-config.cookie-name.style-wrong' => 'Le nom du cookie doit être obligatoirement une suite de lettre et de chiffre',
	'sessions-config.cookie-duration' => 'Durée de la session',
	'sessions-config.cookie-duration.explain' => '3600 secondes conseillé',
	'sessions-config.active-session-duration' => 'Durée utilisateurs actifs',
	'sessions-config.active-session-duration.explain' => '300 secondes conseillé',
	'sessions-config.integer-required' => 'La valeur doit être un nombre',
	
	'miscellaneous' => 'Divers',
	'miscellaneous.output-gziping-enabled' => 'Activation de la compression des pages, ceci accélère la vitesse d\'affichage',
	'miscellaneous.unlock-administration' => 'Code de déverrouillage',
	'miscellaneous.unlock-administration.explain' => 'Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné.',
	'miscellaneous.unlock-administration.request' => 'Renvoyer le code de déverrouillage',
	'miscellaneous.debug-mode' => 'Mode Debug',
	'miscellaneous.debug-mode.explain' => 'Ce mode est particulièrement utile pour les développeurs car les erreurs sont affichées explicitement. Il est déconseillé d\'utiliser ce mode sur un site en production.',
	'miscellaneous.debug-mode.type' => 'Séléction du mode debug',
	'miscellaneous.debug-mode.type.normal' => 'Normal',
	'miscellaneous.debug-mode.type.strict' => 'Stricte',

	'unlock-code.title' => 'Mail à conserver',
	'unlock-code.content' => 'Code à conserver (Il ne vous sera plus délivré) : :unlock_code
	<br /><br />
	Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné.
	Il vous sera demandé dans le formulaire de connexion directe à l\'administration (:server_url/admin/admin_index.php)'

);
?>