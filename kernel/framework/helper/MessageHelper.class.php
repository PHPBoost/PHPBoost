<?php
/**
 * Message Helper
 * @package     Helper
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 17
 * @since       PHPBoost 3.0 - 2011 01 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MessageHelper
{
	const SUCCESS        = 'success';
	const NOTICE         = 'notice';
	const WARNING        = 'warning';
	const ERROR          = 'error';
	const QUESTION       = 'question';
	const MEMBER_ONLY    = 'member_only';
	const MODERATOR_ONLY = 'moderator_only';
	const ADMIN_ONLY     = 'admin_only';
	const GROUP          = 'group_only';

	public static function display($content, $type, $timeout = 0, $display_small = false)
	{
		$tpl = new FileTemplate('framework/helper/message.tpl');
		$group_only = false;

		switch ($type)
		{
			case self::SUCCESS:
				$css_class = 'success';
				$image     = 'error_success';
			break;
			case self::NOTICE:
				$css_class = 'notice';
				$image     = 'error_notice';
			break;
			case self::WARNING:
				$css_class = 'warning';
				$image     = 'error_warning';
			break;
			case self::ERROR:
				$css_class = 'error';
				$image     = 'error_fatal';
			break;
			case self::QUESTION:
				$css_class = 'question';
				$image     = 'error_question';
			break;
			case self::MEMBER_ONLY:
				$css_class = 'member';
				$image     = 'error_member_only';
			break;
			case self::MODERATOR_ONLY:
				$css_class = 'moderator';
				$image     = 'error_modo_only';
			break;
			case self::ADMIN_ONLY:
				$css_class = 'administrator';
				$image     = 'error_admin_only';
			break;
			default:
				$css_class  = $type . ' ' . self::GROUP;
				$image      = 'error_' . $type . '_' . self::GROUP;
				$group_only = true;
		}

		$tpl->put_all(array(
			'ID'                => KeyGenerator::generate_key(4),
			'MESSAGE_CSS_CLASS' => $css_class . ($display_small ? ' message-helper-small' : ''),
			'MESSAGE_IMG'       => $image,
			'MESSAGE_CONTENT'   => $content,
			'C_FLOATING'        => $timeout != 0,
			'C_TIMEOUT'         => $timeout > 0,
			'TIMEOUT'           => $timeout * 1000,
			'C_MEMBER_ONLY'     => $type == self::MEMBER_ONLY,
			'C_MODERATOR_ONLY'  => $type == self::MODERATOR_ONLY,
			'C_ADMIN_ONLY'      => $type == self::ADMIN_ONLY,
			'C_GROUP_ONLY'      => $group_only
		));

		return $tpl;
	}
}
?>
