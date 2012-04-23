<?php
/*##################################################
 *                        APCDataStore.class.php
 *                            -------------------
 *   begin                : December 09, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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
 * @package {@package}
 * @desc This data store is not already available, the APC PHP extension must be enabled for you yo use it.
 * When it's available, it provides a memory area that is persistent (its life span is no the page execution) and
 * shared by all simultaneous page executions.
 * This is very efficient and has an infinite life span (in fact it's the Web server's one).
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 */
class APCDataStore implements DataStore
{
	private static $website_id = false;

	private $cache_id;
	
	private static $apc_fields_id = '_apc_fields';

	private $apc_fields = array();

	public function __construct($cache_id)
	{
		$this->cache_id = self::get_website_id() . '-' . $cache_id;
		$this->retrieve_apc_fields();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($id)
	{
		$id = $this->get_full_object_id($id);
		$found = false;
		$object = apc_fetch($id, $found);
		if (!$found)
		{
			throw new DataStoreException($id);
		}
		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	public function contains($id)
	{
		$id = $this->get_full_object_id($id);
		$found = false;
		apc_fetch($id, $found);
		return $found;
	}

	/**
	 * {@inheritdoc}
	 */
	public function store($id, $object)
	{
		$this->add_apc_field($id);
		$id = $this->get_full_object_id($id);
		return (bool) apc_store($id, $object);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id)
	{
		$this->delete_apc_field($id);
		$id = $this->get_full_object_id($id);
		return apc_delete($id);
	}

	private function get_full_object_id($tiny_id)
	{
		return $this->cache_id . '-' . $tiny_id;
	}

	private static function get_website_id()
	{
		if (self::$website_id === false)
		{
			$website_id_cache_file = PATH_TO_ROOT . '/cache/website_id.cfg';
			if (file_exists($website_id_cache_file))
			{
				self::$website_id = @file_get_contents($website_id_cache_file);
			}
			if (self::$website_id === false)
			{
				self::$website_id = substr(md5(realpath(PATH_TO_ROOT)), 0, 10);
				$file = new File($website_id_cache_file);
				$file->write(self::$website_id);
				$file->close();
			}
		}
		return self::$website_id;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function clear()
	{
		// Copy the apc_fields array to avoid exceptions due to elements removed during foreach
		$apc_fields = $this->apc_fields;
		foreach ($apc_fields as $apc_field)
		{
			$this->delete($apc_field);
		}
	}

	private function retrieve_apc_fields()
	{
		if ($this->contains(self::$apc_fields_id))
		{
			$this->apc_fields = $this->get(self::$apc_fields_id);
		}
		else
		{
			$this->apc_fields = array();
		}
	}

	private function add_apc_field($field_name)
	{
		if (!in_array($field_name, $this->apc_fields))
		{
			$this->apc_fields[] = $field_name;
			$this->store(self::$apc_fields_id, $this->apc_fields);
		}
	}

	private function delete_apc_field($field_name)
	{
		$value_index = array_search($field_name, $this->apc_fields);
		if ($value_index !== false)
		{
			unset($this->apc_fields[$value_index]);
			$this->store(self::$apc_fields_id, $this->apc_fields);
		}
	}
}
?>