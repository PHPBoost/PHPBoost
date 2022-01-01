<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 6.0 - 2020 02 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldThumbnail extends AbstractFormField
{
	protected $default_picture = '';

	const NONE  = 'none';
	const DEFAULT_VALUE = 'default';
	const CUSTOM = 'custom';

	public function __construct($id, $label = '', $value = self::NONE, $default_picture = '', array $field_options = array(), array $constraints = array())
	{
		$this->default_picture = self::get_default_thumbnail_url($default_picture);
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get_all_langs();

		$view = new FileTemplate('framework/builder/form/FormFieldThumbnail.tpl');
		$view->add_lang($lang);

		$this->assign_common_template_variables($template);
		$this->assign_common_template_variables($view);

		$real_file_url = $this->get_value() == self::DEFAULT_VALUE ? $this->default_picture : $this->get_value();
		$file_type = new FileType(new File($real_file_url));

		$view->put_all(array(
			'C_DEFAULT_THUMBNAIL_URL' => $this->default_picture,
			'C_PREVIEW_HIDDEN'        => !$this->get_value() || !$file_type->is_picture(),
			'C_AUTH_UPLOAD'           => FileUploadConfig::load()->is_authorized_to_access_interface_files(),
			'FILE_PATH'               => Url::to_relative($real_file_url),
			'PREVIEW_FILE_PATH'       => Url::to_rel($real_file_url),
			'C_NONE_CHECKED'          => $this->get_value() == '',
			'C_DEFAULT_CHECKED'       => $this->get_value() && ($this->get_value() == self::DEFAULT_VALUE || $this->get_value() == $this->default_picture),
			'C_CUSTOM_CHECKED'        => $this->get_value() && $this->get_value() != self::DEFAULT_VALUE && $this->get_value() != $this->default_picture,
			'DEFAULT_THUMBNAIL_URL'   => Url::to_rel($this->default_picture)
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $view->render()
		));

		return $template;
	}

	public static function get_default_thumbnail_url($thumbnail_url)
	{
		$file_name = basename($thumbnail_url);
		$module_id = Environment::get_running_module_name();
		$parent_theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_configuration()->get_parent_theme();

		$module_url = PATH_TO_ROOT . '/' . $module_id . '/templates/images/' . $file_name;
		$module_file = new File($module_url);
		$module_theme_url = PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/modules/' . $module_id . '/images/' . $file_name;
		$module_theme_file = new File($module_theme_url);
		$module_parent_theme_url = PATH_TO_ROOT . '/templates/' . $parent_theme . '/modules/' . $module_id . '/images/' . $file_name;
		$module_parent_theme_file = new File($module_parent_theme_url);
		$theme_file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $file_name);

		if ($file_name && $module_theme_file->exists())
			return $module_theme_url;
		else if ($file_name && $module_parent_theme_file->exists())
			return $module_parent_theme_url;
		else if ($file_name && $theme_file->exists())
			return '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $file_name;
		else if ($file_name && $module_file->exists())
			return $module_url;
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
