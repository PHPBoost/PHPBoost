<?php
/*##################################################
 *                         AdminLoggedErrorsControllerList.class.php
 *                            -------------------
 *   begin                : January 05 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class AdminLoggedErrorsControllerList extends AdminController
{
	private $view;
	private $lang;
	
	const NUMBER_ITEMS_PER_PAGE = 15;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_table();
		
		return new AdminErrorsDisplayResponse($this->view, $this->lang['logged_errors']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM # # INCLUDE table #');
	}

	private function build_table()
	{
		$errors = $this->get_errors_list();
		
		$types = array(
			'question' => 'error.unknow',
			'notice' => 'error.notice',
			'warning' => 'error.warning',
			'error' => 'error.fatal' 
		);
		
		$table_model = new HTMLTableModel('table', array(
			new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), '', 'col-large'),
			new HTMLTableColumn(LangLoader::get_message('description', 'main'))
		), new HTMLTableSortingRule(''), self::NUMBER_ITEMS_PER_PAGE);
		
		$table = new HTMLTable($table_model, 'table-fixed error-list');
		
		$table_model->set_caption($this->lang['logged_errors_list']);
		
		$br = new BrHTMLElement();
		
		$results = array();
		foreach ($errors as $error)
		{
			$error_class = new SpanHTMLElement(LangLoader::get_message($types[$error['errclass']], 'status-messages-common') . ' : ', array(), 'text-strong');
			$error_stacktrace = new SpanHTMLElement(strip_tags($error['errstacktrace'], '<br>'), array(), 'text-italic');
			
			$error_message = $error_class->display() . strip_tags($error['errmsg'], '<br>') . $br->display() . $br->display() . $br->display() . $error_stacktrace->display();
			
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($error['errdate']),
				new HTMLTableRowCell(new DivHTMLElement($error_message, array(), $error['errclass']))
			));
		}
		$results_number = count($results);
		$table->set_rows($results_number, $results);
		
		if ($results_number)
		{
			$this->view->put_all(array(
				'FORM' => $this->build_form()->display(),
				'table' => $table->display()
			));
		}
		else
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('no_item_now', 'common'), MessageHelper::SUCCESS, 0, true));
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__, AdminErrorsUrlBuilder::clear_logged_errors()->rel(), false);
		
		$fieldset = new FormFieldsetHTML('clear_errors', $this->lang['clear_list']);
		$form->add_fieldset($fieldset);

		$submit_button = new FormButtonSubmit($this->lang['clear_list'], 'clear', '', 'submit', $this->lang['logged_errors_clear_confirmation']);
		$form->add_button($submit_button);
		
		return $form;
	}

	private function get_errors_list()
	{
		$array_errinfo = array();
		$file_path = PATH_TO_ROOT . '/cache/error.log';
		
		if (is_file($file_path) && is_readable($file_path)) //Fichier accessible en lecture
		{
			$handle = @fopen($file_path, 'r');
			if ($handle) 
			{
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
			}
		}
		
		return array_reverse($array_errinfo); //Tri en sens inverse car enregistrement à la suite dans le fichier de log
	}
}
?>
