<?php
/*##################################################
 *                        RAMDataStore.class.php
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
 * @desc This is a very efficient data store, but its principal weakness is that it's life span
 * is very short, in fact it's the page's execution.
 * It's to use when you know that the data you want to store will be accessed several times during
 * the page execution. 
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 *
 */
class RAMDataStore implements DataStore
{
	private $data = array();

	/**
	 * {@inheritdoc}
	 */
	public function get($id)
	{
		if ($this->contains($id))
		{
			return $this->data[$id];
		}
		throw new DataStoreException($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function contains($id)
	{
		return isset($this->data[$id]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function store($id, $data)
	{
		$this->data[$id] = $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id)
	{
		unset($this->data[$id]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function clear()
	{
		$this->data = array();
	}
}
?>