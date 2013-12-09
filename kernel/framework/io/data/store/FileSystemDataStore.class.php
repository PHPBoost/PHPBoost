<?php
/*##################################################
 *                      FileSystemDataStore.class.php
 *                            -------------------
 *   begin                : January 11, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @desc This data store is not very efficient but is the only one which has an infinite life span
 * when APC is not available.
 * It stores data in the /cache folder.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
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
		$data = unserialize($content);
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
		$data_to_write = serialize($data);
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