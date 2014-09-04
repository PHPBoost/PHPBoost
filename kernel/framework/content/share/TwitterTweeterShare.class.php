<?php
/*##################################################
 *                               TwitterTweeterShare.class.php
 *                            -------------------
 *   begin                : July 06, 2011
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
 
class TwitterTweeterShare extends AbstractShare
{
	private $lang = null;
	private $layout = 'horizontal';
	private $manual_content_tweet = '';
	private $manual_url = '';
	
	const VERTICAL_LAYOUT = 'vertical';
	const HORIZONTAL_LAYOUT = 'horizontal';
	const NO_COUNTER = 'none';
	
	public function __construct()
	{
		$this->set_template(new StringTemplate('
			<a href="http://twitter.com/share" class="twitter-share-button" # IF C_MANUAL_URL # data-url="{MANUAL_URL}" # ENDIF # 
			# IF C_MANUAL_CONTENT_TWEET # data-text="{MANUAL_CONTENT_TWEET}" # ENDIF # data-count="{LAYOUT}" data-lang="{LANG_SHARE}">
			</a>
			<script src="http://platform.twitter.com/widgets.js"></script> 
		'));
		
		$this->assign_vars();
	}
	
	public static function display($layout = 'horizontal', $manual_content_tweet = '')
	{
		$class = new self();
		$class->set_layout($layout);
		$class->set_manual_content_tweet($manual_content_tweet);
		return $class->display();
	}
	
	public function set_manual_lang($lang)
	{
		$this->lang = $lang;
	}
	
	public function get_lang()
	{
		if ($this->lang !== null)
		{
			return $this->lang;
		}
		else
		{
			// TODO change for a new function
			return substr(AppContext::get_current_user()->get_locale(), 2);
		}
	}
	
	public function set_manual_content_tweet($manual_content_tweet)
	{
		$this->manual_content_tweet = $manual_content_tweet;
	}
	
	public function set_manual_url($manual_url)
	{
		$this->manual_url = manual_url;
	}
	
	public function set_layout($layout)
	{
		switch ($layout) 
		{
			case self::VERTICAL_LAYOUT:
			case self::HORIZONTAL_LAYOUT:
			case self::NO_COUNTER:
				$this->layout = $layout;
				break;
			default:
				throw new Exception('Layout entry is not correct');
		}
	}
	
	private function assign_vars()
	{
		$this->get_template()->put_all(array(
			'C_MANUAL_URL' => !empty($this->manual_url),
			'MANUAL_URL' => $this->manual_url,
			'LANG_SHARE' => $this->get_lang(),
			'LAYOUT' => $this->layout,
			'C_MANUAL_CONTENT_TWEET' => !empty($this->manual_content_tweet),
			'MANUAL_CONTENT_TWEET' => $this->manual_content_tweet
		));
	}
}
?>