<?php
/**
 * @package     Util
 * @subpackage  Captcha
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2012 09 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CaptchaProvidersService
{
	public static function create_factory(string $identifier): ?Captcha
	{
		return self::get_captcha($identifier);
	}

	public static function get_captcha(string $identifier): ?Captcha
	{
		$captchas = self::get_captchas();
		if (array_key_exists($identifier, $captchas))
		{
			return $captchas[$identifier];
		}
		return null;
	}

	public static function get_captchas(): array
	{
		$captchas = [];
		foreach (self::get_extensions_point() as $id => $provider)
		{
			$captchas[$id] = $provider;
		}
		return $captchas;
	}

	public static function get_extensions_point(): array
	{
		return AppContext::get_extension_provider_service()->get_extension_point(Captcha::EXTENSION_POINT);
	}
}
?>
