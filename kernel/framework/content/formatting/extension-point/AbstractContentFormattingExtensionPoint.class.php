<?php
/**
 * @package     Content
 * @subpackage  Formatting\extension-point
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

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
