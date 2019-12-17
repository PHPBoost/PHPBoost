<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 31
 * @since       PHPBoost 3.0 - 2011 02 01 11
*/

class NewsletterConfig extends AbstractConfigData
{
	const MAIL_SENDER = 'mail_sender';
	const NEWSLETTER_NAME = 'newsletter_name';
	const DEFAULT_CONTENTS = 'default_contents';
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

	public function get_default_contents()
	{
		return $this->get_property(self::DEFAULT_CONTENTS);
	}

	public function set_default_contents($value)
	{
		$this->set_property(self::DEFAULT_CONTENTS, $value);
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
			self::DEFAULT_CONTENTS => '',
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
