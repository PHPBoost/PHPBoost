<?php
/*##################################################
 *                              faq_begin.php
 *                            -------------------
 *   begin                : December 16, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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

if (defined('PHPBOOST') !== true)	
	exit;

$Cache->load('faq');
load_module_lang('faq'); //Chargement de la langue du module.
$faq_config = FaqConfig::load();

$auth_read = $User->check_auth($faq_config->get_authorizations(), FaqAuthorizationsService::READ_AUTHORIZATIONS);
$auth_write = $User->check_auth($faq_config->get_authorizations(), FaqAuthorizationsService::WRITE_AUTHORIZATIONS);

$id_faq = retrieve(GET, 'id', 0);
//For users who have disabled javascript
$id_question = retrieve(GET, 'question', 0);
	
?>