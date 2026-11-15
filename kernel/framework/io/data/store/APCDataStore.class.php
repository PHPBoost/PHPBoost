<?php
/**
 * The APCu PHP extension must be enabled to use this data store.
 * When it's available, it provides a memory area that is persistent (its life span is no the page execution) and
 * shared by all simultaneous page executions.
 * This is very efficient and has an infinite life span (in fact it's the Web server's one).
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 09 16
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class APCDataStore implements DataStore
{
	private static string|false $website_id = false;
	private string $cache_id = '';
	private static string $apc_fields_id = '_apc_fields';
	private array $apc_fields = [];

	public function __construct(object|string $cache_id)
	{
		$this->cache_id = self::get_website_id() . '-' . $cache_id;
		$this->retrieve_apc_fields();
	}

	public function get(string $id): mixed
	{
		$id = $this->get_full_object_id($id);
		$found = false;
        /** @disregard */
		$object = apcu_fetch($id, $found);
		if (!$found)
		{
			throw new DataStoreException($id);
		}
		return $object;
	}

	public function contains(string $id): bool
	{
		$id = $this->get_full_object_id($id);
		$found = false;
        /** @disregard */
		apcu_fetch($id, $found);
		return $found;
	}

	public function store(string $id, mixed $object): void
	{
		$this->add_apc_field($id);
		$id = $this->get_full_object_id($id);
        /** @disregard */
		apcu_store($id, $object);
	}

	public function delete(string $id): void
	{
		$this->delete_apc_field($id);
		$id = $this->get_full_object_id($id);
        /** @disregard */
		apcu_delete($id);
	}

	private function get_full_object_id(string $tiny_id): string
	{
		return $this->cache_id . '-' . $tiny_id;
	}

	private static function get_website_id(): string
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
				self::$website_id = TextHelper::substr(md5(realpath(PATH_TO_ROOT)), 0, 10);
				$file = new File($website_id_cache_file);
				$file->write(self::$website_id);
				$file->close();
			}
		}
		return self::$website_id;
	}

	public function clear(): void
	{
		// Copy the apc_fields array to avoid exceptions due to elements removed during foreach
		$apc_fields = $this->apc_fields;
		foreach ($apc_fields as $apc_field)
		{
			$this->delete($apc_field);
		}
	}

	private function retrieve_apc_fields(): void
	{
		if ($this->contains(self::$apc_fields_id))
		{
			$this->apc_fields = $this->get(self::$apc_fields_id);
		}
		else
		{
			$this->apc_fields = [];
		}
	}

	private function add_apc_field(string $field_name): void
	{
		if (!in_array($field_name, $this->apc_fields))
		{
			$this->apc_fields[] = $field_name;
			$this->store(self::$apc_fields_id, $this->apc_fields);
		}
	}

	private function delete_apc_field(string $field_name): void
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
