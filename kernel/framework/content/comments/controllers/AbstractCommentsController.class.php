<?php
/**
 * @package     Content
 * @subpackage  Comments\controllers
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 3.0 - 2011 09 23
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AbstractCommentsController extends AbstractController
{
	protected string $module_id = '';
	protected int $id_in_module;
	protected mixed $topic_identifier = null;
	protected mixed $provider = null;

	public function execute(HTTPRequestCustom $request): mixed
	{
		$this->module_id = $request->get_poststring('module_id', '');
		$this->id_in_module = $request->get_poststring('id_in_module', '');
		$this->topic_identifier = $request->get_poststring('topic_identifier', '');
		$this->provider = CommentsProvidersService::get_provider($this->module_id, $this->topic_identifier);

		if (!empty($this->provider))
        {
			$this->provider->set_id_in_module($this->id_in_module);
        }
        return null;
	}

	public function is_authorized_read(): bool
	{
		return $this->get_authorizations()->is_authorized_read();
	}

	public function is_displayed(): bool
	{
		return !empty($this->provider) && $this->provider->is_displayed();
	}

	public function get_module_id(): string
	{
		return $this->module_id;
	}

	public function get_id_in_module(): int
	{
		return $this->id_in_module;
	}

	public function get_topic_identifier(): int
	{
		return $this->topic_identifier;
	}

	public function get_authorizations(): mixed
	{
		return !empty($this->provider) ? $this->provider->get_authorizations($this->get_module_id(), $this->get_id_in_module()) : new CommentsAuthorizations();
	}
}
?>
