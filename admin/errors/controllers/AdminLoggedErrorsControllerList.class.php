<?php
/*##################################################
 *                         AdminLoggedErrorsControllerList.class.php
 *                            -------------------
 *   begin                : January 05 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class AdminLoggedErrorsControllerList extends AdminController
{
	private $view;
	private $lang;
	
	private $number_errors_per_page = 15;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return new AdminErrorsDisplayResponse($this->view, $this->lang['logged_errors']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		
		$this->view = new FileTemplate('admin/errors/AdminLoggedErrorsControllerList.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$file_path = PATH_TO_ROOT .'/cache/error.log';
		
		if (is_file($file_path) && is_readable($file_path)) //Fichier accessible en lecture
		{
			$handle = @fopen($file_path, 'r');
			if ($handle) 
			{
				$array_errinfo = array();
				$i = 1;
				while (!feof($handle)) 
				{
					$buffer = fgets($handle);
					switch ($i)
					{
						case 1:
						$errinfo['errdate'] = $buffer;
						break;
						case 2:
						$errinfo['errno'] = $buffer;
						break;
						case 3:
						$errinfo['errmsg'] = $buffer;
						break;
						case 4:
						$errinfo['errstacktrace'] = $buffer;
						$i = 0;
						$array_errinfo[] = array(
							'errclass' => ErrorHandler::get_errno_class($errinfo['errno']), 
							'errmsg' => $errinfo['errmsg'], 
							'errstacktrace'=> $errinfo['errstacktrace'], 
							'errdate' => $errinfo['errdate']
						);
						break;
					}
					$i++;
				}
				@fclose($handle);
				
				$types = array(
					'question' => 'e_unknow',
					'notice' => 'e_notice',
					'warning' => 'e_warning',
					'error' => 'e_fatal' 
				);
				
				//Tri en sens inverse car enregistrement à la suite dans le fichier de log
				krsort($array_errinfo);
				
				$page = AppContext::get_request()->get_getint('page', 1);
				$pagination = $this->get_pagination($page, count($array_errinfo));
				
				$nb_errors = $i = 0;
				foreach ($array_errinfo as $key => $errinfo)
				{
					if ($i >= (($page - 1) * $this->number_errors_per_page) && $i < ($page * $this->number_errors_per_page))
					{
						$this->view->assign_block_vars('errors', array(
							'DATE' => $errinfo['errdate'],
							'CLASS' => $errinfo['errclass'],
							'ERROR_TYPE' => LangLoader::get_message($types[$errinfo['errclass']], 'errors'),
							'ERROR_MESSAGE' => strip_tags($errinfo['errmsg'], '<br>'),
							'ERROR_STACKTRACE' => strip_tags($errinfo['errstacktrace'], '<br>')
						));
						$nb_errors++;
					}
					$i++;
					
					if ($nb_errors == $this->number_errors_per_page)
					{
						break;
					}
				}
				
				$this->view->put_all(array(
					'C_ERRORS' => $nb_errors,
					'C_PAGINATION' => $pagination->has_several_pages(),
					'PAGINATION' => $pagination->display(),
					'U_CLEAR_LOGGED_ERRORS' => AdminErrorsUrlBuilder::clear_logged_errors()->rel()
				));
			}
		}
	}
	
	private function get_pagination($page, $errors_number)
	{
		$pagination = new ModulePagination($page, $errors_number, $this->number_errors_per_page);
		$pagination->set_url(AdminErrorsUrlBuilder::logged_errors('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>