<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 11
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminFilesConfigController extends DefaultAdminController
{
	private $file_upload_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('authorized_extensions')->set_selected_options($this->file_upload_config->get_authorized_extensions());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminFilesDisplayResponse($this->view, $this->lang['upload.files.config']);
	}

	private function init()
	{
		$this->file_upload_config = FileUploadConfig::load();
	}

	private function build_form()
	{
		$extensions = $this->get_extensions_list();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('files-config', $this->lang['upload.files.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldDecimalNumberEditor('size_limit', $this->lang['upload.size.limit'], NumberHelper::round($this->file_upload_config->get_maximum_size_upload() / 1024, 2),
			array(
				'class' => 'third-field',
				'min' => 0, 'step' => 0.05, 'required' => true,
				'description' => $this->lang['upload.size.limit.clue']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('bandwidth_protect', $this->lang['upload.bandwidth.protect'], $this->file_upload_config->get_enable_bandwidth_protect(),
			array(
				'class' => 'custom-checkbox third-field',
				'description' => $this->lang['upload.bandwidth.protect.clue']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_file_thumbnail', $this->lang['upload.display.thumbnails'], $this->file_upload_config->get_display_file_thumbnail(),
			array(
				'class' => 'custom-checkbox third-field',
				'description' => $this->lang['upload.display.thumbnails.clue']
			)
		));

		$fieldset->add_field(new FormFieldSpacer('extensions', ''));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('authorized_extensions', $this->lang['upload.authorized.extensions'], $this->file_upload_config->get_authorized_extensions(), $extensions['authorized_extensions_select'],
			array('class' => 'half-field top-field', 'size' => 12)
		));

		$fieldset->add_field(new FormFieldTextEditor('extend_extensions', $this->lang['upload.authorized.extensions.more'],  $extensions['extend_extensions'],
			array(
				'class' => 'half-field top-field',
				'description' => $this->lang['upload.authorized.extensions.clue']
			)
		));

		$auth_settings = new AuthorizationsSettings(array(new VisitorDisabledActionAuthorization($this->lang['upload.files.manager.authorizations'], FileUploadConfig::AUTH_FILES_BIT)));
		$auth_settings->build_from_auth_array($this->file_upload_config->get_authorization_enable_interface_files());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->file_upload_config->set_authorization_enable_interface_files($this->form->get_value('authorizations')->build_auth_array());
		$this->file_upload_config->set_maximum_size_upload($this->form->get_value('size_limit') * 1024);

		if ($this->form->get_value('bandwidth_protect'))
			$this->file_upload_config->set_enable_bandwidth_protect(true);
		else
			$this->file_upload_config->set_enable_bandwidth_protect(false);

		$this->file_upload_config->set_display_file_thumbnail($this->form->get_value('display_file_thumbnail'));

		$authorized_extensions = $this->form->get_value('authorized_extensions');
		$authorized_extensions = array();
		foreach ($this->form->get_value('authorized_extensions') as $field => $option)
		{
			$authorized_extensions[] = $option->get_raw_value();
		}
		$extend_extensions = preg_split('`, ?`', trim($this->form->get_value('extend_extensions')));

		if (is_array($extend_extensions))
		{
			foreach ($extend_extensions as $extension)
			{
				// Deleting all forbidden characters in extensions
				$extension = str_replace('-', '', Url::encode_rewrite($extension));

				if ($extension != '' && !isset($authorized_extensions[$extension]) && $extension != 'php')
				{
					array_push($authorized_extensions, $extension);
				}
			}
		}

		$this->file_upload_config->set_authorized_extensions($authorized_extensions);

		FileUploadConfig::save();

		// Regeneration of .htaccess and nginx.conf
		HtaccessFileCache::regenerate();
		NginxFileCache::regenerate();

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['upload.files.config'], 'url' => AdminFilesUrlBuilder::configuration()->rel()));
	}

	private function get_extensions_list()
	{
		$authorized_extensions = $this->file_upload_config->get_authorized_extensions();
		$array_extensions_type = array(
			$this->lang['upload.option.image'] => array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'webp', 'tif', 'svg', 'ico', 'nef'),
			$this->lang['upload.option.archives'] => array('rar', 'zip', 'gz', '7z'),
			$this->lang['upload.option.text'] => array('txt', 'doc', 'docx', 'pdf', 'ppt', 'xls', 'odt', 'odp', 'ods', 'odg', 'odc', 'odf', 'odb', 'xcf', 'csv'),
			$this->lang['upload.option.media'] => array('mp3', 'ogg', 'webm', 'mpg', 'mov', 'wav', 'wmv', 'midi', 'mng', 'qt', 'mp4', 'mkv'),
			$this->lang['upload.option.miscellaneous'] => array('ttf', 'tex', 'rtf', 'psd', 'iso')
		);

		$select_options = array();
		foreach ($array_extensions_type as $file_type => $array_extensions)
		{
			$select_options[] = new FormFieldSelectChoiceGroupOption($file_type, array());
			foreach ($array_extensions as $key => $extension)
			{
				$extension_key = array_search($extension, $authorized_extensions);

				$select_options[] = new FormFieldSelectChoiceOption($extension, $extension);

				if (isset($authorized_extensions[$extension_key]))
					unset($authorized_extensions[$extension_key]);
			}
		}

		return array('authorized_extensions_select' => $select_options, 'extend_extensions' => implode(', ', $authorized_extensions));
	}
}
?>
