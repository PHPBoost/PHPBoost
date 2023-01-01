<?php
/**
 * @package     Content
 * @subpackage  Comments\extension-point
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 23
*/

class CommentsProvidersService
{
	public static function module_containing_extension_point($module_id)
	{
		return in_array($module_id, self::get_extension_point_ids());
	}

	public static function get_extension_point_ids()
	{
		return array_keys(self::get_extension_point());
	}

	public static function get_extension_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(CommentsExtensionPoint::EXTENSION_POINT);
	}

	public static function get_provider($module_id, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$extension_point = self::get_extension_point();
			return $extension_point[$module_id]->get_comments_topic($topic_identifier);
		}
	}
}
?>
