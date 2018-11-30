<?php 
/*##################################################
 *                            ReCaptcha.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
	
	public static function display_config_form_fields(FormFieldset $fieldset)
	{
		return AdminReCaptchaConfig::get_form_fields($fieldset);
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
		$tpl = new FileTemplate('ReCaptcha/ReCaptcha.tpl');
		$tpl->add_lang(LangLoader::get('common', 'ReCaptcha'));
		$tpl->put_all(array(
			'C_INVISIBLE' => $this->config->is_invisible_mode_enabled(),
			'SITE_KEY' => $this->config->get_site_key(),
			'FORM_ID' => $this->get_form_id(),
			'HTML_ID' => $this->get_html_id()
		));
		return $tpl->render();
	}
	
	public function is_visible()
	{
		return !$this->config->is_invisible_mode_enabled();
	}
}
?>