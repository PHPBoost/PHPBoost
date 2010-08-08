<?php
/*##################################################
 *                       SandboxRegisterController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class SandboxRegisterController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		$view = new FileTemplate('sandbox/SandboxRegisterController.tpl');
		$form = $this->build_form();
		
		$view->add_subtemplate('form', $form->display());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{	
		$form = new HTMLForm('slide_admin');
		
		// S'inscrire
		$fieldset = new FormFieldsetHTML('s\'inscrire', 'S\'inscrire');
		$fieldset->set_description('Les champs marqués * sont obligatoire !');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', 'Pseudo', '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'description' => 'Longeur minimum du pseudo : 3 caractères'),
			array(new FormFieldConstraintLengthRange(3, 25))
		));		
		$fieldset->add_field(new FormFieldTextEditor('mail', 'Mail', '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => 'Mail valide'),
		array(new FormFieldConstraintMailAddress())
		));
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', 'Mot de passe', '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => 'Minimum 6, max 12 caractères'),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', 'Confirmation du mot de passe', '', array(
			'class' => 'text', 'maxlength' => 25),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field(new FormFieldSelectChoice('user_lang', 'Langue par défaut', '',
			array(
				new FormFieldSelectChoiceOption('Français', '1'),
				new FormFieldSelectChoiceOption('Anglais', '2'),
			)
		));
		
		//Options
		$fieldset2 = new FormFieldsetHTML('option', 'Option');
		$form->add_fieldset($fieldset2);
		
		$fieldset2->add_field(new FormFieldSelectChoice('user_theme', 'Thème par défaut', '',
			array(
				new FormFieldSelectChoiceOption('Base', '1'),
				new FormFieldSelectChoiceOption('Base2', '2'),
			),
			array('events' => array('onchange' => 'change_img_theme(\'img_theme\', this.options[selectedIndex].value)'))
		));
		$fieldset2->add_field(new FormFieldFree('preview_theme', 'Preview du thème', '<img id="img_theme" src="../templates/base/theme/images/theme.jpg" alt="" style="vertical-align:top" />', array()));
		
		$fieldset2->add_field(new FormFieldSelectChoice('user_editor', 'Editeur de texte par défaut', '',
			array(
				new FormFieldSelectChoiceOption('BBCode', '1'),
				new FormFieldSelectChoiceOption('TinyMCE', '2'),
			)
		));
		$fieldset2->add_field(new FormFieldSelectChoice('user_timezone', 'Choix du fuseau horaire', '',
			array(
				new FormFieldSelectChoiceOption('GMT', '1'),
			)
		));
		$fieldset2->add_field(new FormFieldCheckbox('user_hide_mail', 'Cacher votre email', true));
		
		//Infos
		$fieldset3 = new FormFieldsetHTML('informations', 'Informations');
		$form->add_fieldset($fieldset3);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_web', 'Site web', '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => 'Url valide'),
		array(new FormFieldConstraintUrl())
		));
		
		$fieldset3->add_field(new FormFieldTextEditor('user_local', 'localisation', '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_occupation', 'Emploi', '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldTextEditor('user_hobbies', 'Loisirs', '', array(
			'class' => 'text', 'maxlength' => 255))
		);
		
		$fieldset3->add_field(new FormFieldSelectChoice('user_sex', 'Sexe', '',
			array(
				new FormFieldSelectChoiceOption('--', '1'),
				new FormFieldSelectChoiceOption('Masculin', '2'),
				new FormFieldSelectChoiceOption('Feminim', '3'),
			)
		));
		
		$fieldset3->add_field(new FormFieldDate('user_born', 'Date de naissance', new Date(), array(
			'description' => 'Date de naissance valide')
		));
		
		$fieldset3->add_field(new FormFieldMultiLineTextEditor('user_sign', 'Signature', '',
		array('rows' => 4, 'cols' => 27, 'description' => 'Apparaît sous chacun de vos messages')
		));
		
		//Contact
		$fieldset4 = new FormFieldsetHTML('contact', 'Contact');
		$form->add_fieldset($fieldset4);
		
		$fieldset4->add_field(new FormFieldTextEditor('user_msn', 'MSN', '', array(
			'class' => 'text', 'maxlength' => 255), array(new FormFieldConstraintMailAddress()))
		);
		$fieldset4->add_field(new FormFieldTextEditor('user_yohoo', 'Yahoo', '', array(
			'class' => 'text', 'maxlength' => 255), array(new FormFieldConstraintMailAddress()))
		);
		// Avatar
		$fieldset5 = new FormFieldsetHTML('gestion_avatar', 'Gestion avatar');
		$form->add_fieldset($fieldset5);
		
		$fieldset5->add_field(new FormFieldFilePicker('avatars', 'Uploader avatar',
			array('description' => 'Avatar directement hébergé sur le serveur'
		)));
		$fieldset5->add_field(new FormFieldTextEditor('user_avatar', 'lien avatar', '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => 'Adresse directe de l\'avatar')
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button(new FormButtonDefaultSubmit());

		return $form;
	}
}

?>