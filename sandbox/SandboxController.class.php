<?php
/*##################################################
 *                          SandboxController.class.php
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

class SandboxController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		$view = new View('sandbox/SandboxController.tpl');
		$form = $this->build_form($request);
		if ($request->is_post_method())
		{
			if ($form->validate())
			{
				echo 'valide';
			}
			else
			{
				echo 'pas valide';
			}
		}
		$view->add_subtemplate('form', $form->export());
		return new SiteDisplayResponse($view);
	}

	private function build_form(HTTPRequest $request)
	{
		$form = new Form('sandboxForm');
		$fieldset = new FormFieldset('ajouter un message');

		$fieldset->add_field(new FormTextEdit('pseudo', 'Visiteur', array(
			'title' => 'Pseudo', 'class' => 'text', 'required' => 'Le pseudo est obligatoire',
			'maxlength' => 25),
		array(new RegexFormFieldConstraint('`^[a-z0-9_]+$`i'))));
		$fieldset->add_field(new FormTextarea('contents', '', array(
		'forbiddentags' => array('swf'), 'title' => 'message',
		'rows' => 10, 'cols' => 47, 'required' => 'le message est obligatoire')));

		$form->add_fieldset($fieldset);
		$form->display_preview_button('contents');

		return $form;
	}
}
?>
