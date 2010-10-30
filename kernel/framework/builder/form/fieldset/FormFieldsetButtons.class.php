<?php
/*##################################################
 *                       FormFieldsetButtons.class.php
 *                            -------------------
 *   begin                : October 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FormFieldsetButtons implements FormFieldset
{
	private $id;
	private $form_id;
	private $disabled;
	/**
	 * @var Template
	 */
	private $template;
	/**
	 * @var FormButton[]
	 */
	private $buttons;

	public function __construct($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function add_field(FormField $form_field)
	{
		throw new UnsupportedOperationException("FormFieldsetButtons supports only buttons and not fields");
	}

	public function set_form_id($prefix)
	{
		$this->form_id = $prefix;
	}

	function get_html_id()
	{
		return $this->form_id . '_' . $this->id;
	}

	public function validate()
	{
	}

	public function disable()
	{
		$this->disabled = true;
	}

	public function enable()
	{
		$this->disabled = false;
	}

	public function is_disabled()
	{
		return $this->disabled;
	}

	public function display()
	{
		$template = $this->get_template_to_use();
		$template->put_all(array(
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
		));
		$loop_vars = array();
		foreach ($this->buttons as $button)
		{
			$loop_vars[] = array(
				'BUTTON' => $button->display()
			);
		}
		$template->put('buttons', $loop_vars);
		return $template;
	}

	private function get_template_to_use()
	{
		if ($this->template != null)
		{
			return $this->template;
		}
		return new StringTemplate('<fieldset id="${escape(ID)}" # IF C_DISABLED # style="display:none;" # ENDIF # class="fieldset_submit">
		# START buttons #
			# INCLUDE buttons.BUTTON #
		# END buttons #
	</fieldset>');
	}

	public function get_onsubmit_validations()
	{
		return array();
	}

	public function get_validation_error_messages()
	{
		return array();
	}

	public function has_field($field_id)
	{
		return false;
	}

	public function get_field($field_id)
	{
		return null;
	}

	function get_fields()
	{
		return array();
	}

	public function set_template(Template $template)
	{
		$this->template = $template;
	}

	public function add_button(FormButton $button)
	{
		$this->buttons[] = $button;
	}
}

?>