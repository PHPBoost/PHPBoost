<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2020 06 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesItem extends RichItem
{
	protected $summary_field_enabled = false;

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('i_order', ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]);

		$this->add_additional_attribute('author_display', ['type' => 'boolean', 'notnull' => 1, 'default' => 0, 'attribute_post_content_field_parameters' => [
			'field_class' => 'FormFieldCheckbox',
			'label'       => LangLoader::get_message('form.display.author', 'form-lang')
			]
		]);
	}

	protected function default_properties()
	{
		$this->set_additional_property('i_order', 0);
		$this->set_additional_property('author_display', 0);
	}

	protected function get_additional_template_vars()
	{
		return [
			'C_AUTHOR_DISPLAYED' => $this->get_additional_property('author_display')
		];
	}

	/**
	 * Returns the frontend URL of this item using the root dispatcher
	 * (no /pages/ prefix in the generated URL).
	 *
	 * Overrides Item::get_item_url() which would otherwise produce /pages/...
	 *
	 * @return string relative URL
	 */
	public function get_item_url(): string
	{
		$category = $this->get_category();
		return PagesUrlBuilder::display(
			$category->get_id(),
			$category->get_rewrited_name(),
			$this->get_id(),
			$this->get_rewrited_title()
		)->rel();
	}

	/**
	 * Extends the parent template vars to fix the U_COMMENTS URL so it also
	 * points to the root-based frontend URL instead of /pages/...
	 *
	 * {@inheritdoc}
	 */
	public function get_template_vars(): array
	{
		$vars = parent::get_template_vars();

		// Fix the comments anchor URL produced by Item::get_template_vars()
		// which calls ItemsUrlBuilder::display_comments() => /pages/... path.
		if (isset($vars['U_COMMENTS']))
		{
			$category = $this->get_category();
			$vars['U_COMMENTS'] = PagesUrlBuilder::display_comments(
				$category->get_id(),
				$category->get_rewrited_name(),
				$this->get_id(),
				$this->get_rewrited_title()
			)->rel();
		}

		return $vars;
	}
}
?>
