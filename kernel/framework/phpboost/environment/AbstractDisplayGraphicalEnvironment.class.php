<?php
/**
 * This class contains the content of the writing pad which is on the home page
 * of the administration panel.
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 08 26
 * @since       PHPBoost 3.0 - 2009 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Maxence CAUDERLIER <mxkoder@phpboost.com>
*/

abstract class AbstractDisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	/**
	 * @var SEOMetaData
	 */
	private $seo_meta_data = null;

	private $css_cache_config;

	private $location_id = '';

	/**
	 * @var CookieBarConfig
	 */
	protected $cookiebar_config;

	/**
	 * @var MaintenanceConfig
	 */
	protected $maintenance_config;

	public function __construct()
	{
		$this->seo_meta_data = new SEOMetaData();
		$this->css_cache_config = CSSCacheConfig::load();
	}

	
	/**
	 * Return if cookie bar is enabled or not
	 * @return bool true if enabled, false otherwise
	 */
	protected function get_cookiebar_enabled():bool
	{
		return isset($this->cookiebar_config) ? $this->cookiebar_config->is_cookiebar_enabled() && !$this->maintenance_config->is_under_maintenance() : false;
	}

	
	/**
	 * Return HTML code for CSS files regarding cache is enabled or not
	 * @return string
	 */
	protected function get_modules_css_files_html_code()
	{
		
		$css_files = array_merge(ModulesCssFilesService::get_css_files_always_displayed(), ModulesCssFilesService::get_css_files_running_module_displayed());
		if ($this->css_cache_config->is_enabled())
		{
			$html_code = '<link rel="stylesheet" href="' . CSSCacheManager::get_css_path($css_files) . '" type="text/css" media="screen, print" />';
		}
		else
		{
			$html_code = '';
			foreach ($css_files as $file)
			{
				$html_code .= '<link rel="stylesheet" href="' . Url::to_rel($file) . '" type="text/css" media="screen, print" />';
			}
		}
		return $html_code;
	}

	/**
	 * Get all JS files as a string from modules to display at top_js in HTML in <script> tags
	 * @return string
	 */
	protected function get_top_js_files_html_code():string
	{
		$js_top = new FileTemplate('js_top.tpl');
		$js_top_files = $this->get_js_files_from_html($js_top->render());
		$js_files = array_merge($js_top_files, ModulesJsFilesService::get_top_js_files_always_displayed(), ModulesJsFilesService::get_top_js_files_running_module_displayed());
		return $this->get_js_files_html_code($js_files);
	}

	/**
	 * Get all JS files as a string from modules to display at bottom_js in HTML in <script> tags
	 * @return string
	 */
	protected function get_bottom_js_files_html_code():string
	{
		$js_bottom = new FileTemplate('js_bottom.tpl');
		$js_bottom->put('C_COOKIEBAR_ENABLED', $this->get_cookiebar_enabled());
		$js_bottom_files = $this->get_js_files_from_html($js_bottom->render());
		$js_files = array_merge($js_bottom_files, ModulesJsFilesService::get_bottom_js_files_always_displayed(), ModulesJsFilesService::get_bottom_js_files_running_module_displayed());
		return $this->get_js_files_html_code($js_files);
	}

	/**
	 * Return HTML code for JS files regarding cache is enabled or not
	 * @param array $js_files
	 * @return string
	 */
	private function get_js_files_html_code($js_files):string
	{
		if ($this->css_cache_config->is_enabled())
		{
			$js_manager = JSCacheManager::get_js_path($js_files);
			if ($js_manager === false)
			{
				// No JS to add
				return '';
			}
			$html_code = '<script src="'. $js_manager->get_script_cache_location() .'"></script>';
			foreach ($js_manager->get_ignored_scripts() as $file)
			{
				$html_code .= "<script src=\"$file\"></script>";
			}
		}
		else
		{
			$html_code = '';
			foreach ($js_files as $file_url)
			{
				$html_code .= '<script src="'. Url::to_rel($file_url) .'"></script>';
			}
		}
		return $html_code;
	}

    /**
     * Get all JS files as an array from an HTML code
	 * scripts can be relative or absolute
	 * eg : <script src="/path/templates/__default__/plugins/@global.js"></script>
	 * or : <script src="https://plop.com./script.js"></script>
     * @param string $html
     * @return string[]
     */
	public function get_js_files_from_html(string $html):array
    {
        $files = [];
        $html = explode('<script', $html);
        foreach ($html as $script)
        {
            if (strpos($script, 'src="') !== false)
            {
                $tmp_file = substr($script, strpos($script, 'src="') + 5, strpos($script, '">') - strpos($script, 'src="') - 5);
                $files[] = (filter_var($tmp_file, FILTER_VALIDATE_URL)) ? $tmp_file : str_replace(TPL_PATH_TO_ROOT, '', $tmp_file);
            }
        }
        return $files;
    }

	public function get_location_id()
	{
		return $this->location_id;
	}

	public function set_location_id($location_id)
	{
		$this->location_id = $location_id;
	}

	public function get_seo_meta_data()
	{
		return $this->seo_meta_data;
	}

	public function set_seo_meta_data(SEOMetaData $seo_meta_data)
	{
		$this->seo_meta_data = $seo_meta_data;
	}

	public function get_page_title()
	{
		return $this->get_seo_meta_data()->get_title();
	}

	public function set_page_title($title, $section = '', $page = 1)
	{
		$this->get_seo_meta_data()->set_title($title, $section, $page);

		defined('TITLE') or define('TITLE', $this->get_page_title());

		self::set_page_localization($this->get_page_title(), $this->get_location_id());
	}

	protected function retrieve_kernel_message()
	{
		$kernel_message = [
			'message' => '',
			'message_type' => MessageHelper::SUCCESS,
			'message_duration' => 5
		];

		$request = AppContext::get_request();
		if ($request->has_cookieparameter('message'))
		{
			$kernel_message['message'] = $request->get_cookie('message');
			$kernel_message['message_type'] = $request->has_cookieparameter('message_type') ? $request->get_cookie('message_type') : $kernel_message['message_type'];
			$kernel_message['message_duration'] = $request->has_cookieparameter('message_duration') ? $request->get_cookie('message_duration') : $kernel_message['message_duration'];

			$response = AppContext::get_response();
			$response->delete_cookie('message');
			$response->delete_cookie('message_type');
			$response->delete_cookie('message_duration');
		}

		return $kernel_message;
	}

	public function display_kernel_message(View $template)
	{
		$this->display_install_or_update_folders_kernel_message($template);

		$kernel_message = $this->retrieve_kernel_message();
		if (!empty($kernel_message['message']))
			$template->put('KERNEL_MESSAGE', MessageHelper::display($kernel_message['message'], $kernel_message['message_type'], $kernel_message['message_duration']));
	}

	private function is_folder_deleted($folder_name)
	{
		$folder = new Folder(PATH_TO_ROOT . '/' . $folder_name);
		return !$folder->exists();
	}

	private function delete_folder($folder_name)
	{
		$folder = new Folder(PATH_TO_ROOT . '/' . $folder_name);
		if ($folder->exists())
			$folder->delete();
	}

	private function display_install_or_update_folders_kernel_message(View $template)
	{
		if (AppContext::get_current_user()->is_admin() && !AppContext::get_request()->get_is_localhost())
		{
			$display_message_install = !$this->is_folder_deleted('install');
			$display_message_update = !$this->is_folder_deleted('update');

			if ($display_message_install || $display_message_update)
			{
				$form = new HTMLForm('kerner_message_form', '', false);

				$submit_button = new FormButtonSubmit(LangLoader::get_message('common.delete', 'common-lang'), 'delete_install', '', 'bgc warning delete-install-button');
				$form->add_button($submit_button);

				if ($submit_button->has_been_submited() && $form->validate())
				{
					$this->delete_folder('install');
					$this->delete_folder('update');
					$display_message_install = $display_message_update = false;
				}
			}

			if ($display_message_install || $display_message_update)
			{
				$message = $display_message_install && $display_message_update ? LangLoader::get_message('warning.delete.install.and.update.folders', 'warning-lang') : StringVars::replace_vars(LangLoader::get_message('warning.delete.install.or.update.folders', 'warning-lang'), ['folder' => $display_message_install ? 'install' : 'update']);
				$template->put('KERNEL_MESSAGE', MessageHelper::display($message . ' ' . $form->display()->render(), MessageHelper::WARNING));
			}
		}
	}
}
?>
