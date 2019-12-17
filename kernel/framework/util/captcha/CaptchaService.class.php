<?php
/**
 * @package     Util
 * @subpackage  Captcha
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2012 09 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class CaptchaService
{
	private $default_factory;

	public function get_default_factory()
	{
		if ($this->default_factory === null)
		{
			$this->default_factory = $this->create_factory($this->get_default_captcha());
		}
		return $this->default_factory;
	}

	public function create_factory($identifier = '')
	{
		$captcha = $this->get_existing_captcha($identifier);
		return CaptchaProvidersService::create_factory($captcha);
	}

	public function get_default_captcha()
	{
		return ContentManagementConfig::load()->get_used_captcha_module();
	}

	public function is_available()
	{
		return $this->get_default_factory()->is_available();
	}

	public function is_valid()
	{
		return $this->get_default_factory()->is_valid();
	}

	public function display()
	{
		return $this->get_default_factory()->display();
	}

	private function get_existing_captcha($captcha)
	{
		if (in_array($captcha, self::get_captchas_identifier()))
		{
			return $captcha;
		}
		else
		{
			return $this->get_default_captcha();
		}
	}

	public function get_captchas_identifier()
	{
		return array_keys(CaptchaProvidersService::get_captchas());
	}

	public function get_available_captchas()
	{
		$available_captchas = array();
		foreach (CaptchaProvidersService::get_captchas() as $id => $provider)
		{
			if ($provider->is_available())
				$available_captchas[$id] = $provider->get_name();
		}
		return $available_captchas;
	}

	/**
	 * @param string $id_module
	 */
	public function uninstall_captcha($id_module)
	{
		$captchas = $this->get_available_captchas();

		if (in_array($id_module, $captchas))
		{
			if (count($captchas) > 1)
			{
				$default_captcha = $this->get_default_captcha();
				if ($default_captcha === $id_module)
					return LangLoader::get_message('captcha.is_default', 'status-messages-common');
			}
			else
				return LangLoader::get_message('captcha.last_installed', 'status-messages-common');
		}
	}
}
?>
