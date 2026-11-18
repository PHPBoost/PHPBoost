<?php
/**
 * This class represents the comments topic
 * <div class="message-helper bgc notice">Do not use this class, but one of its children like for your module</div>
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2011 03 31
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsTopic
{
	protected string $module_id;
	protected string $topic_identifier = '';
	protected int $id_in_module;
	protected ?Url $url = null;

	const DEFAULT_TOPIC_IDENTIFIER = 'default';

	public function __construct(string $module_id, string $topic_identifier = self::DEFAULT_TOPIC_IDENTIFIER)
	{
		$this->module_id = $module_id;
		$this->topic_identifier = $topic_identifier;
	}

	public function get_authorizations(): CommentsAuthorizations
	{
		return new CommentsAuthorizations();
	}

	public function is_displayed(): bool
	{
		return false;
	}

	public function get_comments_number_display(): int
	{
		return CommentsConfig::load()->get_comments_number_display();
	}

	public function get_events(): CommentsTopicEvents
	{
		return new CommentsTopicEvents($this);
	}

	public function display(): Template
	{
		return CommentsService::display($this);
	}

	public function get_topic_identifier(): string
	{
		return $this->topic_identifier;
	}

	public function get_module_id(): string
	{
		return $this->module_id;
	}

	public function set_id_in_module(int $id_in_module): void
	{
		$this->id_in_module = $id_in_module;
	}

	public function get_id_in_module(): int
	{
		return $this->id_in_module;
	}

	public function set_url(Url $url): void
	{
		$this->url = $url;
	}

	public function get_url(): string
	{
		return $this->url->rel();
	}

	public function get_path(): string
	{
		return $this->url->relative();
	}
}
?>
