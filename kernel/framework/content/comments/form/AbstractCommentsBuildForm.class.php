<?php
/**
 * @package     Content
 * @subpackage  Comments\form
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 3.0 - 2011 09 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractCommentsBuildForm
{
	private HTMLForm $form;
	private FormButtonSubmit $submit_button;

	public function display(): mixed
	{
		return $this->form->display();
	}

	protected abstract function create_form(): mixed;

	protected abstract function handle_submit(): void;

	protected function has_been_submited(): bool
	{
		return $this->submit_button->has_been_submited() && $this->form->validate();
	}

	protected function get_form(): mixed
	{
		return $this->form;
	}

	protected function set_form(HTMLForm $form): void
	{
		$this->form = $form;
	}

	protected function set_submit_button(FormButtonSubmit $submit_button): void
	{
		$this->submit_button = $submit_button;
	}
}
?>
