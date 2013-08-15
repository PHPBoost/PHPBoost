<?php
/*##################################################
 *                        Articles.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class Articles
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $description;
	private $contents;
	private $picture_url;
	private $number_view;

	private $notation_enabled;
	private $author_name_displayed;
	private $author_user;
	private $notation;

	private $published;
	private $publishing_start_date;
	private $publishing_end_date;
	private $date_created;
	private $end_date_enabled;

	private $sources;

	const NOT_PUBLISHED = 0;
	const PUBLISHED_NOW = 1;
	const PUBLISHED_DATE = 2;

	const NOTATION_DISABLED = 0;
	const NOTATION_ENABLED = 1;

	const AUTHOR_NAME_NOTDISPLAYED = 0;
	const AUTHOR_NAME_DISPLAYED = 1;
	
	const DEFAULT_NBR_VIEW = 0;
        
	const DEFAULT_PICTURE = '/articles/templates/images/default.png';

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function rewrited_title_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}
	
	public function set_description($description)		
	{		
		$this->description = $description;		
	}		
			
	public function get_description()
	{		
		return $this->description;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}

	public function get_picture()
	{
		return $this->picture_url;
	}
	
	public function has_picture()
	{
		$picture = $this->picture_url->absolute();
		return !empty($picture);
	}
	
	public function set_number_view($number_view)
	{
		$this->number_view = $number_view;
	}

	public function get_number_view()
	{
		return $this->number_view;
	}

	public function get_notation_enabled()
	{
		return $this->notation_enabled;
	}

	public function set_notation_enabled($enabled) 
	{
		$this->notation_enabled = $enabled;
	}

	public function get_author_name_displayed()
	{
		return $this->author_name_displayed;
	}

	public function set_author_name_displayed($displayed)
	{
		$this->author_name_displayed = $displayed;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}
	
	public function get_author_user()
	{
	    return $this->author_user;
	}

	public function set_notation(Notation $notation)
	{
		$this->notation = $notation;
	}
	
	public function get_notation()
	{
	    return $this->notation;
	}
	
	public function set_publishing_state($published)
	{
		$this->published = $published;
	}

	public function get_publishing_state()
	{
		return $this->published;
	}
	
	public function is_published()
	{
		$now = new Date();
		return $this->get_publishing_state() == Articles::PUBLISHED_NOW || 
			($this->get_publishing_state() == Articles::PUBLISHED_DATE 
			&& $this->get_publishing_start_date()->is_anterior_to($now) 
			&& ($this->end_date_enabled ? $this->get_publishing_end_date()->is_posterior_to($now) : true));
	}
	
	public function set_publishing_start_date(Date $publishing_start_date)
	{
		$this->publishing_start_date = $publishing_start_date;
	}

	public function get_publishing_start_date()
	{
		return $this->publishing_start_date;
	}

	public function set_publishing_end_date(Date $publishing_end_date)
	{
		$this->publishing_end_date = $publishing_end_date;
		$this->end_date_enabled = true;
	}

	public function get_publishing_end_date()
	{
		return $this->publishing_end_date;
	}

	public function end_date_enabled()
	{
		return $this->end_date_enabled;
	}

	public function set_date_created(Date $date_created)
	{
		$this->date_created = $date_created;
	}

	public function get_date_created()
	{
		return $this->date_created;
	}

	public function add_source($source)
	{
		$this->sources[] = $source;
	}

	public function set_sources($sources)
	{
		$this->sources = $sources;
	}

	public function get_sources()
	{
		return $this->sources;
	}

	public function get_keywords()
	{
		return ArticlesKeywordsService::get_article_keywords_name($this->id);
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'title' => $this->get_title(),
			'rewrited_title' => $this->get_rewrited_title(),
			'description' => $this->get_description(),
			'contents' => $this->get_contents(),
			'picture_url' => $this->get_picture()->relative(),
			'number_view' => $this->get_number_view(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'author_name_displayed' => $this->get_author_name_displayed(),
			'published' => $this->get_publishing_state(),
			'publishing_start_date' => $this->get_publishing_start_date() !== null ? $this->get_publishing_start_date()->get_timestamp() : '',
			'publishing_end_date' => $this->get_publishing_end_date() !== null ? $this->get_publishing_end_date()->get_timestamp() : '',
			'date_created' => $this->get_date_created()->get_timestamp(),
			'notation_enabled' => $this->get_notation_enabled(),
			'sources' => serialize($this->get_sources())
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_description($properties['description']);
		$this->set_contents($properties['contents']);
		$this->set_picture(new Url($properties['picture_url']));
		$this->set_number_view($properties['number_view']);
		$this->set_author_name_displayed($properties['author_name_displayed']);
		$this->set_publishing_state($properties['published']);
		$this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['publishing_start_date']) : null;
		$this->publishing_end_date = !empty($properties['publishing_end_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['publishing_end_date']) : null;
		$this->set_date_created(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['date_created']));
		$this->set_notation_enabled($properties['notation_enabled']);
		$this->set_sources(!empty($properties['sources']) ? unserialize($properties['sources']) : array());
		
		$user = new User();
		$user->set_properties($properties);
		$this->set_author_user($user);
		
		$notation = new Notation();
		$notation->set_module_name('articles');
		$notation->set_notation_scale(ArticlesConfig::load()->get_notation_scale());
		$notation->set_id_in_module($properties['id']);
		$notation->set_number_notes($properties['number_notes']);
		$notation->set_average_notes($properties['average_notes']);
		$notation->set_user_already_noted(!empty($properties['note']));
		$this->notation = $notation;
	}

	public function init_default_properties()
	{
		$this->id_category = Category::ROOT_CATEGORY;
		$this->author_name_displayed = self::AUTHOR_NAME_DISPLAYED;
		$this->publishing_state = self::NOT_PUBLISHED;
		$this->publishing_start_date = new Date();
		$this->publishing_end_date = new Date();
		$this->date_created = new Date();
		$this->notation_enabled = self::NOTATION_ENABLED;
		$this->sources = array();
		$this->picture_url = new Url(self::DEFAULT_PICTURE);
		$this->number_view = self::DEFAULT_NBR_VIEW;
	}

	public function clean_publishing_start_and_end_date()
	{
		$this->publishing_start_date = null;
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}

	public function clean_publishing_end_date()
	{
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}
	
	public function get_tpl_vars()
	{
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($this->get_id_category());
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		
		$description = FormatingHelper::second_parse($this->get_description());
		if (ArticlesConfig::load()->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC)
		{
			$short_description = strlen($description > 132) ? TextHelper::substr_html($description, 0, 128) . '...' : $description;
		}
		else
		{
			$short_description = strlen($description > 249) ? TextHelper::substr_html($description, 0, 245) . '...' : $description;
		}
		
		return array(
			//Conditions
			'C_EDIT' => ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation() || ArticlesAuthorizationsService::check_authorizations($category->get_id())->write() && $user->get_id() == AppContext::get_current_user()->get_id(),
			'C_DELETE' => ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation(),
			'C_HAS_PICTURE' => $this->has_picture(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_AUTHOR_DISPLAYED' => $this->get_author_name_displayed(),
			'C_NOTATION_ENABLED' => $this->get_notation_enabled(),
			
			//Articles
			'TITLE' => $this->get_title(),
			'DATE' => $this->get_date_created()->format(DATE_FORMAT_SHORT),
			'L_COMMENTS' => CommentsService::get_number_and_lang_comments('articles', $this->get_id()),
			'NUMBER_VIEW' => $this->get_number_view(),
			'NOTE' => $this->get_notation()->get_number_notes() > 0 ? NotationService::display_static_image($this->get_notation()) : '&nbsp;',
			'PSEUDO' => $user->get_pseudo(),
			'DESCRIPTION' => $short_description,
			'PICTURE' => $this->get_picture()->absolute(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
		    
			//Links
			'U_COMMENTS' => ArticlesUrlBuilder::display_comments_article($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->absolute(),
			'U_AUTHOR' => UserUrlBuilder::profile($this->get_author_user()->get_id())->absolute(),
			'U_CATEGORY' => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->absolute(),
			'U_ARTICLE' => ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->absolute(),
			'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($this->get_id())->absolute(),
			'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($this->get_id())->absolute(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication($category->get_id())->rel()
		);
	}
}
?>