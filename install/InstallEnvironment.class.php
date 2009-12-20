<?php
/*##################################################
 *                          environment.class.php
 *                            -------------------
 *   begin                : September 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loïc Rouchon
 *   email                : ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';

class InstallEnvironment
{
    public static function load_imports()
    {
        try
        {
            Environment::load_imports();
        }
        catch(Exception $ex)
        {
        }
    }

    public static function init()
    {
        Environment::fit_to_php_configuration();
        Environment::load_static_constants();
        Environment::write_http_headers();
        self::load_dynamic_constants();
        Environment::init_output_bufferization();
    }

    public static function load_dynamic_constants()
    {
        define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
        $server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
        define('FILE', $server_path);
        define('DIR', str_replace('/install/install.php', '', $server_path));
        define('SID', '');
        define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);
    }

    public static function load_lang($prefered_lang)
    {
        global $LANG;

        if (!@include_once('lang/' . $prefered_lang . '/install_' . $prefered_lang . '.php'))
        {
            include_once('lang/' . DEFAULT_LANGUAGE . '/install_' . DEFAULT_LANGUAGE . '.php');
            $prefered_lang = DEFAULT_LANGUAGE;
        }
        @include_once(PATH_TO_ROOT . '/lang/' . $prefered_lang . '/errors.php');
    }

    public static function load_distribution_properties($prefered_lang)
    {
        global $DISTRIBUTION_MODULES;

        //If the distribution properties exist in the prefered language
        if (is_file('distribution/distribution_' . $prefered_lang . '.php'))
        {
            //We load them
            include('distribution/distribution_' . $prefered_lang . '.php');
        }
        else
        {
            //We try to load another lang

            $distribution_folder = new Folder('distribution');
            $distribution_files = $distribution_folder->get_files('`distribution_[a-z_-]+\.php`i');
            if (count($distribution_files) > 0)
            {
                include('distribution/distribution_' . $distribution_files[0]->get_name() . '.php');
            }
            else
            {
                //We couldn't load anything, we just have to define them to default values
                //Name of the distribution (localized)
                define('DISTRIBUTION_NAME', 'Default distribution');

                //Description of the distribution (localized)
                define('DISTRIBUTION_DESCRIPTION', 'This distribution is the default distribution. You will manage to install PHPBoost with the default configuration but it will install only the kernel without any module.');

                //Distribution default theme
                define('DISTRIBUTION_THEME', 'base');

                //Home page
                define('DISTRIBUTION_START_PAGE', '/member/member.php');

                //Can people register?
                define('DISTRIBUTION_ENABLE_USER', false);

                //Debug mode?
                define('DISTRIBUTION_ENABLE_DEBUG_MODE', true);
                
                //Enable bench?
                define('DISTRIBUTION_ENABLE_BENCH', false);

                //Modules list
                $DISTRIBUTION_MODULES = array();
            }
        }
    }

    public static function destroy()
    {
        ob_end_flush();
    }
}
?>