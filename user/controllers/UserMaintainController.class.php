<?php
/*##################################################
 *                      UserMaintainController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserMaintainController extends AbstractController
{
	private $tpl;
	private $lang;
	private $main_lang;
	private $maintain_config;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->init_vars_template();
		$this->init_delay();
		
		return $this->build_reponse();
	}
	
	private function init()
	{
		$this->tpl = new FileTemplate('user/UserMaintainController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->main_lang = LangLoader::get('main');
		$this->tpl->add_lang($this->lang);
		$this->maintain_config = MaintenanceConfig::load();
	}
	
	private function init_vars_template()
	{
		$this->tpl->put_all(array(
			'L_MAINTAIN' => FormatingHelper::second_parse($this->maintain_config->get_message()),
			'L_CONNECT' => $this->lang['connect'],
			'U_CONNECT' => UserUrlBuilder::connect()->absolute(),
			'L_MAINTAIN_DELAY' => $this->main_lang['maintain_delay'],
			'L_LOADING' => $this->main_lang['loading'],
			'L_DAYS' => $this->main_lang['days'],
			'L_HOURS' => $this->main_lang['hours'],
			'L_MIN' => $this->main_lang['minutes'],
			'L_SEC' => $this->main_lang['seconds']
		));
	}
	
	private function init_delay()
	{
		$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
		$array_delay = array(0 => $this->main_lang['unspecified'], 1 => '', 2 => '1 ' . $this->main_lang['minute'], 3 => '5 ' . $this->main_lang['minutes'], 4 => '15 ' . $this->main_lang['minutes'], 5 => '30 ' . $this->main_lang['minutes'], 6 => '1 ' . $this->main_lang['hour'], 7 => '2 ' . $this->main_lang['hours'], 8 => '1 ' . $this->main_lang['day'], 9 => '2 ' . $this->main_lang['days'], 10 => '1 ' . $this->main_lang['week']);
		
		if (!$this->maintain_config->is_unlimited_maintenance())
		{
			$key = 0;
			$current_time = time();
			$end_timestamp = $this->maintain_config->get_end_date()->get_timestamp();
			for ($i = 10; $i >= 0; $i--)
			{					
				$delay = ($end_timestamp - $current_time) - $array_time[$i];		
				if ($delay >= $array_time[$i]) 
				{	
					$key = $i;
					break;
				}
			}
			
			//Calcul du format de la date
			$seconds = gmdate_format('s', $end_timestamp, TIMEZONE_SITE);
			$array_release = array(
			gmdate_format('Y', $end_timestamp, TIMEZONE_SITE), (gmdate_format('n', $end_timestamp, TIMEZONE_SITE) - 1), gmdate_format('j', $end_timestamp, TIMEZONE_SITE), 
			gmdate_format('G', $end_timestamp, TIMEZONE_SITE), gmdate_format('i', $end_timestamp, TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds );
		
			$seconds = gmdate_format('s', time(), TIMEZONE_SITE);
		    $array_now = array(
		    gmdate_format('Y', time(), TIMEZONE_SITE), (gmdate_format('n', time(), TIMEZONE_SITE) - 1), gmdate_format('j', time(), TIMEZONE_SITE),
		    gmdate_format('G', time(), TIMEZONE_SITE), gmdate_format('i', time(), TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
		}	
		else
		{	
			$key = -1;
			$array_release = array('0', '0', '0', '0', '0', '0');
			$array_now = array('0', '0', '0', '0', '0', '0');
		}
		
		$this->tpl->put_all(array(
			'MAINTAIN_NOW_FORMAT' => implode(',', $array_now),
			'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release)
		));
		
		if ($this->maintain_config->get_display_duration() && !$this->maintain_config->is_unlimited_maintenance())
		{
			$this->tpl->put_all(array(
				'C_DISPLAY_DELAY' => true,
				'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',
				
			));
		}
	}
	
	private function build_reponse()
	{
		$response = new UserSiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['maintain']);
		return $response;
	}
	
	public function get_right_controller_regarding_authorizations()
    {
      	if (!MaintenanceConfig::load()->is_under_maintenance())
      	{
       		AppContext::get_response()->redirect(Environment::get_home_page());
      	}
      	return $this;
    }
}
?>