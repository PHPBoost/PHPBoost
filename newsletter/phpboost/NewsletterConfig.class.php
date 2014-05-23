<?php
/*##################################################
 *		                   NewsletterConfig.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsletterConfig extends AbstractConfigData
{
	const MAIL_SENDER = 'mail_sender';
	const NEWSLETTER_NAME = 'newsletter_name';
	const AUTHORIZATIONS = 'authorizations';
	
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
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $auth)
	{
		$this->set_property(self::AUTHORIZATIONS, $auth);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::MAIL_SENDER => MailServiceConfig::load()->get_default_mail_sender(),
			self::NEWSLETTER_NAME => '',
			self::AUTHORIZATIONS => array('r1' => 63, 'r0' => 35, 'r-1' => 35)
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsletterConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'newsletter', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('newsletter', self::load(), 'config');
	}
}
?>