<?php
/**
 * This class represents the comments topic
 * <div class="message-helper bgc notice">Do not use this class, but one of its children like for your module</div>
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2011 03 31
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsTopic
{
	protected $module_id;
	protected $topic_identifier = '';
	protected $id_in_module;
	protected $url;

	const DEFAULT_TOPIC_IDENTIFIER = 'default';

	public function __construct($module_id, $topic_identifier = self::DEFAULT_TOPIC_IDENTIFIER)
	{
		$this->module_id = $module_id;
		$this->topic_identifier = $topic_identifier;
	}

	/**
	 * @return class CommentsAuthorizations
	 */
	public function get_authorizations()
	{
		return new CommentsAuthorizations();
	}

	/**
	 * @return boolean display
	 */
	public function is_displayed()
	{
		return false;
	}

	/**
	 * @return int number comments display default
	 */
	public function get_comments_number_display()
	{
		return CommentsConfig::load()->get_comments_number_display();
	}

	/**
	 * @return class CommentsTopicEvents
	 */
	public function get_events()
	{
		return new CommentsTopicEvents($this);
	}

	public function display()
	{
		return CommentsService::display($this);
	}

	public function get_topic_identifier()
	{
		return $this->topic_identifier;
	}

	public function get_module_id()
	{
		return $this->module_id;
	}

	public function set_id_in_module($id_in_module)
	{
		$this->id_in_module = $id_in_module;
	}

	public function get_id_in_module()
	{
		return $this->id_in_module;
	}

	public function set_url(Url $url)
	{
		$this->url = $url;
	}

	public function get_url()
	{
		return $this->url->rel();
	}

	public function get_path()
	{
		return $this->url->relative();
	}
}
?>
