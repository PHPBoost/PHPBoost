<?php
/**
 * This class represents a rich text editor.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 01 09
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldRichTextEditor extends FormFieldMultiLineTextEditor
{
	private mixed $formatter = null;
	private mixed $reset_value = null;

	/**
	 * Constructs a rich text edit field.
	 * In addition to the parameters of the FormMultiLineEdit ones, there is the formatter which
	 * is an instance of the ContentFormattingFactory which ensures the formatting. The default value
	 * corresponds to the user's default configuration and will be the one to use 99% of the time.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value
	 * @param array $field_options options
	 * @param FormFieldConstraint[] $constraints The constraints
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		$this->formatter = AppContext::get_content_formatting_service()->get_default_factory();
		parent::__construct($id, $label, '', $field_options, $constraints);

		$this->set_value($value);
	}

	public function display(): Template
	{
		$template = parent::display();

		$this->assign_editor($template);

		return $template;
	}

	private function assign_editor(Template $template): void
	{
		$editor = $this->formatter->get_editor();
		$editor->set_identifier($this->get_html_id());

		$template->put_all([
			'C_EDITOR_ENABLED'       => true,
			'C_RESET_BUTTON_ENABLED' => $this->reset_value !== null,

			'EDITOR'         => $editor->display(),
			'EDITOR_NAME'    => TextHelper::strtolower($this->formatter->get_name()),
			'VALUE'          => $this->get_raw_value(),
			'PREVIEW_BUTTON' => $this->get_preview_button_code(),
			'RESET_BUTTON'   => $this->get_reset_button_code()
		]);
	}

	private function get_preview_button_code(): mixed
	{
		$template = new FileTemplate('framework/builder/form/button/FormButtonPreview.tpl');

		$template->put('HTML_ID', $this->get_html_id());

		return $template->render();
	}

	private function get_reset_button_code(): mixed
	{
		$template = new FileTemplate('framework/builder/form/button/FormButtonReset.tpl');

		$template->put_all([
			'C_ONCLICK_FUNCTION' => true,

			'HTML_ID' => $this->get_html_id(),
			'CLASS'   => 'small',

			'L_RESET'         => LangLoader::get_message('form.reset', 'form-lang'),
			'ONCLICK_ACTIONS' => (AppContext::get_current_user()->get_editor() == 'TinyMCE' ? 'setTinyMceContent(' . TextHelper::to_js_string($this->unparse_value($this->reset_value)) . ');' :
				'HTMLForms.getField("' . $this->get_id() . '").setValue(' . TextHelper::to_js_string($this->unparse_value($this->reset_value)) . ');')
		]);

		return $template->render();
	}

	public function get_value(): mixed
	{
		return $this->parse_value($this->get_raw_value());
	}

	private function parse_value(mixed $value): mixed
	{
		$parser = $this->formatter->get_parser();
		$parser->set_content($value);
		$parser->parse();
		return $parser->get_content();
	}

	private function get_raw_value(): mixed
	{
		return parent::get_value();
	}

	public function set_value(mixed $value): void
	{
		$this->set_raw_value($this->unparse_value($value));
	}

	private function set_raw_value(mixed $value): void
	{
		parent::set_value($value);
	}

	private function unparse_value(mixed $value): mixed
	{
		$unparser = $this->formatter->get_unparser();
		$unparser->set_content($value);
		$unparser->parse();
		return $unparser->get_content();
	}

	public function retrieve_value(): void
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->set_raw_value($request->get_string($this->get_html_id()));
		}
	}

	protected function compute_options(array &$field_options): void
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'formatter':
					if ($value instanceof ContentFormattingExtensionPoint)
					{
						$this->formatter = $value;
						unset($field_options['formatter']);
					}
					else
					{
						throw new FormBuilderException('The value associated to the formatter attribute must be an instance of the ContentFormattingFactory class');
					}
					break;
				case 'reset_value':
					$this->reset_value = $value;
					unset($field_options['reset_value']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
}
?>
