<?php
/*##################################################
 *                           NewsConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 8, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class NewsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news');
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$news_block_activated = ($config['type'] == 1) ? true : false;
		$comments_activated = ($config['activ_com'] == 1) ? true : false;
		$icon_activated = ($config['activ_icon'] == 1) ? true : false;
		$edito_activated = ($config['activ_edito'] == 1) ? true : false;
		$pagination_activated = ($config['activ_pagin'] == 1) ? true : false;
		$display_date = ($config['display_date'] == 1) ? true : false;
		$display_author = ($config['display_author'] == 1) ? true : false;
		
		$news_config = NewsConfig::load();
		$news_config->set_news_block_activated($news_block_activated);
		$news_config->set_comments_activated($comments_activated);
		$news_config->set_icon_activated($icon_activated);
		$news_config->set_edito_activated($edito_activated);
		$news_config->set_pagination_activated($pagination_activated);
		$news_config->set_display_date($display_date);
		$news_config->set_display_author($display_author);
		$news_config->set_news_pagination($config['pagination_news']);
		$news_config->set_archives_pagination($config['pagination_arch']);
		$news_config->set_nbr_columns($config['nbr_column']);
		$news_config->set_nbr_visible_news($config['nbr_news']);
		$news_config->set_edito_title($config['edito_title']);
		$news_config->set_edito_content($config['edito']);
		NewsConfig::save();

		return true;
	}
}
?>