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
	const PUBLIC_KEY = '6LdrD9YSAAAAAAfoC2bRYQ-xT0PT7AAjWb6kc8cd';
	const PRIVATE_KEY = '6LdrD9YSAAAAAJloXTYNKbaMgBaLPvfMoWncKWkc';

	private $recaptcha_response;
	private $lang;
	
	public function __construct()
	{
		require_once(PATH_TO_ROOT . '/ReCaptcha/lib/recaptchalib.php');	
	}
	
	public function get_name()
	{
		return 'ReCaptcha';
	}
		
	public function is_available()
	{
		return @fsockopen(RECAPTCHA_VERIFY_SERVER, 80) !== false;
	}
	
	public function is_valid()
	{
		$this->recaptcha_response = recaptcha_check_answer(self::PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], AppContext::get_request()->get_postvalue('recaptcha_challenge_field', ''), AppContext::get_request()->get_postvalue($this->get_html_id(), ''));
		
		return $this->recaptcha_response->is_valid;
	}
	
	public function display()
	{
		$tpl = new FileTemplate('ReCaptcha/ReCaptcha.tpl');
		$this->lang = LangLoader::get('common', 'ReCaptcha');
		$tpl->add_lang($this->lang);
		$tpl->put_all(array(
			'PUBLIC_KEY' => self::PUBLIC_KEY,
			'HTML_ID' => $this->get_html_id()
		));
		return $tpl->render();
	}
	
	public function get_error()
	{
		if ($this->recaptcha_response !== null)
		{
			return $this->recaptcha_response->error;
		}
	}
}
?>