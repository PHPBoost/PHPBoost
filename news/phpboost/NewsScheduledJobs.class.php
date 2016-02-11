<?php
/*##################################################
 *                         NewsScheduledJobs.class.php
 *                            -------------------
 *   begin                : December 15, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class NewsScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changepage()
	{
		$config = NewsConfig::load();
		$deferred_operations = $config->get_deferred_operations();
		
		if (!empty($deferred_operations))
		{
			$now = new Date();
			$is_modified = false;
			
			foreach ($deferred_operations as $id => $timestamp)
			{
				if ($timestamp <= $now->get_timestamp())
				{
					unset($deferred_operations[$id]);
					$is_modified = true;
				}
			}
			
			if ($is_modified)
			{
				Feed::clear_cache('news');
				NewsCategoriesCache::invalidate();
				
				$config->set_deferred_operations($deferred_operations);
				NewsConfig::save();
			}
		}
	}
}
?>
