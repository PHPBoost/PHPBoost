<?php
/*##################################################
 *                      AbstractAdminFormPageController.class.php
 *                            -------------------
 *   begin                : August 7 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

abstract class AbstractAdminFormPageController extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	protected $success_message;

	protected function __construct($success_message)
	{
		$this->success_message = $success_message;
	}

	public function execute(HTTPRequest $request)
	{
		$template = new StringTemplate('# IF C_SUCCESS # <div class="success" id="success_message">{SUCCESS_MESSAGE}</div> # ENDIF #
		<script type="text/javascript"><!--
		window.setTimeout(function() { Effect.Fade("success_message"); }, 5000);
		--></script>
		# INCLUDE form #');
		$this->create_form();
		if ($this->has_been_submitted())
		{
			$this->handle_submit();
			$template->put_all(array(
				'SUCCESS_MESSAGE' => $this->success_message,
				'C_SUCCESS' => $this->form
			));
		}
		$template->put('form', $this->form->display());
		return $this->generate_response($template);
	}

	protected abstract function create_form();

	private function has_been_submitted()
	{
		return $this->submit_button->has_been_submitted() && $this->form->validate();
	}

	protected abstract function handle_submit();

	protected abstract function generate_response(View $template);

	protected function get_form()
	{
		return $this->form;
	}

	protected function set_form(HTMLForm $form)
	{
		$this->form = $form;
	}

	protected function set_submit_button(FormButtonSubmit $submit_button)
	{
		$this->submit_button = $submit_button;
	}
}
?>