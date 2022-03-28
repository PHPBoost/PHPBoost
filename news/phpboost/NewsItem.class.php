<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 28
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class NewsItem extends RichItem
{
	protected $sub_categories_displayed = false;

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('top_list_enabled', array('type' => 'boolean', 'notnull' => 1, 'default' => 0, 'attribute_options_field_parameters' => array(
			'field_class' => 'FormFieldCheckbox',
			'label'       => LangLoader::get_message('form.top.list.enabled', 'common', 'news')
			)
		));
	}

	protected function default_properties()
	{
		$this->set_additional_property('top_list_enabled', 0);
	}

	protected function get_additional_template_vars()
	{
		return array(
			'C_PRIME_ITEM' => $this->get_additional_property('top_list_enabled')
		);
	}

	public function get_additional_content_template()
	{
		$template = new FileTemplate('news/NewsItemAdditionalContent.tpl');
		$template->add_lang(LangLoader::get_all_langs());
		$config = self::$module->get_configuration()->get_configuration_parameters();

		$suggested_news = ItemsService::get_items_manager(self::$module_id)->get_suggested_news($this);

		$template->put_all(array(
			'C_SUGGESTED_NEWS' => $config->get_items_suggestions_enabled() && !empty($suggested_news),
			'C_RELATED_LINKS'  => $config->get_items_navigation_enabled()
		));

		foreach ($suggested_news as $news)
		{
			$date = $news['creation_date'] <= $news['update_date'] ? $news['update_date'] : $news['creation_date'];
			$template->assign_block_vars('suggested', array(
				'C_HAS_THUMBNAIL' => !empty($news['thumbnail']),
				'CATEGORY_NAME'   => CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($news['id_category'])->get_name(),
				'TITLE'           => $news['title'],
				'DATE'			  => Date::to_format($date, Date::FORMAT_DAY_MONTH_YEAR),
				'U_CATEGORY'      => ItemsUrlBuilder::display_category($news['id_category'], CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($news['id_category'])->get_rewrited_name())->rel(),
				'U_ITEM'          => ItemsUrlBuilder::display($news['id_category'], CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($news['id_category'])->get_rewrited_name(), $news['id'], $news['rewrited_title'], self::$module_id)->rel(),
				'U_THUMBNAIL'     => $news['thumbnail'] == FormFieldThumbnail::DEFAULT_VALUE ? Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(RichItem::THUMBNAIL_URL)) : Url::to_rel($news['thumbnail'])
			));
		}

		foreach (ItemsService::get_items_manager(self::$module_id)->get_navigation_links($this) as $link)
		{
			$template->put_all(array(
				'C_'. $link['type'] .'_HAS_THUMBNAIL' => !empty($link['thumbnail']),
				'C_'. $link['type'] .'_ITEM'          => true,
				$link['type'] . '_ITEM'               => $link['title'],
				'U_'. $link['type'] .'_ITEM'          => ItemsUrlBuilder::display($link['id_category'], CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($link['id_category'])->get_rewrited_name(), $link['id'], $link['rewrited_title'], self::$module_id)->rel(),
				'U_'. $link['type'] .'_THUMBNAIL'     => $link['thumbnail'] == FormFieldThumbnail::DEFAULT_VALUE ? Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(RichItem::THUMBNAIL_URL)) : Url::to_rel($link['thumbnail'])
			));
		}

		return $template;
	}
}
?>
