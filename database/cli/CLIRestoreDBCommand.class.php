<?php
/**
 * @copyright   © 2005-2026 PHPBoost
 * @license     [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html) GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2019 04 03
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIRestoreDBCommand implements CLICommand
{
    public function short_description(): string
    {
        return 'Restore database';
    }

    public function help(array $args): void
    {
        CLIOutput::writeln('scenario: phpboost restoredb file');
        CLIOutput::writeln('Restores the database from a dump (sql file or in a zip archive).');
        CLIOutput::writeln('Warning, a dump is DBMS specific. Be sure that the dump you are restoring corresponds to the one to which this server is linked to.');
    }

    public function execute(array $args): void
    {
        if (count($args) === 0)
        {
            CLIOutput::writeln('Error: please enter an input file.');
            return;
        }

        if (count($args) > 1)
        {
            CLIOutput::writeln('Warning: there are several parameters to the command whereas it accepts only one. The other ones will be ignored.');
        }

        $file_name = $args[0];
        if (!preg_match('`[^/]+\.sql$`u', $file_name) && !preg_match('`[^/]+\.zip$`u', $file_name))
        {
            CLIOutput::writeln('Error: please enter a link to a sql or zip input file.');
            return;
        }

        try
        {
            if ($this->restore_db($file_name))
            {
                $this->optimize_tables();
                $this->clear_caches();
            }
            else
            {
                CLIOutput::writeln('Error: the file you want to import is not the backup of this site, impossible to restore.');
            }
        }
        catch (IOException $ex)
        {
            CLIOutput::writeln('Error: the file you want to import doesn\'t exist or is not readable.');
        }
        catch (Exception $ex)
        {
            CLIOutput::writeln('Error: ' . $ex->getMessage());
        }
    }

    private function restore_db(string $file_name): bool
    {
        Environment::try_to_increase_max_execution_time();
        $original_file_compressed = false;
        $file_to_restore = '';

        if (preg_match('`[^/]+\.zip$`u', $file_name))
        {
            $original_file_compressed = true;
            $extract_filename = '';

            // Remplacement de PclZip par ZipArchive natif
            $zip = new ZipArchive();
            if ($zip->open($file_name) === TRUE)
            {
                // Recherche du fichier SQL dans l'archive
                for ($i = 0; $i < $zip->numFiles; $i++)
                {
                    $filename = $zip->getNameIndex($i);
                    if (preg_match('`[^/]+\.sql$`u', $filename))
                    {
                        $extract_filename = $filename;
                        break;
                    }
                }

                $zip->close();

                if ($extract_filename)
                {
                    // Réouverture pour extraction
                    if ($zip->open($file_name) === TRUE)
                    {
                        $sql_content = $zip->getFromName($extract_filename);
                        $zip->close();

                        if ($sql_content !== false && strlen($sql_content) > 0)
                        {
                            // Création du fichier temporaire
                            $file_to_restore = PATH_TO_ROOT . '/' . basename($extract_filename);
                            $temp_file = new File($file_to_restore);
                            $temp_file->write($sql_content);

                            // Vérification avec les bonnes méthodes PHPBoost File
                            if (!$temp_file->exists() || filesize($file_to_restore) == 0)
                            {
                                CLIOutput::writeln('Error: extracted SQL file is empty: ' . $file_to_restore);
                                $temp_file->delete();
                                return false;
                            }
                        }
                        else
                        {
                            CLIOutput::writeln('Error: failed to extract SQL content from ZIP or content is empty.');
                            return false;
                        }
                    }
                    else
                    {
                        CLIOutput::writeln('Error: cannot reopen ZIP archive for extraction.');
                        return false;
                    }
                }
                else
                {
                    CLIOutput::writeln('Error: the zip file does not contain any SQL dump to restore.');
                    return false;
                }
            }
            else
            {
                CLIOutput::writeln('Error: cannot open ZIP file: ' . $file_name);
                return false;
            }
        }
        else
        {
            $file_to_restore = $file_name;

            // Vérification avec les bonnes méthodes PHPBoost File
            $file = new File($file_to_restore);
            if (!$file->exists() || filesize($file_to_restore) == 0)
            {
                CLIOutput::writeln('Error: SQL file is empty or does not exist: ' . $file_to_restore);
                return false;
            }
        }

        if ($file_to_restore)
        {
            $return = PersistenceContext::get_dbms_utils()->parse_file(new File($file_to_restore));

            if ($original_file_compressed)
            {
                $file = new File($file_to_restore);
                $file->delete();
            }

            if ($return)
            {
                CLIOutput::writeln('Dump restored from file ' . $file_name);
            }

            return $return;
        }

        return false;
    }

    private function optimize_tables(): void
    {
        $db_utils = PersistenceContext::get_dbms_utils();
        $tables = $db_utils->list_tables();
        $db_utils->optimize($tables);
        $db_utils->repair($tables);
        CLIOutput::writeln('Database optimized');
    }

    private function clear_caches(): void
    {
        $cache_service = AppContext::get_cache_service();
        $cache_service->clear_phpboost_cache();
        $cache_service->clear_syndication_cache();
        CLIOutput::writeln('Caches cleared');
    }
}
?>
