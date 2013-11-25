<?php
/*##################################################
 *                             AbstractRichCategoriesFormController.class.php
 *                            -------------------
 *   begin                : February 07, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc
 */
abstract class AbstractRichCategoriesFormController extends AbstractCategoriesFormController
{	
	protected function get_options_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new FormFieldRichTextEditor('description', $this->lang['category.form.description'], $this->get_category()->get_description()));
		
		$image_preview_request = new AjaxRequest(PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/image/preview/', 'function(response){
		if (response.responseJSON.url) {
			$(\'preview_picture\').src = response.responseJSON.url;
		}}');
		$image_preview_request->add_event_callback(AjaxRequest::ON_CREATE, 'function(response){ $(\'preview_picture\').src = PATH_TO_ROOT + \'/templates/'. get_utheme() .'/images/loading_mini.gif\';}');
		$image_preview_request->add_param('image', 'HTMLForms.getField(\'image\').getValue()');
		
		$fieldset->add_field(new FormFieldTextEditor('image', $this->lang['category.form.image'], $this->get_category()->get_image()->relative(), array(
			'events' => array('change' => $image_preview_request->render())
		)));
		$fieldset->add_field(new FormFieldFree('image_preview', $this->lang['category.form.image.preview'], '<img id="preview_picture" src="'. $this->get_category()->get_image()->rel() .'" alt="" style="vertical-align:top" />'));
	}
	
	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_description($this->form->get_value('description'));
		$this->get_category()->set_image(new Url($this->form->get_value('image')));
	}
}
?>