<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 26
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIDumpCommand implements CLICommand
{
    public function short_description()
    {
        return 'Dump database';
    }

    public function help(array $args)
    {
        CLIOutput::writeln('scenario: phpboost dump [file [tables ...] [--no-zip]]');
        CLIOutput::writeln('Dumps the database in a file (either the one specified as the first parameter or a default one).');
        CLIOutput::writeln('You can choose the tables you want to dump as of the second parameter. By default, the whole database will be dumped.');
        CLIOutput::writeln('Use the --no-zip flag to skip ZIP file creation.');
    }

    public function execute(array $args)
    {
        $create_zip = true;
        if (in_array('--no-zip', $args))
        {
            $create_zip = false;
            $args = array_diff($args, ['--no-zip']);
        }

        if (count($args) == 0)
        {
            $date = new Date();
            $file_name = PATH_TO_ROOT . '/cache/backup/dump_' . $date->get_year() . '-' . $date->get_month() . '-' . $date->get_day_two_digits() . '_' . $date->get_hours() . 'h' . $date->get_minutes() . '.sql';
        }
        else
        {
            $file_name = $args[0];
            array_shift($args);
        }

        $tables = null;
        foreach ($args as $arg)
        {
            $tables[] = $arg;
        }

        $this->dump($file_name, $tables, $create_zip);
    }

    private function dump(string $file_path, ?array $tables, bool $create_zip): void
    {
        Environment::try_to_increase_max_execution_time();
        $file = new File($file_path);
        $file_writer = new BufferedFileWriter($file);
        if ($tables === null)
        {
            PersistenceContext::get_dbms_utils()->dump_phpboost($file_writer, DBMSUtils::DUMP_STRUCTURE_AND_DATA);
        }
        else
        {
            PersistenceContext::get_dbms_utils()->dump_tables($file_writer, $tables, DBMSUtils::DUMP_STRUCTURE_AND_DATA);
        }

        if (file_exists($file_path))
        {
            CLIOutput::writeln('Tables dumped to SQL file: ' . $file_path);

            if ($create_zip)
            {
                $zip = new ZipArchive();
                $zip_path = str_replace('.sql', '.zip', $file_path);

                if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE)
                {
                    $zip->addFile($file_path, basename($file_path));
                    $zip->close();
                    CLIOutput::writeln('Tables dumped and zipped to file: ' . $zip_path);
                }
                else
                {
                    CLIOutput::writeln('Failed to create ZIP file.');
                }
            }
        }
        else
        {
            CLIOutput::writeln('Failed to create SQL file.');
        }
    }
}
?>
