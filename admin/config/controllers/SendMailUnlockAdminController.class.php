<?php
/*##################################################
 *                       SendMailUnlockAdminController.class.php
 *                            -------------------
 *   begin                : August 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class SendMailUnlockAdminController extends AdminController 
{
	private $unlock_admin_clean;
	private $lang;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->save_unlock_code();
		Debug::dump($this->unlock_admin_clean);
		
		if ($this->send_mail() == true)
		{
			$controller = new UserErrorController($this->lang['advanced-config.unlock-administration'], $this->lang['advanced-config.code_sent_success'], 1);
			DispatchManager::redirect($controller);
		}
		else 
		{
			$controller = new UserErrorController($this->lang['advanced-config.unlock-administration'], $this->lang['advanced-config.code_sent_fail'], 4);
			DispatchManager::redirect($controller);
		}
	}
	
	private function init()
	{
		$this->load_lang();
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}
	
	private function send_mail()
	{        
        //Préparation du mail
		$subject = $this->lang['advanced-config.unlock-code.title'] . ' - ' . GeneralConfig::load()->get_site_name();
		$content = StringVars::replace_vars($this->lang['advanced-config.unlock-code.content'], 
			array('unlock_code' => $unlock_admin_clean, 'host_dir' => (HOST . DIR), 'signature' => MailServiceConfig::load()->get_mail_signature())
		);
		
		$mail = new Mail();
        $admin_mails = MailServiceConfig::load()->get_administrators_mails();
        foreach ($admin_mails as $mail_address)
        {
        	$mail->add_recipient($mail_address);
        }
		
        $mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), GeneralConfig::load()->get_site_name());
        $mail->set_subject($subject);
        $mail->set_content($content);
        return AppContext::get_mail_service()->try_to_send($mail);
	}
	
	private function save_unlock_code() 
	{
		//Génération du code de déverrouillage de l'administration
		$this->unlock_admin_clean = KeyGenerator::generate_key(18);
        $unlock_admin = KeyGenerator::string_hash($this->unlock_admin_clean);
        
        $general_config = GeneralConfig::load();
        $general_config->set_admin_unlocking_key($unlock_admin);
        GeneralConfig::save();
	}
}
?>