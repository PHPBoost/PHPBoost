<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 4.0 - 2014 01 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLoggedErrorsControllerList extends DefaultAdminController
{
	const NUMBER_ITEMS_PER_PAGE = 15;

	public function execute(HTTPRequestCustom $request)
	{
		$current_page = $this->build_table();

		return new AdminErrorsDisplayResponse($this->view, $this->lang['admin.logged.errors'], $current_page);
	}

	private function build_table()
	{
		$errors = $this->get_errors_list();

		$types = array(
			'question' => 'warning.unknown',
			'notice' => 'warning.notice',
			'warning' => 'warning.warning',
			'error' => 'warning.fatal'
		);

		$table_model = new HTMLTableModel('error-list', array(
			new HTMLTableColumn($this->lang['common.date'], '', array('css_class' => 'col-medium')),
			new HTMLTableColumn($this->lang['common.description'])
		), new HTMLTableSortingRule(''), self::NUMBER_ITEMS_PER_PAGE);

		$table = new HTMLTable($table_model, $this->lang, 'admin.logged.errors.list');
		$table->hide_multiple_delete();

		$table_model->set_caption($this->lang['admin.logged.errors.list']);
		$table_model->set_footer_css_class('footer-error-list');

		$br = new BrHTMLElement();

		$results = array();
		foreach ($errors as $error)
		{
			$error_class = new SpanHTMLElement($this->lang[$types[$error['errclass']]] . ' : ', array(), 'text-strong');
			$error_stacktrace = new SpanHTMLElement(strip_tags($error['errstacktrace'], '<br>'), array(), 'text-italic');

			$error_message = $error_class->display() . strip_tags($error['errmsg'], '<br>') . $br->display() . $br->display() . $br->display() . $error_stacktrace->display();

			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($error['errdate']),
				new HTMLTableRowCell(new DivHTMLElement($error_message, array(), 'message-helper bgc ' . $error['errclass']))
			));
		}
		$results_number = count($results);
		$table->set_rows($results_number, $results);

		if ($results_number)
		{
			$this->view = new StringTemplate('# INCLUDE FORM ## INCLUDE TABLE #');
			$this->view->put_all(array(
				'FORM' => $this->build_form()->display(),
				'TABLE' => $table->display()
			));
		}
		else
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['common.no.item.now'], MessageHelper::SUCCESS, 0, true));

		return $table->get_page_number();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__, AdminErrorsUrlBuilder::clear_logged_errors()->rel(), false);

		$fieldset = new FormFieldsetHTML('clear_errors', '');
		$form->add_fieldset($fieldset);

		$submit_button = new FormButtonSubmit($this->lang['admin.clear.list'], 'clear', '', 'submit', $this->lang['admin.warning.clear.errors']);
		$form->add_button($submit_button);

		return $form;
	}

	private function get_errors_list()
	{
		$array_errinfo = array();
		$file_path = PATH_TO_ROOT . '/cache/error.log';

		if (is_file($file_path) && is_readable($file_path)) // Readable file
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

		return array_reverse($array_errinfo); // Sorting in reverse order because recording in the log file
	}
}
?>
