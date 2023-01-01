<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 26
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ReCaptcha extends Captcha
{
	public static $_signupUrl = "https://www.google.com/recaptcha/admin";
	private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";

	private $config;

	public function __construct()
	{
		$this->config = ReCaptchaConfig::load();
	}

	public function get_name()
	{
		return 'ReCaptcha';
	}

	public static function display_config_form_fields(FormFieldset $fieldset, $locale = '')
	{
		return AdminReCaptchaConfig::get_form_fields($fieldset, $locale);
	}

	public static function save_config(HTMLForm $form)
	{
		AdminReCaptchaConfig::save_config($form);
	}

	public function is_available()
	{
		return Url::check_url_validity('www.google.com') && $this->config->get_site_key() && $this->config->get_secret_key();
	}

	public function is_valid()
	{
		$request = AppContext::get_request();

		if ($request->has_postparameter('g-recaptcha-response'))
		{
			$validation_url = self::$_siteVerifyUrl . "?secret=" . $this->config->get_secret_key() . "&response=" . $request->get_postvalue('g-recaptcha-response', '');

			$server_configuration = new ServerConfiguration();
			if ($server_configuration->has_curl_library())
			{
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $validation_url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_TIMEOUT, 15);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
				$response = curl_exec($curl);
			}
			else
			{
				$response = @file_get_contents($validation_url);
			}

			$response = json_decode($response, true);

			if (is_array($response) && $response['success'] == true)
				return true;
			else
				$this->recaptcha_error = isset($response['error-codes'][0]) ? $response['error-codes'][0] : null;
		}
		return false;
	}

	public function display()
	{
		$view = new FileTemplate('ReCaptcha/ReCaptcha.tpl');
		$view->add_lang(LangLoader::get_all_langs('ReCaptcha'));
		$view->put_all(array(
			'C_INVISIBLE' => $this->config->is_invisible_mode_enabled(),
			'SITE_KEY'    => $this->config->get_site_key(),
			'FORM_ID'     => $this->get_form_id(),
			'HTML_ID'     => $this->get_html_id()
		));
		return $view->render();
	}

	public function is_visible()
	{
		return !$this->config->is_invisible_mode_enabled();
	}
}
?>
