<?php
/**
 * This is a very efficient data store, but its principal weakness is that it's life span
 * is very short, in fact it's the page's execution.
 * It's to use when you know that the data you want to store will be accessed several times during
 * the page execution.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2011 01 11
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class RAMDataStore implements DataStore
{
	private array $data = [];

	public function get(string $id): mixed
	{
		if ($this->contains($id))
		{
			return $this->data[$id];
		}
		throw new DataStoreException($id);
	}

	public function contains(string $id): bool
	{
		return isset($this->data[$id]);
	}

	public function store(string $id, mixed $data): void
	{
		$this->data[$id] = $data;
	}

	public function delete(string $id): void
	{
		unset($this->data[$id]);
	}

	public function clear(): void
	{
		$this->data = [];
	}
}
?>
