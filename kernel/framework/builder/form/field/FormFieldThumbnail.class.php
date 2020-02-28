<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 28
 * @since       PHPBoost 5.3 - 2020 02 27
*/

class FormFieldThumbnail extends AbstractFormField
{
	protected $default_url = '';

	const NONE  = 'none';
	const DEFAULT_VALUE = 'default';
	const CUSTOM = 'custom';

	public function __construct($id, $label = '', $value = self::NONE, $default_url = '', array $field_options = array(), array $constraints = array())
	{
		$this->default_url = self::get_default_thumbnail_url($default_url);
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get('common');

		$tpl = new FileTemplate('framework/builder/form/FormFieldThumbnail.tpl');
		$tpl->add_lang($lang);

		$this->assign_common_template_variables($template);
		$this->assign_common_template_variables($tpl);

		$real_file_url = $this->get_value() == self::DEFAULT_VALUE ? $this->default_url : $this->get_value();
		$file_type = new FileType(new File($real_file_url));

		$tpl->put_all(array(
			'C_DEFAULT_URL'     => $this->default_url,
			'C_PREVIEW_HIDDEN'  => !$this->get_value() || !$file_type->is_picture(),
			'C_AUTH_UPLOAD'     => FileUploadConfig::load()->is_authorized_to_access_interface_files(),
			'FILE_PATH'         => Url::to_rel($real_file_url),
			'C_NONE_CHECKED'    => $this->get_value() == '',
			'C_DEFAULT_CHECKED' => $this->get_value() && ($this->get_value() == self::DEFAULT_VALUE || $this->get_value() == $this->default_url),
			'C_CUSTOM_CHECKED'  => $this->get_value() && $this->get_value() != self::DEFAULT_VALUE && $this->get_value() != $this->default_url,
			'DEFAULT_URL'       => Url::to_rel($this->default_url),
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public static function get_default_thumbnail_url($thumbnail_url)
	{
		$file_name = basename($thumbnail_url);
		$file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $file_name);
		if ($file_name && $file->exists())
			return '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $file_name;
		else
			return $thumbnail_url;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$value = '';
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$radio_value = $request->get_value($this->get_html_id());
			switch ($radio_value)
			{
				case self::NONE:
					$value = '';
					break;
				case self::CUSTOM:
					$value = $request->get_value($this->get_html_id() . '_custom_file');
					break;
				default :
					$value = self::DEFAULT_VALUE;
			}
		}
		$this->set_value($value);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
