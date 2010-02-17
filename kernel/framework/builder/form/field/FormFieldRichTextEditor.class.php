<?php
/*##################################################
 *                          FormFieldRichTextEditor.class.php
 *                            -------------------
 *   begin                : January 09, 2010
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a rich text editor.
 * @package builder
 * @subpackage form
 */
class FormFieldRichTextEditor extends FormFieldMultiLineTextEditor
{
	/**
	 * @var ContentFormattingFactory
	 */
	private $formatter = null;

	/**
	 * @desc Constructs a rich text edit field.
	 * In addition to the parameters of the FormMultiLineEdit ones, there is the formatter which
	 * is an instance of the ContentFormattingFactory which ensures the formatting. The default value
	 * corresponds to the user's default configuration and will be the one to use 99% of the time.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options options
	 * @param FormFieldConstraint[] $constraints The constraints
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		$this->formatter = ContentFormattingMetaFactory::get_default_factory();
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the textarea.
	 */
	public function display()
	{
		$template = parent::display();

		$this->assign_editor($template);

		return $template;
	}

	private function assign_editor(Template $template)
	{
		$editor = $this->formatter->get_editor();
		$editor->set_identifier($this->get_html_id());

		$template->assign_vars(array(
			'C_EDITOR_ENABLED' => true,
			'EDITOR' => $editor->display(),
			'VALUE' => $this->get_raw_value(),
			'PREVIEW_BUTTON' => $this->get_preview_button_code()
		));
	}

	private function get_preview_button_code()
	{
		global $LANG;
		return '<input type="button" value="' . $LANG['preview'] . '" onclick="XMLHttpRequest_preview();" class="submit" />';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_value()
	{
		return $this->parse_value($this->get_raw_value());
	}

	private function parse_value($value)
	{
		$parser = $this->formatter->get_parser();
		$parser->set_content($value);
		$parser->parse();
		return $parser->get_content();
	}
	
	private function get_raw_value()
	{
		return parent::get_value();
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_value($value)
	{
		$this->set_raw_value($this->unparse_value($value));
	}
	
	private function set_raw_value($value)
	{
		parent::set_value($value);
	}

	private function unparse_value($value)
	{
		$unparser = $this->formatter->get_unparser();
		$unparser->set_content($value);
		$unparser->parse();
		return $unparser->get_content();
	}

	public function get_onblur_validations()
	{
		// This is a patch for TinyMCE, it shouldn't be there but it's difficult not to process it here
		if ($this->formatter instanceof TinyMCEParserFactory)
		{
			return 'tinyMCE.triggerSave(); ' . parent::get_onblur_validations();
		}
		else
		{
			return parent::get_onblur_validations();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->set_raw_value($request->get_value($this->get_html_id()));
		}
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'formatter':
					if ($value instanceof ContentFormattingFactory)
					{
						$this->formatter = $value;
						unset($field_options['formatter']);
					}
					else
					{
						throw new FormBuilderException('The value associated to the formatter attribute must be an instance of the ContentFormattingFactory class');
					}
					break;
			}
		}
		parent::compute_options($field_options);
	}
}

?>