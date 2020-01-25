<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmileysFormController extends AdminController
{
	private $lang;
	private $view;
	private $upload_form;
	private $smiley_form;
	private $upload_submit_button;
	private $smiley_submit_button;

	private $smileys_path;
	private $smiley;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		if (!$this->smiley['idsmiley'])
		{
			$this->build_upload_form();

			if ($this->upload_submit_button->has_been_submited() && $this->upload_form->validate())
			{
				$this->upload_smiley();
			}

			$this->view->put('UPLOAD_FORM', $this->upload_form->display());
		}

		$this->build_smiley_form();

		if ($this->smiley_submit_button->has_been_submited() && $this->smiley_form->validate())
		{
			$this->save_smiley();
			$this->smiley_form->get_field_by_id('code_smiley')->set_value('');
			$this->smiley_form->get_field_by_id('url_smiley')->set_options($this->generate_available_smileys_pictures_list());
		}

		$this->view->put('SMILEY_FORM', $this->smiley_form->display());

		return new AdminSmileysDisplayResponse($this->view, !$this->smiley['idsmiley'] ? $this->lang['add_smiley'] : $this->lang['edit_smiley']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE UPLOAD_FORM # # INCLUDE SMILEY_FORM #');
		$this->view->add_lang($this->lang);
		$this->smileys_path = PATH_TO_ROOT . '/images/smileys/';
		$this->get_smiley();
	}

	private function build_upload_form()
	{
		$form = new HTMLForm('upload_smiley', '', false);

		$fieldset = new FormFieldsetHTML('upload', $this->lang['upload_smiley']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFilePicker('file', $this->lang['explain_upload_img'],
			array(
				'class' => 'full-field', 'multiple' => true,
				'authorized_extensions' => implode('|', array_map('preg_quote', FileUploadConfig::load()->get_authorized_picture_extensions()))
			)
		));

		$this->upload_submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->upload_submit_button);
		$this->upload_form = $form;
	}

	private function upload_smiley()
	{
		$folder_phpboost_smileys = $this->smileys_path;

		if (!is_writable($folder_phpboost_smileys))
		{
			$is_writable = @chmod($folder_phpboost_smileys, 0777);
		}
		else
		{
			$is_writable = true;
		}

		if ($is_writable)
		{
			$uploaded_file = $this->upload_form->get_value('file');
			if ($uploaded_file !== null)
			{
				$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();

				if (empty($authorized_pictures_extensions))
				{
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_upload_invalid_format', 'errors'), MessageHelper::NOTICE));
				}

				$upload = new Upload($this->smileys_path);

				if ($upload->file('upload_smiley_file', '`([a-z0-9()_-])+\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`iu'))
				{
					// TODO : manage the smileys archive (possibility to upload a zip + checkbox if you want to create each smiley directly with: name_of_smiley as code)
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 5));
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message($upload->get_error(), 'errors'), MessageHelper::NOTICE));
				}
			}
			else
			{
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('upload.error', 'status-messages-common'), MessageHelper::NOTICE));
			}
		}
		else
		{
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('e_upload_failed_unwritable', 'errors'), MessageHelper::WARNING));
		}
	}

	private function build_smiley_form()
	{
		$form = new HTMLForm('smiley', '', false);

		$fieldset = new FormFieldsetHTML('smiley', !$this->smiley['idsmiley'] ? $this->lang['add_smiley'] : $this->lang['edit_smiley']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('code_smiley', $this->lang['smiley_code'], $this->smiley['code_smiley'],
			array('maxlength' => 50, 'required' => true)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('url_smiley', $this->lang['smiley_available'], $this->smiley['url_smiley'],
			$this->generate_available_smileys_pictures_list(),
			array(
				'events' => array('change' => '
					if (HTMLForms.getField("url_smiley").getValue() != \'\') {
						jQuery(\'#smiley-img\').attr(\'src\', \'' . Url::to_rel('/images/smileys/') . '\' + HTMLForms.getField("url_smiley").getValue());
						HTMLForms.getField("img_smiley").enable();
					} else {
						HTMLForms.getField("img_smiley").disable();
					}'
				)
			)
		));

		$img_smiley = new ImgHTMLElement($this->smiley['idsmiley'] ? Url::to_rel('/images/smileys/') . $this->smiley['url_smiley'] : '',
			array(
				'id' => 'smiley-img',
				'alt' => $this->smiley['code_smiley'],
				'aria-label' => $this->smiley['code_smiley']
			)
		);

		$fieldset->add_field(new FormFieldFree('img_smiley', LangLoader::get_message('form.picture.preview', 'common'), $img_smiley->display(),
			array('hidden' => !$this->smiley['idsmiley'])
		));

		$this->smiley_submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->smiley_submit_button);
		$this->smiley_form = $form;
	}

	private function save_smiley()
	{
		$code_smiley = $this->smiley_form->get_value('code_smiley');
		$url_smiley = $this->smiley_form->get_value('url_smiley')->get_raw_value();

		if (!empty($code_smiley) && !empty($url_smiley))
		{
			if (!$this->smiley['idsmiley'])
			{
				$check_smiley = PersistenceContext::get_querier()->count(DB_TABLE_SMILEYS, 'WHERE code_smiley=:code_smiley', array('code_smiley' => $code_smiley));

				if (empty($check_smiley))
				{
					PersistenceContext::get_querier()->insert(DB_TABLE_SMILEYS, array('code_smiley' => $code_smiley, 'url_smiley' => $url_smiley));

				 	// Regenerate smileys cache
					SmileysCache::invalidate();

					$this->view->put('MSG', MessageHelper::display($this->lang['smiley_add_success'], MessageHelper::SUCCESS));
				}
				else
				{
					$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('element.already_exists', 'status-messages-common'), MessageHelper::ERROR));
				}
			}
			else
			{
				PersistenceContext::get_querier()->update(DB_TABLE_SMILEYS, array('url_smiley' => $url_smiley, 'code_smiley' => $code_smiley), 'WHERE idsmiley = :id', array('id' => $this->smiley['idsmiley']));

				// Regenerate smileys cache
				SmileysCache::invalidate();

				AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management());
			}
		}
	}

	private function generate_available_smileys_pictures_list()
	{
		$smileys_array = $options = array();
		$folder_phpboost_smileys = new Folder($this->smileys_path);
		foreach ($folder_phpboost_smileys->get_files('`\.(png|jpg|bmp|gif)$`iu') as $smileys)
			$smileys_array[] = $smileys->get_name();

		if (!$this->smiley['idsmiley'])
		{
			$result = PersistenceContext::get_querier()->select("SELECT url_smiley
			FROM " . PREFIX . "smileys");
			while ($row = $result->fetch())
			{
				// Search keys correponding to the database table ones.
				$key = array_search($row['url_smiley'], $smileys_array);
				if ($key !== false)
					unset($smileys_array[$key]); // Delete this keys from the table
			}
			$result->dispose();

			$options = array(new FormFieldSelectChoiceOption('--', ''));
		}

		foreach ($smileys_array as $smiley)
			$options[] = new FormFieldSelectChoiceOption($smiley, $smiley);

		return $options;
	}

	private function get_smiley()
	{
		if ($this->smiley === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->smiley = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SMILEYS, array('idsmiley', 'code_smiley', 'url_smiley'), 'WHERE idsmiley = :id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->smiley = array('idsmiley' => 0, 'code_smiley' => '', 'url_smiley' => '');
			}
		}
		return $this->smiley;
	}
}
?>
