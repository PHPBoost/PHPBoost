<?php
/*##################################################
 *                              Event.class.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright            : (C) 2008 Benoît Sautel
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
 * @abstract
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc It's the common part between two types of event existing now in PHPBoost:
 * <ul>
 * 	<li>User contribution managed into the contribution panel</li>
 * 	<li>Administrator alert, triggered for example when a new update is available or when a new member account is to approbate</li>
 * </ul>
 */

class Event
{
	//Those are the different status of events
	//Unread event
	const EVENT_STATUS_UNREAD = 0;
	//Read event and beeing processed, somebody is focusing on it, but it's not processed
	const EVENT_STATUS_BEING_PROCESSED = 1;
	//Read and processed, it is normally not useful anymore
	const EVENT_STATUS_PROCESSED = 2;
	
	/**
	 * @protected int Numerical identifier of the event (in DB).
	 */
	protected $id = 0;

	/**
	 * @protected string Entitled (title or name) of the event.
	 */
	protected $entitled = '';

	/**
	 * @protected string URL where you can process the event (relative from the website root).
	 */
	protected $fixing_url = '';

	/**
	 * @protected int The event status.
	 */
	protected $current_status = self::EVENT_STATUS_UNREAD;

	/**
	 * @protected Date The event creation date.
	 */
	protected $creation_date;

	//The following attributes are used by the module developper to recognize his events
	/**
	 * @protected int Id corresponding to the event in the module (optionnal).
	 */
	protected $id_in_module = 0;

	/**
	 * @protected string Identifier to recognize the entry (optionnal).
	 */
	protected $identifier = '';

	/**
	 * @protected string Event type (optionnal).
	 */
	protected $type = '';

	/**
	 * @protected bool To know if the modifications implies to regenerate the cache (for instance whether the status has been changed).
	 */
	protected $must_regenerate_cache = true;

	/**
	 * @desc Builds an Event object.
	 */
	public function __construct()
	{
		$this->current_status = self::EVENT_STATUS_UNREAD;
		$this->creation_date = new Date();
	}

	/**
	 * @desc Sets the id of the event. The id is the corresponding data base entry one.
	 * @param int $id Id of the event.
	 */
	public function set_id($id)
	{
		if (is_int($id) && $id > 0)
		$this->id = $id;
	}

	/**
	 * @desc Sets the entitled of the event. The entitled can be considered as the name, it must be explicit.
	 * @param string $entitled The event entitiled.
	 */
	public function set_entitled($entitled)
	{
		$this->entitled = $entitled;
	}

	/**
	 * @desc Sets the URL corresponding to the event. For the contributions and the administrator alerts it's the number URL at which the problem can be solved.
	 * @param string $fixing_url Relative URL (the first character must be / for the root of the site).
	 */
	public function set_fixing_url($fixing_url)
	{
		$this->fixing_url = $fixing_url;
	}

	/**
	 * @desc Set the status of the event.
	 * @param int $new_current_status One of those elements:
	 * <ul>
	 * 	<li>Event::EVENT_STATUS_UNREAD if it's not read.</li>
	 * 	<li>Event::EVENT_STATUS_BEING_PROCESSED if the event is beeing processed</li>
	 * 	<li>Event::EVENT_STATUS_PROCESSED if the event is processed.
	 * </ul>
	 */
	public function set_status($new_current_status)
	{
		if (in_array($new_current_status, array(self::EVENT_STATUS_UNREAD, self::EVENT_STATUS_BEING_PROCESSED, self::EVENT_STATUS_PROCESSED), TRUE))
		{
			$this->current_status = $new_current_status;
		}
		//Default
		else
		{
			$this->current_status = self::EVENT_STATUS_UNREAD;
		}

		$this->must_regenerate_cache = true;
	}

	/**
	 * @desc Sets the creation date of the event.
	 * @param Date $date The creation date
	 */
	public function set_creation_date($date)
	{
		if (is_object($date) && $date instanceof Date)
		$this->creation_date = $date;
	}

	/**
	 * @desc Sets the id in module parameter. It corresponds to the id of the element corresponding to the event in your data base tables.
	 * For example, il you use the events to allow user to purpose some news in your web site, it will be the id of the news added.
	 * @param int $id Id in the module
	 */
	public function set_id_in_module($id)
	{
		$this->id_in_module = $id;
	}

