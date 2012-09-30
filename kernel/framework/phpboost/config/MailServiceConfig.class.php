<?php
/*##################################################
 *		                  MailServiceConfig.class.php
 *                            -------------------
 *   begin                : April 13, 2010
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

/**
 * This class contains the configuration of PHPBoost's mail service.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class MailServiceConfig extends AbstractConfigData
{
	public function get_default_mail_sender()
	{
		return $this->get_property('default_mail_sender');
	}
	
	public function set_default_mail_sender($sender)
	{
		$this->set_property('default_mail_sender', $sender);
	}
	
	public function get_administrators_mails()
	{
		return $this->get_property('administrators_mails');
	}
	
	public function set_administrators_mails(array $mails)
	{
		$this->set_property('administrators_mails', $mails);
	}
	
	public function get_mail_signature()
	{
		return $this->get_property('mail_signature');
	}
	
	public function set_mail_signature($signature)
	{
		$this->set_property('mail_signature', $signature);
	}
	
	public function is_smtp_enabled()
	{
		return $this->get_property('use_smtp');
	}

	public function enable_smtp()
	{
		return $this->set_property('use_smtp', true);
	}

	public function disable_smtp()
	{
		$this->set_property('use_smtp', false);
	}

	public function get_smtp_host()
	{
		return $this->get_property('smtp_host');
	}

	public function set_smtp_host($host)
	{
		$this->set_property('smtp_host', $host);
	}

	public function get_smtp_port()
	{
		return $this->get_property('smtp_port');
	}

	public function set_smtp_port($port)
	{
		$this->set_property('smtp_port', $port);
	}

	public function get_smtp_login()
	{
		return $this->get_property('smtp_login');
	}

	public function set_smtp_login($login)
	{
		$this->set_property('smtp_login', $login);
	}

	public function get_smtp_password()
	{
		return $this->get_property('smtp_password');
	}

	public function set_smtp_password($password)
	{
		$this->set_property('smtp_password', $password);
	}

	public function get_smtp_protocol()
	{
		return $this->get_property('smtp_protocol');
	}

	public function set_smtp_protocol($protocol)
	{
		$this->set_property('smtp_protocol', $protocol);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		//TODO Add mail signature lang
		return array(
			'use_smtp' => false,
			'smtp_host' => '',
			'smtp_port' => 25,
			'smtp_login' => '',
			'smtp_password' => '',
			'smtp_protocol' => 'none',
			'default_mail_sender' => '',
			'administrators_mails' => array(),
			'mail_signature' => ''
		);
	}
	
	/**
	 * @return SMTPConfiguration
	 */
	public function to_smtp_config()
	{
		if ($this->is_smtp_enabled())
		{
			$config = new SMTPConfiguration();
			$config->set_host($this->get_smtp_host());
			$config->set_port($this->get_smtp_port());
			$config->set_login($this->get_smtp_login());
			$config->set_password($this->get_smtp_password());
			$config->set_auth_mode($this->get_smtp_protocol());
			return $config;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Returns the configuration.
	 * @return MailServiceConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'mail-service');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'mail-service');
	}
}
?>