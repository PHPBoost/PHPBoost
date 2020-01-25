<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminFilesConfigController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $file_upload_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('authorized_extensions')->set_selected_options($this->file_upload_config->get_authorized_extensions());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminFilesDisplayResponse($tpl, LangLoader::get_message('files_config', 'main'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin');
		$this->file_upload_config = FileUploadConfig::load();
	}

	private function build_form()
	{
		$extensions = $this->get_extensions_list();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('files-config', LangLoader::get_message('files_config', 'main'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldDecimalNumberEditor('size_limit', $this->lang['size_limit'], NumberHelper::round($this->file_upload_config->get_maximum_size_upload() / 1024, 2),
			array(
				'min' => 0, 'step' => 0.05, 'required' => true,
				'description' => $this->lang['size_limit_explain']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('bandwidth_protect', $this->lang['bandwidth_protect'], $this->file_upload_config->get_enable_bandwidth_protect(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['bandwidth_protect_explain']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_file_thumbnail', $this->lang['files_thumb'], $this->file_upload_config->get_display_file_thumbnail(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['files_thumb_explain']
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('extend_extensions', $this->lang['extend_extensions'],  $extensions['extend_extensions'],
			array('description' => $this->lang['extend_extensions_explain'])
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('authorized_extensions', $this->lang['auth_extensions'], $this->file_upload_config->get_authorized_extensions(), $extensions['authorized_extensions_select'],
			array('class' => 'top-field', 'size' => 12)
		));

		$auth_settings = new AuthorizationsSettings(array(new VisitorDisabledActionAuthorization($this->lang['auth_files'], FileUploadConfig::AUTH_FILES_BIT)));
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
				//Suppression de tous les caractères interdits dans les extensions
				$extension = str_replace('-', '', Url::encode_rewrite($extension));

				if ($extension != '' && !isset($authorized_extensions[$extension]) && $extension != 'php')
				{
					array_push($authorized_extensions, $extension);
				}
			}
		}

		$this->file_upload_config->set_authorized_extensions($authorized_extensions);

		FileUploadConfig::save();

		//Régénération du .htaccess et du nginx.conf.
		HtaccessFileCache::regenerate();
		HtaccessFileCache::regenerate();
	}

	private function get_extensions_list()
	{
		$authorized_extensions = $this->file_upload_config->get_authorized_extensions();
		$array_extensions_type = array(
			$this->lang['files_image'] => array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'tif', 'svg', 'ico', 'nef'),
			$this->lang['files_archives'] => array('rar', 'zip', 'gz', '7z'),
			$this->lang['files_text'] => array('txt', 'doc', 'docx', 'pdf', 'ppt', 'xls', 'odt', 'odp', 'ods', 'odg', 'odc', 'odf', 'odb', 'xcf', 'csv'),
			$this->lang['files_media'] => array('flv', 'mp3', 'ogg', 'mpg', 'mov', 'swf', 'wav', 'wmv', 'midi', 'mng', 'qt', 'mp4', 'mkv'),
			$this->lang['files_misc'] => array('ttf', 'tex', 'rtf', 'psd', 'iso')
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
