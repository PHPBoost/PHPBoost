<?php
/*##################################################
 *                       SandboxFormController.class.php
 *                            -------------------
 *   begin                : December 20, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
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

class SandboxFormController extends ModuleController
{
	/**
	 * @var FormButtonSubmit
	 */
	private $preview_button;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$view = new FileTemplate('sandbox/SandboxFormController.tpl');
		$form = $this->build_form();
		if ($this->submit_button->has_been_submited())
		{
			if ($form->validate())
			{
				$view->put_all(array(
					'C_RESULT' => true, 

					'SELECT' => $form->get_value('select')->get_label(),

				));

			}
		}
		$view->put('form', $form->display());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{
		$form = new HTMLForm('sandboxForm');

		// FIELDSET
		$fieldset = new FormFieldsetHTML('fieldset_1', 'Fieldset');
		$form->add_fieldset($fieldset);

		$fieldset->set_description('Ceci est ma description');
		
		// SELECT
		$default_select_option = new FormFieldSelectChoiceOption('Choix 1', '1');
		$fieldset->add_field(new FormFieldSimpleSelectChoice('select', 'Liste déroulante', $default_select_option,
			array(
				$default_select_option,
				new FormFieldSelectChoiceOption('Choix 2', '2'),
				new FormFieldSelectChoiceOption('Choix 3', '3'),
				new FormFieldSelectChoiceGroupOption('Groupe 1', array(
					new FormFieldSelectChoiceOption('Choix 4', '4'),
					new FormFieldSelectChoiceOption('Choix 5', '5'),
				)),
				new FormFieldSelectChoiceGroupOption('Groupe 2', array(
					new FormFieldSelectChoiceOption('Choix 6', '6'),
					new FormFieldSelectChoiceOption('Choix 7', '7'),
				))
			),
			array('required' => true)));

		// BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('buttons');
		$buttons_fieldset->add_element(new FormButtonReset());

		$this->submit_button = new FormButtonDefaultSubmit();
		$buttons_fieldset->add_element($this->submit_button);

		$form->add_fieldset($buttons_fieldset);


		return $form;
	}
}
?>
