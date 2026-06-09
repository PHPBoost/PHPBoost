<?php
/**
 * File System helper
 * @package     Helper
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2019 02 14
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FileSystemHelper
{
    /**
     * Remove a folder and its content. The folder can be kept and only emptied.
     */
    public static function remove_folder(string $folder, bool $protect_folder = false, string $first_folder_name = '', string $parent_folder = ''): bool
    {
        if ($protect_folder && empty($first_folder_name)) {
            $first_folder_name = $folder;
        }

        if (empty($parent_folder)) {
            $parent_folder = $folder;
        }

        if (!preg_match("/^.*\/$/", $folder)) {
            $folder .= '/';
        }

        $handle = @opendir($folder);

        if ($handle !== false) {
            while ($item = readdir($handle)) {
                $protect_folder_delete = $protect_folder ? !preg_match('/' . $first_folder_name . '/', $item) : true;

                if ($item != "." && $item != ".." && $protect_folder_delete) {
                    $full_path = $folder . $item;
                    if (is_dir($full_path)) {
                        self::remove_folder($full_path, $protect_folder, $first_folder_name, $parent_folder);
                    } else {
                        unlink($full_path);
                    }
                }
            }

            closedir($handle);

            $protect_folder_delete = $protect_folder ? !preg_match('/' . $first_folder_name . '/', $folder) : true;
            if ($protect_folder_delete) {
                $result = rmdir($folder);
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Copy a folder from a source path to a destination.
     */
    public static function copy_folder(string $source_path, string $destination_path): void
    {
        if (!preg_match("/^.*\/$/", $source_path)) {
            $source_path .= '/';
        }

        if (!preg_match("/^.*\/$/", $destination_path)) {
            $destination_path .= '/';
        }

        if (is_dir($source_path)) {
            if ($dh = opendir($source_path)) {
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($destination_path)) {
                        mkdir($destination_path, 0755);
                    }

                    if (is_dir($source_path . $file) && $file != '..' && $file != '.') {
                        self::copy_folder($source_path . $file . '/', $destination_path . $file . '/');
                    } elseif ($file != '..' && $file != '.') {
                        copy($source_path . $file, $destination_path . $file);
                    }
                }

                closedir($dh);
            }
        }
    }

    /**
     * Download a file from a remote url with md5 sum check and extract it if needed in case of a zip file. Curl library needed.
     */
    public static function download_remote_file(string $url, string $destination_path, bool $extract_archive = true, bool $retry = false): bool
    {
        if (!preg_match("/^.*\/$/", $destination_path)) {
            $destination_path .= '/';
        }

        $server_configuration = new ServerConfiguration();

        if (!$server_configuration->has_curl_extension() || !Url::check_url_validity($url)) {
            return false;
        }

        $file_basename = basename($url);
        $file_extension = pathinfo($file_basename, PATHINFO_EXTENSION);
        $file_name = PATH_TO_ROOT . '/cache/' . $file_basename;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $downloaded_size = (int)curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD) ?? false;

        if (\PHP_VERSION_ID < 80100) {
            curl_close($ch);
        }

        file_put_contents($file_name, $content);

        if ($code === 200 && $downloaded_size) {
            $original_size = File::get_remote_file_size($url);
            $file = new File($file_name);

            if ($file->exists() && $downloaded_size === $original_size && $original_size === $file->get_file_size()) {
                if ($extract_archive && $file_extension === 'zip') {
                    if ($server_configuration->has_zip_extension()) {
                        $zip_archive = new ZipArchive();
                        if ($zip_archive->open($file_name) === true) {
                            $zip_archive->extractTo($destination_path);
                            $zip_archive->close();
                            unlink($file_name);
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
                return true;
            }
        }

        if (!$retry) {
            return self::download_remote_file($url, $destination_path, $extract_archive, true);
        }

        return false;
    }

    /**
     * Get content of a remote file. Return false if remote file doesn't exist or curl library isn't activated on server.
     */
    public static function get_remote_file_content(string $url): ?string
    {
        $server_configuration = new ServerConfiguration();

        if ($server_configuration->has_curl_extension() && Url::check_url_validity($url)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
            $content = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $downloaded_size = (int)curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD) ?? false;

            if (\PHP_VERSION_ID < 80100) {
                curl_close($ch);
            }

            if ($code === 200 && $downloaded_size) {
                return $content;
            }
        }

        return null;
    }
}
