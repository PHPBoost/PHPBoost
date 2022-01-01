<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 01 06
 * @since       PHPBoost 3.0 - 2010 10 31
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class AjaxRequest implements View
{
	const GET = 'get';
	const POST = 'post';

	const BEFORE_SEND = 'beforeSend';
	const AJAX_SEND = 'ajaxSend';
	const SUCCESS = 'success';
	const AJAX_SUCCESS = 'ajaxSuccess';
	const ERROR = 'error';
	const AJAX_ERROR = 'ajaxError';
	const COMPLETE = 'complete';
	const AJAX_COMPLETE = 'ajaxComplete';

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
		$this->target = Url::to_rel($target);
	}

	public function set_method($method)
	{
		$this->method = $method;
	}

	public function set_success_callback($onsuccess)
	{
		$this->add_event_callback(self::SUCCESS, $onsuccess);
	}

	public function set_failure_callback($onfailure = null)
	{
		if ($onfailure != null)
		{
			$this->add_event_callback(self::ERROR, $onfailure);
		}
		else
		{
			// TODO localize the default error message
			$this->add_event_callback(self::ERROR, 'function() {alert(\'ajax failure\');}');
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
		return new StringTemplate('jQuery.ajax({
			url: ${escapejs(TARGET)},
			type: ${escapejs(METHOD)},
			data: {# START param #{param.NAME}:{param.VALUE},# END #},
			# START event #{event.NAME}:{event.CALLBACK},# END #
		});');
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
