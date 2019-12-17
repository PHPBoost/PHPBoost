<?php
/**
 * This is a very efficient data store, but its principal weakness is that it's life span
 * is very short, in fact it's the page's execution.
 * It's to use when you know that the data you want to store will be accessed several times during
 * the page execution.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 01 11
 * @contributor Loic ROUCHON <horn@phpboost.com>
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