	/**
	 * @desc Sets the event identifier. To retrieve your event, you might need to have a field in which you put some informations, for example a hash or an identifier.
	 * It's that identifier which can be used to filter the events. You don't have to use it, you can let it blank.
	 * @param string $identifier Identifier of the event.
	 */
	public function set_identifier($identifier)
	{
		$this->identifier = $identifier;
	}

	/**
	 * @desc Sets the type of the event. To retrieve your event, you might need to have a type of event, for example if your module has differents kinds of events. With this field, you can specify it.
	 * @param string $type The type of your event.
	 */
	public function set_type($type)
	{
		$this->type = $type;
	}

	/**
	 * @desc Sets a private property indicating if the changes made on this event imply the regeneration of the events cache.
	 * @param bool $must true if we must generate the events cache, otherwise false.
	 */
	public function set_must_regenerate_cache($must)
	{
		if (is_bool($must))
		$this->must_regenerate_cache = $must;
	}

	/**
	 * @desc Gets the id of the event (in the event data base).
	 * @return int The id.
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * @desc Returns the entitled of the event.
	 * @return string The entitled.
	 */
	public function get_entitled()
	{
		return $this->entitled;
	}

	/**
	 * @desc Returns the URL corresponding to the alert.
	 * @return string Relative URL whose first character is / for the website root.
	 */
	public function get_fixing_url()
	{
		return $this->fixing_url;
	}

	/**
	 * @desc Gets the status of the event. The status is one of those elements:
	 * ul>
	 * 	<li>Event::EVENT_STATUS_UNREAD if it's not read.</li>
	 * 	<li>Event::EVENT_STATUS_BEING_PROCESSED if the event is beeing processed</li>
	 * 	<li>Event::EVENT_STATUS_PROCESSED if the event is processed.
	 * </ul>
	 * @return int Status
	 */
	public function get_status()
	{
		return $this->current_status;
	}

	/**
	 * @desc Returns the creation date of the event.
	 * @return Date Creation date
	 */
	public function get_creation_date()
	{
		return $this->creation_date;
	}

	/**
	 * @desc Gets the id in the module. This value corresponds to the id of the daba base entry associated to the event.
	 * @return int The id in the module.
	 */
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}

	/**
	 * @desc Gets the identifier of the event. To retrieve your event, you might need to have a field in which you put some informations, for example a hash or an identifier.
	 * It's that identifier which can be used to filter the events.
	 * @return string The identifier of the event.
	 */
	public function get_identifier()
	{
		return $this->identifier;
	}

	/**
	 * @desc Gets the type of the event. To retrieve your event, you might need to have a type of event, for example if your module has differents kinds of events. With this field, you can specify it.
	 * @return string The type.
	 */
	public function get_type()
	{
		return $this->type;
	}

	/**
	 * @desc Gets the value indicating if the cache must be generated.
	 * @return bool true if the cache has to be generated, false else.
	 */
	public function get_must_regenerate_cache()
	{
		return $this->must_regenerate_cache;
	}

	/**
	 * @desc Gets the event status name. It's automatically translated in the user language.
	 * @return The name of the event status, ready to be displayed.
	 */
	public function get_status_name()
	{
		switch ($this->current_status)
		{
			case self::EVENT_STATUS_UNREAD:
				return LangLoader::get_message('contribution_status_unread', 'main');
			case self::EVENT_STATUS_BEING_PROCESSED:
				return LangLoader::get_message('contribution_status_being_processed', 'main');
			case self::EVENT_STATUS_PROCESSED:
				return LangLoader::get_message('contribution_status_processed', 'main');
		}
	}

	/**
	 * @desc Builds an event object from its whole parameters.
	 * @param int $id The event id.
	 * @param string $entitled The event entitled.
	 * @param string $fixing_url The URL corresponding to the event.
	 * @param int $current_status The event status.
	 * @param Date $creation_date The creation date.
	 * @param int $id_in_module The id of the object associated to the event.
	 * @param string $identifier The event identifier.
	 * @param string $type The event type.
	 */
	public function build_event($id, $entitled, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type)
	{
		$this->id = $id;
		$this->entitled = $entitled;
		$this->fixing_url = $fixing_url;
		$this->current_status = $current_status;
		$this->creation_date = $creation_date;
		$this->id_in_module = $id_in_module;
		$this->identifier = $identifier;
		$this->type = $type;
		$this->must_regenerate_cache = false;
	}
}
?>