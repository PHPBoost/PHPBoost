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

class CaptchaService
{
    private ?Captcha $default_factory = null;

    public function get_default_factory(): ?Captcha
    {
        if ($this->default_factory === null)
        {
            $default_captcha = $this->get_default_captcha();
            $this->default_factory = $this->create_factory($default_captcha);

            // Fallback to first available captcha if configured one doesn't exist
            if ($this->default_factory === null)
            {
                $available_captchas = $this->get_available_captchas();
                if (!empty($available_captchas))
                {
                    $first_available = key($available_captchas);
                    $this->default_factory = $this->create_factory($first_available);
                }
            }
        }
        return $this->default_factory;
    }

    public function create_factory(string $identifier = ''): ?Captcha
    {
        $captcha = $this->get_existing_captcha($identifier);
        return CaptchaProvidersService::create_factory($captcha);
    }

    public function get_default_captcha(): string
    {
        return ContentManagementConfig::load()->get_used_captcha_module();
    }

    public function is_available(): bool
    {
        return $this->get_default_factory()->is_available();
    }

    public function is_valid(): bool
    {
        return $this->get_default_factory()->is_valid();
    }

    public function display(): string
    {
        return $this->get_default_factory()->display();
    }

    private function get_existing_captcha(string $captcha): string
    {
        if (in_array($captcha, self::get_captchas_identifier()))
        {
            return $captcha;
        }
        else
        {
            return $this->get_default_captcha();
        }
    }

    public function get_captchas_identifier(): array
    {
        return array_keys(CaptchaProvidersService::get_captchas());
    }

    public function get_available_captchas(): array
    {
        $available_captchas = [];
        foreach (CaptchaProvidersService::get_captchas() as $id => $provider)
        {
            if ($provider->is_available())
            {
                $available_captchas[$id] = $provider->get_name();
            }
        }
        return $available_captchas;
    }

    public function uninstall_captcha(string $id_module): ?string
    {
        $captchas = $this->get_available_captchas();

        if (array_key_exists($id_module, $captchas))
        {
            if (count($captchas) > 1)
            {
                $default_captcha = $this->get_default_captcha();
                if ($default_captcha === $id_module)
                {
                    return LangLoader::get_message('warningcaptcha.is.default', 'warning-lang');
                }

                return null;
            }
            return LangLoader::get_message('warningcaptcha.last.installed', 'warning-lang');
        }
        return null;
    }
}
?>
