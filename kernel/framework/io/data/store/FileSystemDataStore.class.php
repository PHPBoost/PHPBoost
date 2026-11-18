<?php
/**
 * This data store is not very efficient but is the only one which has an infinite life span
 * when APC is not available.
 * It stores data in the /cache folder.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2011 01 11
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FileSystemDataStore implements DataStore
{
	private string $prefix = '';
	private RAMDataStore $files_cache;

	public function __construct(object|string $id)
	{
		$this->prefix = $id;
		$this->files_cache = new RAMDataStore();
	}

	public function get(string $id): mixed
	{
		if ($this->contains($id))
		{
			return $this->get_data($id);
		}
		throw new DataStoreException($id);
	}

	private function get_data(string $name): mixed
	{
		$file = $this->get_file($name);
		$content = $file->read();
		try
		{
			$data = TextHelper::unserialize($content);
		}
		catch (TypeError $ex)
		{
			$file->delete();
			throw new DataStoreException($name);
		}
		// If the class definition was missing at unserialize time (e.g. during
		// an update where module paths changed), TextHelper::unserialize returns
		// a __PHP_Incomplete_Class. Treat it as a cache miss.
		if ($data instanceof \__PHP_Incomplete_Class)
		{
			$file->delete();
			throw new DataStoreException($name);
		}
		return $data;
	}

	public function contains(string $id): bool
	{
		return $this->get_file($id)->exists();
	}

	public function store(string $id, mixed $data): void
	{
		$file = $this->get_file($id);
		$data_to_write = TextHelper::serialize($data);
		$file->open(File::WRITE);
		$file->lock();
		$file->write($data_to_write);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}

	public function delete(string $id): void
	{
		try
		{
			$this->get_file($id)->delete();
		}
		catch(IOException $ex) {}
	}

	public function clear(): void
	{
		$cache_dir = new Folder(PATH_TO_ROOT . '/cache');
		$files = $cache_dir->get_files('`^' . $this->prefix . '-.*`');
		foreach ($files as $file)
		{
			$file->delete();
		}
	}

	private function get_file(string $id): File
	{
		if ($this->files_cache->contains($id))
		{
			return $this->files_cache->get($id);
		}
		else
		{
			$file = new File(PATH_TO_ROOT . '/cache/' . $this->prefix . '-' . $id);
			$this->files_cache->store($id, $file);
			return $file;
		}
	}
}
?>
