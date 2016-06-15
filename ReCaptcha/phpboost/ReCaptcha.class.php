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
	const DEFAULT_SITE_KEY = '6LdrD9YSAAAAAAfoC2bRYQ-xT0PT7AAjWb6kc8cd';
	const DEFAULT_SECRET_KEY = '6LdrD9YSAAAAAJloXTYNKbaMgBaLPvfMoWncKWkc';
	
	public static $_signupUrl = "https://www.google.com/recaptcha/admin";
	private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";
	
	private $recaptcha_response;
	private $recaptcha_error;
	private $lang;
	private $config;
	private $is_recaptcha_v2;
	
	public function __construct()
	{
		require_once(PATH_TO_ROOT . '/ReCaptcha/lib/recaptchalib.php');
		$this->config = ReCaptchaConfig::load();
		$this->is_recaptcha_v2 = $this->config->get_site_key() && $this->config->get_secret_key();
	}
	
	public function get_name()
	{
		return 'ReCaptcha';
	}
	
	public function is_available()
	{
		return Url::check_url_validity('www.google.com');
	}
	
	public function is_valid()
	{
		if ($this->is_recaptcha_v2)
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
				
				if ($response['success'] == true)
					return true;
				else
					$this->recaptcha_error = isset($response['error-codes'][0]) ? $response['error-codes'][0] : null;
			}
		}
		else
		{
			$this->recaptcha_response = recaptcha_check_answer(self::DEFAULT_SECRET_KEY, $_SERVER["REMOTE_ADDR"], AppContext::get_request()->get_postvalue('recaptcha_challenge_field', ''), AppContext::get_request()->get_postvalue($this->get_html_id(), ''));
			
			return $this->recaptcha_response->is_valid;
		}
		return false;
	}
	
	public function display()
	{
		$tpl = new FileTemplate('ReCaptcha/ReCaptcha.tpl');
		$this->lang = LangLoader::get('common', 'ReCaptcha');
		$tpl->add_lang($this->lang);
		$tpl->put_all(array(
			'C_RECAPTCHA_V2' => $this->is_recaptcha_v2,
			'SITE_KEY' => $this->is_recaptcha_v2 ? $this->config->get_site_key() : self::DEFAULT_SITE_KEY,
			'HTML_ID' => $this->get_html_id()
		));
		return $tpl->render();
	}
	
	public function get_error()
	{
		if ($this->recaptcha_error !== null)
		{
			return $this->recaptcha_error;
		}
		else if ($this->recaptcha_response !== null)
		{
			return $this->recaptcha_response->error;
		}
	}
}
?>