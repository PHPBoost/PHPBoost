<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : March 11, 2011
 *   copyright            : (C) 2010 Kevin MASSY
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(
	//Admin
	new UrlControllerMapper('AdminNewsletterConfigController', '`^/admin(?:/config)?/?$`'),
	
	//Streams
	new UrlControllerMapper('NewsletterStreamsManageController', '`^/streams/?$`'),
	new UrlControllerMapper('NewsletterStreamsFormController', '`^/stream/add/?$`'),
	new UrlControllerMapper('NewsletterStreamsFormController', '`^/stream/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('NewsletterDeleteStreamController', '`^/stream/([0-9]+)/delete/?$`', array('id')),
	
	//Newsletter
	new UrlControllerMapper('HomeAddNewsletterController', '`^/add/?$`'),
	new UrlControllerMapper('AddNewsletterController', '`^/add/([a-z]+)?/?$`', array('type')),
	
	//Suscribers
	new UrlControllerMapper('NewsletterSubscribersListController', '`^/subscribers/([0-9]+)-([a-z0-9-_]+)/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('id_stream', 'rewrited_name_stream', 'field', 'sort', 'page')),
	new UrlControllerMapper('NewsletterSubscribeController', '`^/subscribe/?$`'),
	new UrlControllerMapper('NewsletterUnsubscribeController', '`^/unsubscribe/?$`'),
	new UrlControllerMapper('NewsletterEditSubscriberController', '`^/subscriber/([0-9]+)/edit/?$`', array('id')),
	new UrlControllerMapper('NewsletterDeleteSubscriberController', '`^/subscriber/([0-9]+)/delete(?:/([0-9]+))?/?$`', array('id', 'id_stream')),
	
	//Archives
	new UrlControllerMapper('NewsletterArchivesController', '`^/archives(?:/([0-9]+)-([a-z0-9-_]+))?/?([a-z]+)?/?([a-z]+)?/?([0-9]+)?/?$`', array('id_stream', 'rewrited_name_stream',  'field', 'sort', 'page')),
	new UrlControllerMapper('NewsletterArchiveController', '`^/archive/([0-9]+)?/?$`', array('id')),
	new UrlControllerMapper('NewsletterDeleteArchiveController', '`^/delete/([0-9]+)/([0-9]+)/?$`', array('id', 'id_stream')),
	
	new UrlControllerMapper('NewsletterHomeController', '`^(?:/([0-9]+))?/?$`', array('page')),
);
DispatchManager::dispatch($url_controller_mappers);
?>