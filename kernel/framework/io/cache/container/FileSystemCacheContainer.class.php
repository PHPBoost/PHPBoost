<?php
/*##################################################
 *                      FileSystemCacheContainer.class.php
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
 * @package io
 * @subpackage cache/container
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class FileSystemCacheContainer implements CacheContainer
{
	private $prefix;
	/**
	 * @var RAMCacheContainer
	 */
	private $files_cache;

	public function __construct($id)
	{
		$this->prefix = $id;
		$this->files_cache = new RAMCacheContainer();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/container/CacheContainer#get($id)
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
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/container/CacheContainer#contains($id)
	 */
	public function contains($id)
	{
		return $this->get_file($id)->exists();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/container/CacheContainer#store($id, $object)
	 */
	public function store($id, $data)
	{
		$file = $this->get_file($id);
		$data_to_write = serialize($data);
		$file->write($data_to_write);
		$file->close();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/container/CacheContainer#delete($id)
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
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/container/CacheContainer#clear()
	 */
	public function clear()
	{
		$cache_dir = new Folder(PATH_TO_ROOT . '/cache');
		foreach ($cache_dir->get_files('^`' . $this->prefix . '-.*`') as $file)
		{
			$file->delete();
		}
	}

	/**
	 *
	 * @param $id
	 * @return File
	 */
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