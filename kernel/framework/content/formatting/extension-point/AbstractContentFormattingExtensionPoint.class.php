<?php
/**
 * @package     Content
 * @subpackage  Formatting\extension-point
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 10 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractContentFormattingExtensionPoint implements ContentFormattingExtensionPoint
{
	protected array $forbidden_tags = [];
	protected array $html_auth = [];

	public function __construct()
	{
		$content_formatting_config = ContentFormattingConfig::load();
		$this->forbidden_tags = $content_formatting_config->get_forbidden_tags();
		$this->html_auth = $content_formatting_config->get_html_tag_auth();
	}

	public function set_forbidden_tags(array $tags): void
	{
		$this->forbidden_tags = $tags;
	}

	public function get_forbidden_tags(): array
	{
		return $this->forbidden_tags;
	}

	public function add_forbidden_tag(string $tag): void
	{
		$this->forbidden_tags[] = $tag;
	}

	public function add_forbidden_tags(array $tags): void
	{
		foreach ($tags as $tag)
		{
			$this->forbidden_tags[] = $tag;
		}
	}

	public function set_html_auth(array $auth): void
	{
		$this->html_auth = $auth;
	}

	public function get_html_auth(): array
	{
		return $this->html_auth;
	}
}
?>
