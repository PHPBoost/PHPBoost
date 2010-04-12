<?php
/*##################################################
 *                          admin-config-common.php
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
#                     English                       #
 ####################################################

$lang = array(
	'mail_config' => 'Mail sending configuration',
	'general_mail_config' => 'General mail configuration',
	'default_mail_sender' => 'Default mail sender',
	'default_mail_sender_explain' => 'Mail address which will be used when PHPBoost sends a mail without specifying the sender',
	'administrators_mails' => 'Administrators mails',
	'administrators_mails_explain' => 'List of the administrator\'s mail addresses (comma-separated) to which will be sent the mail which are sent to administrators',
	'mail_signature' => 'Mail signature',
	'mail_signature_explain' => 'This will be appended to which mail sent by PHPBoost',
	'send_protocol' => 'Sending protocol',
	'send_protocol_explain' => 'Generally, hosters configure correctly the server for it to be able to send mails. However, some
	users need to change the way it sends mails by using a custom SMTP configuration. To choose a custom configuration, check the 
	box below and fill the configuration fields. This configuration will be used each time PHPBoost sends a mail.',
	'use_custom_smtp_configuration' => 'Use a custom SMTP configuration',
	'custom_smtp_configuration' => 'Custom SMTP configuration',
	'smtp_host' => 'Host',
	'smtp_port' => 'Port',
	'smtp_login' => 'Login',
	'smtp_password' => 'Password',
	'smtp_secure_protocol' => 'Secured protocol',
	'smtp_secure_protocol_none' => 'None',
	'smtp_secure_protocol_tls' => 'TLS',
	'smtp_secure_protocol_ssl' => 'SSL',
	'mail_config_saved' => 'The configuration has been saved successfully'
);

?>