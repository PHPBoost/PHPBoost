<?php
/*##################################################
 *                    AbstractContentFormattingFactory.class.php
 *                            -------------------
 *   begin                : January 9, 2010
 *   copyright            : (C) 2010 Benoit Sautel
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

/**
 * @package content
 * @subpackage formatting/factory
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This is a default abstract implementation of the ContentFormattingFactory class.
 */
abstract class AbstractContentFormattingFactory implements ContentFormattingFactory
{
	protected $forbidden_tags = array();
	
	protected $html_auth = array();
	
	public function __construct()
	{
		global $CONFIG;
		$this->forbidden_tags = $CONFIG['forbidden_tags'];
		$this->html_auth = $CONFIG['html_auth'];
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
