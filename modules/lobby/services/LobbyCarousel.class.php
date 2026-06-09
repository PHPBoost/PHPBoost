<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyCarousel
{
	/**
	 * Returns the carousel view populated from LobbyConfig carousel items.
	 */
	public static function get_carousel_view(): FileTemplate
	{
		$config  = LobbyConfig::load();
		$carousel = $config->get_carousel();

		$view = new FileTemplate('lobby/pagecontent/carousel.tpl');
		$view->add_lang(LangLoader::get_all_langs('lobby'));

		$nb_dots = 0;

		foreach ($carousel as $id => $options)
		{
			$view->assign_block_vars('items', [
				'C_LINK_ONLY'       => !empty($options['link']) && empty($options['picture_url']),
				'U_DEFAULT_PICTURE' => Url::to_rel('/templates/__default__/images/default_item.webp'),
				'DESCRIPTION'       => $options['description'],
				'PICTURE_TITLE'     => !empty($options['description']) ? $options['description'] : basename($options['picture_url']),
				'U_PICTURE'         => Url::to_rel($options['picture_url']),
				'LINK'              => Url::to_rel($options['link']),
			]);
			$nb_dots++;
		}

		$view->put_all([
			'CAROUSEL_POSITION' => $config->get_module_position_by_id(LobbyConfig::MODULE_CAROUSEL),
			'NB_DOTS'           => $nb_dots,
			'CAROUSEL_SPEED'    => $config->get_carousel_speed(),
			'CAROUSEL_TIME'     => $config->get_carousel_time(),
			'CAROUSEL_NUMBER'   => $config->get_carousel_number(),
			'CAROUSEL_AUTO'     => $config->get_carousel_auto(),
			'CAROUSEL_HOVER'    => $config->get_carousel_hover(),
		]);

		return $view;
	}
}
?>
