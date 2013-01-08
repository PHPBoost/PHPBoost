<?php
/*##################################################
 *                           AbstractContentFormattingExtensionPoint.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

abstract class AbstractContentFormattingExtensionPoint implements ContentFormattingExtensionPoint
{
	protected $forbidden_tags = array();
	protected $html_auth = array();
	
	public function __construct()
	{
		$content_formatting_config = ContentFormattingConfig::load();
		$this->forbidden_tags = $content_formatting_config->get_forbidden_tags();
		$this->html_auth = $content_formatting_config->get_html_tag_auth();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function set_forbidden_tags(array $tags)
	{
		$this->forbidden_tags = $tags;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_forbidden_tags()
	{
		return $this->forbidden_tags;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function add_forbidden_tag($tag)
	{
		$this->forbidden_tags[] = $tag;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_forbidden_tags(array $tags)
	{
		foreach ($tags as $tag)
		{
			$this->forbidden_tags[] = $tag;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function set_html_auth(array $auth)
	{
		$this->html_auth = $auth;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_html_auth()
	{
		return $this->html_auth;
	}
}
?>