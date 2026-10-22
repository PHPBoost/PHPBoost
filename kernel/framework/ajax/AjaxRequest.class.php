<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 10 31
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AjaxRequest implements View
{
	const GET  = 'get';
	const POST = 'post';

	const BEFORE_SEND   = 'beforeSend';
	const AJAX_SEND     = 'ajaxSend';
	const SUCCESS       = 'success';
	const AJAX_SUCCESS  = 'ajaxSuccess';
	const ERROR         = 'error';
	const AJAX_ERROR    = 'ajaxError';
	const COMPLETE      = 'complete';
	const AJAX_COMPLETE = 'ajaxComplete';

	private string $target;
	private string $method    = self::POST;
	private array $events     = [];
	private array $parameters = [];

	public function __construct(string|Url $target, string $onsuccess, ?string $onfailure = null)
	{
		$this->set_target($target);
		$this->set_onsuccess_callback($onsuccess);
		$this->set_onfailure_callback($onfailure);
	}

	public function set_target(string|Url $target): void
	{
		$this->target = Url::to_rel($target);
	}

	public function set_method(string $method): void
	{
		$this->method = $method;
	}

	public function set_onsuccess_callback(string $onsuccess): void
	{
		$this->add_event_callback(self::SUCCESS, $onsuccess);
	}

	public function set_onfailure_callback(?string $onfailure = null): void
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

	public function add_event_callback(string $event, string $callback):void
	{
		$this->events[$event] = $callback;
	}

	public function add_param(string $key, mixed $value): void
	{
		$this->parameters[$key] = $value;
	}

	public function render(): string
	{
		$tpl = $this->get_template();
		$this->assign($tpl);
		return $tpl->render();
	}

	private function get_template(): StringTemplate
	{
		return new StringTemplate('jQuery.ajax({
			url: ${escapejs(TARGET)},
			type: ${escapejs(METHOD)},
			data: {# START param #{param.NAME}:{param.VALUE},# END #},
			# START event #{event.NAME}:{event.CALLBACK},# END #
		});');
	}

	private function assign(Template $tpl): void
	{
		$tpl->put('TARGET', $this->target);
		$tpl->put('METHOD', $this->method);
		$events = [];
		foreach ($this->events as $event => $callback)
		{
			$events[] = ['NAME' => $event, 'CALLBACK' => $callback];
		}
		$tpl->put('event', $events);
		$params = [];
		foreach ($this->parameters as $key => $value)
		{
			$params[] = ['NAME' => $key, 'VALUE' => $value];
		}
		$tpl->put('param', $params);
	}
}
?>
