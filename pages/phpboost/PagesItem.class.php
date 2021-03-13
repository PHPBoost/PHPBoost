<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 13
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesItem extends RichItem
{
	protected $summary_field_enabled = false;
	
	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('i_order', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		
		$this->add_additional_attribute('author_display', array('type' => 'boolean', 'notnull' => 1, 'default' => 0, 'attribute_post_content_field_parameters' => array(
			'field_class' => 'FormFieldCheckbox',
			'label'       => LangLoader::get_message('config.author.displayed', 'admin-common'),
			'value'       => 0
			)
		));
	}
	
	public function get_author_display()
	{
		return $this->get_additional_property('author_display');
	}

	public function set_author_display(int $value)
	{
		$this->set_additional_property('author_display', $value);
	}

	protected function default_properties()
	{
		$this->set_author_display(0);
		$this->set_additional_property('i_order', 0);
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_template_vars()
	{
		$category = $this->get_category();
		$comments_number = CommentsService::get_comments_number('pages', $this->id);
		$config = PagesConfig::load();

		return array_merge(
			parent::get_template_vars(),
			array(
				// Conditions
				'C_CONTROLS'		 => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_EDIT'             => $this->is_authorized_to_edit(),
				'C_DELETE'           => $this->is_authorized_to_delete(),
				'C_AUTHOR_DISPLAYED' => $this->get_author_display(),
				
				// Item
				'STATUS'             => $this->get_status(),
				'C_VIEWS_NUMBER'     => $config->get_views_number_enabled(),

				'C_COMMENTS'      => !empty($comments_number),
				'L_COMMENTS'      => CommentsService::get_lang_comments('pages', $this->id),
				
				// Links
				'U_ITEM'           => PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel(),
				'U_EDIT'           => PagesUrlBuilder::edit_item($this->id)->rel(),
				'U_DELETE'         => PagesUrlBuilder::delete_item($this->id)->rel(),
				'U_COMMENTS'       => PagesUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel()
			)
		);
	}
}
?>
