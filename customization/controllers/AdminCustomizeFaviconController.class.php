<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2011 08 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeFaviconController extends AdminModuleController
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

	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$favicon = $this->form->get_value('favicon', null);

			if ($favicon !== null)
			{
				$file_type = new FileType(new File($favicon->get_name()));
				if ($file_type->is_picture())
				{
					$this->save($favicon);
					$favicon_file = new File(PATH_TO_ROOT . $this->config->get_favicon_path());
					$picture = '<img src="' . Url::to_rel($favicon_file->get_path()) . '" alt="' . $this->lang['customization.favicon.current'] . '" />';
					$this->form->get_field_by_id('current_favicon')->set_value($picture);
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
				}
				else
				{
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('form.invalid_picture', 'status-messages-common'), MessageHelper::ERROR, 4));
				}
			}
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('common', 'customization');
	}

	private function load_config()
	{
		$this->config = CustomizationConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('customize-favicon', $this->lang['customization.favicon']);
		$form->add_fieldset($fieldset);

		if ($this->config->get_favicon_path() == null || $this->config->get_favicon_path() == '')
		{
			$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $this->lang['customization.favicon.current.null'],
				array('class' => 'third-field')
			));
		}
		else
		{
			if ($this->config->favicon_exists())
			{
				$favicon_file = new File(PATH_TO_ROOT . $this->config->get_favicon_path());
				$picture = '<img src="' . Url::to_rel($favicon_file->get_path()) . '" alt="' . $this->lang['customization.favicon.current'] . '" />';
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], $picture,
					array('class' => 'third-field')
				));
			}
			else
			{
				$fieldset->add_field(new FormFieldFree('current_favicon', $this->lang['customization.favicon.current'], '<span class="text-strong error">' . $this->lang['customization.favicon.current.erased'] . '</span>',
					array('class' => 'third-field')
				));
			}
		}

		$fieldset->add_field(new FormFieldFilePicker('favicon', $this->lang['customization.favicon.current.change'],
			array('class' => 'third-field'),
			array(new FormFieldConstraintPictureFile())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save($favicon)
	{
		$save_destination = new File(PATH_TO_ROOT . '/' . $favicon->get_name());
		$favicon->save($save_destination);

		$this->delete_older();

		$this->config->set_favicon_path($save_destination->get_path_from_root());
		CustomizationConfig::save();
	}

	private function delete_older()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->config->get_favicon_path());
		if ($file->exists())
		{
			$file->delete();
		}
	}
}
?>
