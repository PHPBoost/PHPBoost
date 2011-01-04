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
class FormFieldsetButtons extends AbstractFormFieldset
{
	/**
	 * @var Template
	 */
	private $template;
	/**
	 * @var FormButton[]
	 */
	private $buttons;

	public function __construct($id, $options = array())
	{
		parent::__construct($id, $options);
	}

	public function add_field(FormField $form_field)
	{
		throw new UnsupportedOperationException("FormFieldsetButtons supports only buttons and not fields");
	}

	public function validate()
	{
		return true;
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

	protected function get_default_template()
	{
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