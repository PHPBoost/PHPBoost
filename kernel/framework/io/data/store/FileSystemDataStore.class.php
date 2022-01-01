<?php
/**
 * This data store is not very efficient but is the only one which has an infinite life span
 * when APC is not available.
 * It stores data in the /cache folder.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 30
 * @since       PHPBoost 3.0 - 2011 01 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FileSystemDataStore implements DataStore
{
	private $prefix;
	/**
	 * @var RAMDataStore
	 */
	private $files_cache;

	public function __construct($id)
	{
		$this->prefix = $id;
		$this->files_cache = new RAMDataStore();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($id)
	{
		if ($this->contains($id))
		{
			return $this->get_data($id);
		}
		throw new RAMCacheException($id);
	}

	private function get_data($name)
	{
		$file = $this->get_file($name);
		$content = $file->read();
		$data = TextHelper::unserialize($content);
		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function contains($id)
	{
		return $this->get_file($id)->exists();
	}

	/**
	 * {@inheritdoc}
	 */
	public function store($id, $data)
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

	/**
	 * {@inheritdoc}
	 */
	public function delete($id)
	{
		try
		{
			$this->get_file($id)->delete();
		}
		catch(IOException $ex)
		{
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear()
	{
		$cache_dir = new Folder(PATH_TO_ROOT . '/cache');
		$files = $cache_dir->get_files('`^' . $this->prefix . '-.*`');
		foreach ($files as $file)
		{
			$file->delete();
		}
	}

	private function get_file($id)
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
