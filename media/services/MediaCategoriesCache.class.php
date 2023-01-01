<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 4.0 - 2015 02 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaCategoriesCache extends DefaultCategoriesCache
{
	public function get_category_class()
	{
		return 'MediaCategory';
	}

	public function get_module_identifier()
	{
		return 'media';
	}

	protected function get_category_elements_number($id_category)
	{
		require_once(PATH_TO_ROOT . '/media/media_constant.php');

		return MediaService::count('WHERE id_category = :id_category AND published = :status',
			array(
				'id_category' => $id_category,
				'status' => MEDIA_STATUS_APPROVED
			)
		);
	}

	public function get_root_category()
	{
		$config = MediaConfig::load();

		$root = new MediaCategory();
		$root->set_id(Category::ROOT_CATEGORY);
		$root->set_id_parent(Category::ROOT_CATEGORY);
		$root->set_name(LangLoader::get_message('common.root', 'common-lang'));
		$root->set_rewrited_name('root');
		$root->set_order(0);
		$root->set_authorizations($config->get_authorizations());
		$description = $config->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('media.seo.description.root', 'common', 'media'), array('site' => GeneralConfig::load()->get_site_name()));
		$root->set_description($description);
		$root->set_additional_property('content_type', $config->get_root_category_content_type());
		return $root;
	}
}
?>
