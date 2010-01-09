<?php
/*##################################################
 *                          FormRichTextEdit.class.php
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
class FormRichTextEdit extends FormMultiLineTextEdit
{
	/**
	 * @var ContentFormattingFactory
	 */
	private $formatter = null;

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
				'VALUE' => $this->value,
				'PREVIEW_BUTTON' => $this->get_preview_button_code()
		));
	}
	
	private function get_preview_button_code()
	{
		global $LANG;
		return '<input type="button" value="' . $LANG['preview'] . '" onclick="XMLHttpRequest_preview();" class="submit" />';
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/AbstractFormField#get_value()
	 */
	public function get_value()
	{
		return $this->parse_value(parent::get_value());
	}

	private function parse_value($value)
	{
		$parser = $this->formatter->get_parser();
		$parser->set_content($value);
		$parser->parse();
		return $parser->get_content(ContentFormattingParser::DONT_ADD_SLASHES);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/AbstractFormField#set_value($value)
	 */
	public function set_value($value)
	{
		parent::set_value($this->unparse_value($value));
	}

	private function unparse_value($value)
	{
		$unparser = $this->formatter->get_unparser();
		$unparser->set_content($value);
		$unparser->parse();
		return $unparser->get_content(ContentFormattingParser::DONT_ADD_SLASHES);
	}
	
	protected function get_onblur_action()
	{
		// This is a patch for TinyMCE, it shouldn't be there but it's difficult not to process it here
		if ($this->formatter instanceof TinyMCEParserFactory)
		{
			return 'tinyMCE.triggerSave(); ' . parent::get_onblur_action();	
		}
		else
		{
			return parent::get_onblur_action();
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