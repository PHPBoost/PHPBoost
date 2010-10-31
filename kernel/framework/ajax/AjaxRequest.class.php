<?php
/*##################################################
 *                          AjaxRequest.class.php
 *                            -------------------
 *   begin                : October 31, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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
 *
 * @author Loic Rouchon <horn@phpboost.com>
 * @package {@package}
 */
class AjaxRequest implements View
{
	const GET = 'get';
	const POST = 'post';

	const ON_CREATE = 'onCreate';
	const ON_UNITIALIZED = 'onUninitialized';
	const ON_LOADING = 'onLoading';
	const ON_LOADED = 'onLoaded';
	const ON_INTERACTIVE = 'onInteractive';
	const ON_SUCCESS = 'onSuccess';
	const ON_FAILURE = 'onFailure';
	const ON_COMPLETE = 'onComplete';

	private $target;
	private $method = self::POST;
	private $events = array();
	private $parameters = array();

	public function __construct($target, $onsuccess, $onfailure = null)
	{
		$this->set_target($target);
		$this->set_onsuccess_callback($onsuccess);
		$this->set_onfailure_callback($onfailure);
	}

	public function set_target($target)
	{
		$this->target = Url::to_absolute($target);
	}

	public function set_method($method)
	{
		$this->method = $method;
	}

	public function set_onsuccess_callback($onsuccess)
	{
		$this->add_event_callback(self::ON_SUCCESS, $onsuccess);
	}

	public function set_onfailure_callback($onfailure = null)
	{
		if ($onfailure != null)
		{
			$this->add_event_callback(self::ON_FAILURE, $onfailure);
		}
		else
		{
			// TODO localize the default error message
			$this->add_event_callback(self::ON_FAILURE, 'function() {alert(\'ajax failure\');}');
		}
	}

	public function add_event_callback($event, $callback)
	{
		$this->events[$event] = $callback;
	}

	public function add_param($key, $value)
	{
		$this->parameters[$key] = $value;
	}
	public function render()
	{
		$tpl = $this->get_template();
		$this->assign($tpl);
		return $tpl->render();

	}
	private function get_template()
	{
		return new StringTemplate('new Ajax.Request(${escapejs(TARGET)},' .
			'{method:${escapejs(METHOD)},parameters:{# START param #{param.NAME}:{param.VALUE},# END #},' .
			'# START event #{event.NAME}:{event.CALLBACK},# END #});');
	}
	private function assign(Template $tpl)
	{
		$tpl->put('TARGET', $this->target);
		$tpl->put('METHOD', $this->method);
		$events = array();
		foreach ($this->events as $event => $callback)
		{
			$events[] = array('NAME' => $event, 'CALLBACK' => $callback);
		}
		$tpl->put('event', $events);
		$params = array();
		foreach ($this->parameters as $key => $value)
		{
			$params[] = array('NAME' => $key, 'VALUE' => $value);
		}
		$tpl->put('param', $params);
	}
}
?>