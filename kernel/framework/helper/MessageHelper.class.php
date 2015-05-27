<?php
/*##################################################
 *                             MessageHelper.class.php
 *                            -------------------
 *   begin                : Januar 07, 2011
 *   copyright            : (C) 2011 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @desc Message Helper
 * @author Régis Viarre <crowkait@phpboost.com>
 * @package {@package}
 */
class MessageHelper 
{
	const SUCCESS = 'success';
	const NOTICE = 'notice';
	const WARNING = 'warning';
	const ERROR = 'error';
	const QUESTION = 'question';
	
	public static function display($content, $type, $timeout = 0, $display_small = false)
	{
		$tpl = new FileTemplate('framework/helper/message.tpl');
		
		switch ($type)
		{
			case self::SUCCESS:
				$css_class = 'success';
				$image = 'error_success';
			break;
			case self::NOTICE:
				$css_class = 'notice';
				$image = 'error_notice';
			break;
			case self::WARNING:
				$css_class = 'warning';
				$image = 'error_warning';
			break;
			case self::ERROR:
				$css_class = 'error';
				$image = 'error_fatal';
			break;
			case self::QUESTION:
				$css_class = 'question';
				$image = 'error_question';
			break;
		}
		
		$tpl->put_all(array(
			'ID' => KeyGenerator::generate_key(4),
			'MESSAGE_CSS_CLASS' => $css_class . ($display_small ? ' message-helper-small' : ''),
			'MESSAGE_IMG' => $image,
			'MESSAGE_CONTENT' => $content,
			'C_TIMEOUT' => $timeout > 0,
			'TIMEOUT' => $timeout * 1000
		));
		
		return $tpl;
	}
}
?>