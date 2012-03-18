<?php
/*##################################################
 *		                   NewsletterConfig.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class NewsletterConfig extends AbstractConfigData
{
	const MAIL_SENDER = 'mail_sender';
	const NEWSLETTER_NAME = 'newsletter_name';
	const AUTHORIZATION = 'authorizations';
	
	const AUTH_READ = 1;
	const AUTH_SUBSCRIBE = 2;
	const AUTH_READ_SUBSCRIBERS = 4;
	const AUTH_MODERATION_SUBSCRIBERS = 8;
	const AUTH_CREATE_NEWSLETTER = 16;
	const AUTH_READ_ARCHIVES = 32;
	
	const CAT_AUTH_READ = 1;
	const CAT_AUTH_SUBSCRIBE = 2;
	const CAT_AUTH_READ_SUBSCRIBERS = 4;
	const CAT_AUTH_MODERATION_SUBSCRIBERS = 8;
	const CAT_AUTH_CREATE_NEWSLETTER = 16;
	const CAT_AUTH_READ_ARCHIVES = 32;
	
	public function get_mail_sender()
	{
		return $this->get_property(self::MAIL_SENDER);
	}

	public function set_mail_sender($sender)
	{
		$this->set_property(self::MAIL_SENDER, $sender);
	}
	
	public function get_newsletter_name()
	{
		return $this->get_property(self::NEWSLETTER_NAME);
	}

	public function set_newsletter_name($name)
	{
		$this->set_property(self::NEWSLETTER_NAME, $name);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATION);
	}

	public function set_authorizations(Array $auth)
	{
		$this->set_property(self::AUTHORIZATION, $auth);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::MAIL_SENDER => MailServiceConfig::load()->get_default_mail_sender(),
			self::NEWSLETTER_NAME => '',
			self::AUTHORIZATION => array('r1' => 15, 'r0' => 3, 'r-1' => 3, '2' => 48, '-1' => 32, '1' => 32)
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsletterConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'newsletter-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'newsletter-config');
	}
}
?>