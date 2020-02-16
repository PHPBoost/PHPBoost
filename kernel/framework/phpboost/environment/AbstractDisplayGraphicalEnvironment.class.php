<?php
/**
 * This class contains the content of the writing pad which is on the home page
 * of the administration panel.
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 16
 * @since       PHPBoost 3.0 - 2009 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

 abstract class AbstractDisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	/**
	 * @var SEOMetaData
	 */
	private $seo_meta_data = null;

	private $location_id = '';

	public function __construct()
	{
		$this->seo_meta_data = new SEOMetaData();
	}

	protected function get_modules_css_files_html_code()
	{
		$css_cache_config = CSSCacheConfig::load();
		$css_files = array_merge(ModulesCssFilesService::get_css_files_always_displayed(), ModulesCssFilesService::get_css_files_running_module_displayed());
		if ($css_cache_config->is_enabled())
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
		$kernel_message = array(
			'message' => '',
			'message_type' => MessageHelper::SUCCESS,
			'message_duration' => 5
		);

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

				$submit_button = new FormButtonSubmit(LangLoader::get_message('delete', 'common'), 'delete_install', '', 'bgc warning delete-install-button');
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
				$message = ($display_message_install && $display_message_update ? LangLoader::get_message('message.delete_install_and_update_folders', 'status-messages-common') : StringVars::replace_vars(LangLoader::get_message('message.delete_install_or_update_folders', 'status-messages-common'), array('folder' => $display_message_install ? 'install' : 'update')));
				$template->put('KERNEL_MESSAGE', MessageHelper::display($message . ' ' . $form->display()->render(), MessageHelper::WARNING));
			}
		}
	}
}
?>
