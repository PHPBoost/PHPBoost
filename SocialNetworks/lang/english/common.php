<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : January 05, 2018
 *   copyright            : (C) 2018 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

$lang['module_name'] = 'Social networks';
$lang['module_config_title'] = 'Social networks configuration';

//Authentication Configuration
$lang['authentication.config.curl_extension_disabled'] = '<b>php_curl</b> extension is disabled on this server. Enable it to use Facebook and Google authentication methods.';
$lang['authentication.config.facebook-auth-enabled'] = 'Enable Facebook authentication';
$lang['authentication.config.facebook-auth-enabled-explain'] = 'Go to <a href="https://developers.facebook.com">https://developers.facebook.com</a> to create your id.<br/>
Specify the following callback URL during configuration:<br/>
<b>' . UserUrlBuilder::connect(FacebookAuthenticationMethod::AUTHENTICATION_METHOD)->absolute() . '</b>';
$lang['authentication.config.facebook-app-id'] = 'Facebook App ID';
$lang['authentication.config.facebook-app-key'] = 'Facebook App Secret';

$lang['authentication.config.google-auth-enabled'] = 'Enable Google authentication';
$lang['authentication.config.google-auth-enabled-explain'] = 'Go to <a href="https://console.developers.google.com/project">https://console.developers.google.com/project</a> to create your id.<br/>
Specify the following callback URL during configuration:<br/>
<b>' . UserUrlBuilder::connect(GoogleAuthenticationMethod::AUTHENTICATION_METHOD)->absolute() . '</b>';
$lang['authentication.config.google-client-id'] = 'Google Client ID';
$lang['authentication.config.google-client-secret'] = 'Google Client Secret';

$lang['authentication.config.linkedin-auth-enabled'] = 'Enable LinkedIn authentication';
$lang['authentication.config.linkedin-auth-enabled-explain'] = 'Go to <a href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a> to create your id.<br/>
Specify the following callback URL during configuration:<br/>
<b>' . UserUrlBuilder::connect(LinkedInAuthenticationMethod::AUTHENTICATION_METHOD)->absolute() . '</b>';
$lang['authentication.config.linkedin-client-id'] = 'LinkedIn Client ID';
$lang['authentication.config.linkedin-client-secret'] = 'LinkedIn Client Secret';

$lang['authentication.config.twitter-auth-enabled'] = 'Enable Twitter authentication';
$lang['authentication.config.twitter-auth-enabled-explain'] = 'Go to <a href="http://twitter.com/apps">http://twitter.com/apps</a> to create your id.<br/>
Specify the following callback URL during configuration:<br/>
<b>' . UserUrlBuilder::connect(TwitterInAuthenticationMethod::AUTHENTICATION_METHOD)->absolute() . '</b>';
$lang['authentication.config.twitter-consumer-key'] = 'Twitter Consumer Key';
$lang['authentication.config.twitter-consumer-secret'] = 'Twitter Consumer Secret';

//Sign in label
$lang['facebook-connect'] = 'Sign in with Facebook';
$lang['google-connect'] = 'Sign in with Google+';
$lang['linkedin-connect'] = 'Sign in with LinkedIn';
$lang['twitter-connect'] = 'Sign in with Twitter';

?>