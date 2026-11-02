<?php
/**
 * @package     Util
 * @subpackage  Captcha
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2012 09 04
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class Captcha implements ExtensionPoint
{
	const EXTENSION_POINT = 'captcha';

	private string $html_id = 'captcha';
	private string $form_id = '';
	private ?CaptchaOptions $options;

	abstract public function get_name(): mixed;

	abstract public function is_available(): mixed;

	abstract public function is_valid(): mixed;

	abstract public function display(): mixed;

	public function get_error(): void
	{
		return;
	}

	public function is_visible(): bool
	{
		return true;
	}

	public static function display_config_form_fields(FormFieldset $fieldset): mixed
	{
		return null;
	}

	public static function save_config(HTMLForm $form): void
	{
		return;
	}

	public static function get_css_stylesheet(): mixed
	{
		return null;
	}

	public function set_html_id(string $html_id): void
    {
        $this->html_id = $html_id;
    }

	public function get_html_id(): string
    {
        return $this->html_id;
    }

	public function set_form_id(string $form_id): void
    {
        $this->form_id = $form_id;
    }

	public function get_form_id(): string
    {
        return $this->form_id;
    }

	public function set_options(CaptchaOptions $options): void
    {
        $this->options = $options;
    }

	public function get_options(): ?CaptchaOptions
    {
        return $this->options;
    }

}
?>
