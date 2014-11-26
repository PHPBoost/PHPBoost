<?php
/*##################################################
 *                               FaqCache.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class FaqCache implements CacheData
{
	private $questions = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->questions = array();
		
		$result = PersistenceContext::get_querier()->select('
			SELECT id, id_category, question
			FROM ' . FaqSetup::$faq_table . ' faq
			WHERE approved = 1'
		);
		
		while ($row = $result->fetch())
		{
			$this->questions[$row['id_category']][] = array(
				'id' => $row['id'],
				'question' => $row['question']
			);
		}
	}
	
	public function get_questions()
	{
		return $this->questions;
	}
	
	/**
	 * Loads and returns the faq cached data.
	 * @return FaqCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'faq');
	}
	
	/**
	 * Invalidates the current faq cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'faq');
	}
}
?>
