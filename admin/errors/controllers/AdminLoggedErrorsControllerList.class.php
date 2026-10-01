<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 01
 * @since       PHPBoost 4.0 - 2014 01 05
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLoggedErrorsControllerList extends DefaultAdminController
{
	const NUMBER_ITEMS_PER_PAGE = 15;

    private $alert_form;
    private $alert_button;

	public function execute(HTTPRequestCustom $request)
	{
        $this->config = AdminLoggedErrorsConfig::load();
		$this->build_alert_form();
		$this->build_table();

        $this->view->put_all([
            'ALERT_FORM' => $this->alert_form->display(),
        ]);

        if ($this->alert_button->has_been_submited() && $this->alert_form->validate())
        {
            $this->alert_form->get_field_by_id('alert_level')->set_hidden(!$this->config->get_send_alerts());
            $this->alert_form->get_field_by_id('email_list')->set_hidden(!$this->config->get_send_alerts());
            $this->alert_form->get_field_by_id('delay')->set_hidden(!$this->config->get_send_alerts());
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
            $this->save();
        }

		return new AdminErrorsDisplayResponse($this->view, $this->lang['admin.logged.errors']);
	}

    protected function get_template_string_content()
    {
        return '
            # INCLUDE ALERT_FORM #
            # INCLUDE MESSAGE_HELPER #
            # INCLUDE FORM #
            # INCLUDE TABLE #
        ';
    }

	private function build_table()
	{
		$errors = AdminLoggedErrorsService::get_errors_list();

		$types = [
			'question' => 'warning.unknown',
			'notice'   => 'warning.notice',
			'warning'  => 'warning.warning',
			'error'    => 'warning.fatal'
		];

		$table_model = new HTMLTableModel('error-list', [
			new HTMLTableColumn($this->lang['common.date'], '', ['css_class' => 'col-medium']),
			new HTMLTableColumn($this->lang['common.description'])
		], new HTMLTableSortingRule(''), self::NUMBER_ITEMS_PER_PAGE);

		$table = new HTMLTable($table_model, $this->lang, 'admin.logged.errors.list');
		$table->hide_multiple_delete();

		$table_model->set_caption($this->lang['admin.logged.errors.list']);
		$table_model->set_footer_css_class('footer-error-list');

		$br = new BrHTMLElement();

		$results = [];
		foreach ($errors as $error)
		{
			$error_class = new SpanHTMLElement($this->lang[$types[$error['errclass']]] . ' : ', [], 'text-strong');
			$error_stacktrace = new SpanHTMLElement(strip_tags($error['errstacktrace'], '<br>'), [], 'text-italic');

			$error_message = $error_class->display() . strip_tags($error['errmsg'], '<br>') . $br->display() . $br->display() . $br->display() . $error_stacktrace->display();

			$results[] = new HTMLTableRow([
				new HTMLTableRowCell($error['errdate']),
				new HTMLTableRowCell(new DivHTMLElement($error_message, [], 'message-helper bgc ' . $error['errclass']))
			]);
		}
		$results_number = count($results);
		$table->set_rows($results_number, $results);

		if ($results_number)
		{
			$this->view->put_all([
				'FORM'      => $this->build_form()->display(),
				'TABLE'     => $table->display()
			]);
		}
		else {
			$this->view->put_all([
                'MESSAGE_HELPER' => MessageHelper::display($this->lang['common.no.item.now'], MessageHelper::SUCCESS, 0, true),
			]);
        }

		return $table->get_page_number();
	}

	private function build_form()
	{
		$form = new HTMLForm(self::class, AdminErrorsUrlBuilder::clear_logged_errors()->rel(), false);

		$fieldset = new FormFieldsetHTML('clear_errors', '');
		$form->add_fieldset($fieldset);

		$submit_button = new FormButtonSubmit($this->lang['admin.clear.list'], 'clear', '', 'submit', $this->lang['admin.warning.clear.errors']);
		$form->add_button($submit_button);

		return $form;
	}

	private function build_alert_form()
	{
		$alert_form = new HTMLForm(self::class);

		$fieldset = new FormFieldsetHTML('send_mail', '');
		$alert_form->add_fieldset($fieldset);

        $fieldset->add_field( new FormFieldCheckbox('send_alerts', $this->lang['configuration.errors.alerts'], $this->config->get_send_alerts(),
            [
                'class' => 'custom-checkbox top-field',
                'description' => $this->lang['configuration.errors.alerts.clue'],
                'events' => ['click' => '
                    if (HTMLForms.getField("send_alerts").getValue()) {
                        HTMLForms.getField("alert_level").enable();
                        HTMLForms.getField("email_list").enable();
                        HTMLForms.getField("delay").enable();
                    } else {
                        HTMLForms.getField("alert_level").disable();
                        HTMLForms.getField("email_list").disable();
                        HTMLForms.getField("delay").disable();
                    }'
                ]
            ]
        ));

        $fieldset->add_field( new FormFieldMultipleCheckbox('alert_level', $this->lang['configuration.errors.level'], TextHelper::deserialize($this->config->get_alert_level()),
            [
                new FormFieldMultipleCheckboxOption('question', $this->lang['warning.unknown']),
                new FormFieldMultipleCheckboxOption('notice', $this->lang['warning.notice']),
                new FormFieldMultipleCheckboxOption('warning', $this->lang['warning.warning']),
                new FormFieldMultipleCheckboxOption('error', $this->lang['warning.fatal']),
            ],
            [
                'class' => 'top-field',
                'description' => $this->lang['configuration.errors.level.clue'],
                'hidden' => !$this->config->get_send_alerts()
            ]
        ));

		$fieldset->add_field(new FormFieldNumberEditor('delay', $this->lang['configuration.errors.delay'], $this->config->get_delay(),
			[
                'class' => 'top-field', 'min' => 0, 'max' => 60, 'step' => 5, 'required' => true,
                'description' => $this->lang['configuration.errors.delay.clue'],
                'hidden' => !$this->config->get_send_alerts()
            ]
		));

        $fieldset->add_field(new FormFieldMailEditor('email_list', $this->lang['configuration.errors.emails'], $this->config->get_email_list(),
            [
                'class' => 'top-field', 'multiple' => true,
                'description' => $this->lang['configuration.errors.emails.clue'],
                'hidden' => !$this->config->get_send_alerts()
            ]
        ));

        $this->alert_button = new FormButtonDefaultSubmit();
        $alert_form->add_button($this->alert_button);
        $alert_form->add_button(new FormButtonReset());

        $this->alert_form = $alert_form;
	}

    private function save()
    {
        $this->config->set_send_alerts($this->alert_form->get_value('send_alerts'));
        if ($this->alert_form->get_value('send_alerts')) {
            $alerts = [];
            foreach($this->alert_form->get_value('alert_level') as $id => $value)
            {
                $alerts[] = $value;
            }
            $this->config->set_alert_level(TextHelper::serialize($alerts));
            $this->config->set_delay($this->alert_form->get_value('delay'));
            $this->config->set_email_list($this->alert_form->get_value('email_list'));
        }

        $this->config->save();
    }
}
?>
