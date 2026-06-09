<?php
/**
 * Static helper centralising addon archive handling, locale resolution,
 * remote download+install and remote download+update.
 *
 * Replaces duplicated private methods previously spread across
 * AdminModuleAddController, AdminThemeAddController, AdminLangAddController
 * and AdminModuleUpdateController.
 *
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 04 18
 */

class AddonHelper
{
    // -------------------------------------------------------------------------
    // Archive — listing
    // -------------------------------------------------------------------------

    /**
     * Returns a flat list of entries in a .zip archive.
     * Each entry: ['filename' => string, 'folder' => 0|1]
     */
    public static function list_zip_content(string $archive): array
    {
        $content = [];
        $zip = new ZipArchive();
        if ($zip->open($archive) === true)
        {
            for ($i = 0; $i < $zip->numFiles; $i++)
            {
                $stat      = $zip->statIndex($i);
                $content[] = [
                    'filename' => $stat['name'],
                    'folder'   => (TextHelper::substr($stat['name'], -1) === '/') ? 1 : 0,
                ];
            }
            $zip->close();
        }
        return $content;
    }

    /**
     * Returns a flat list of entries in a .tar.gz archive.
     * Each entry: ['filename' => string, 'folder' => 0|1, 'typeflag' => int]
     */
    public static function list_tar_gz_content(string $archive): array
    {
        $content = [];
        try
        {
            $phar = new PharData($archive);
            foreach (new RecursiveIteratorIterator($phar) as $file)
            {
                $relative  = str_replace('phar://' . $archive . '/', '', $file->getPathname());
                $content[] = ['filename' => $relative, 'folder' => 0];
            }
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('phar://' . $archive), RecursiveIteratorIterator::SELF_FIRST) as $file)
            {
                if ($file->isDir())
                {
                    $relative  = str_replace('phar://' . $archive . '/', '', $file->getPathname()) . '/';
                    $content[] = ['filename' => $relative, 'folder' => 1, 'typeflag' => 5];
                }
            }
        }
        catch (Exception $e) {}
        return $content;
    }

    // -------------------------------------------------------------------------
    // Archive — extraction
    // -------------------------------------------------------------------------

    public static function extract_zip(string $archive, string $destination): void
    {
        $zip = new ZipArchive();
        if ($zip->open($archive) === true)
        {
            $zip->extractTo($destination);
            $zip->close();
            AddonRemoteHelper::apply_permissions($destination, 0755);
        }
    }

    public static function extract_tar_gz(string $archive, string $destination): void
    {
        try
        {
            $phar = new PharData($archive);
            $phar->extractTo($destination, null, true);
            AddonRemoteHelper::apply_permissions($destination, 0755);
        }
        catch (Exception $e) {}
    }

    // -------------------------------------------------------------------------
    // Locale field resolution (for remote JSON indexes)
    // -------------------------------------------------------------------------

    /**
     * Resolves a possibly-multilingual field from a remote JSON index entry.
     *
     * The field value may be:
     *   - a plain string  → returned as-is
     *   - an array        → current locale, then 'english', then first value
     *
     * @param array  $entry   One item from modules.json / themes.json / langs.json
     * @param string $field   Field name ('name', 'description', 'genre', …)
     * @param string $locale  Current user locale (e.g. 'french')
     * @param string $default Fallback value
     */
    public static function resolve_locale_field(array $entry, string $field, string $locale, string $default): string
    {
        if (!isset($entry[$field]))
            return $default;

        $value = $entry[$field];

        if (is_string($value))
            return $value;

        if (is_array($value))
        {
            if (isset($value[$locale]))   return $value[$locale];
            if (isset($value['english'])) return $value['english'];
            $first = reset($value);
            return is_string($first) ? $first : $default;
        }

        return $default;
    }

    // -------------------------------------------------------------------------
    // Remote download helpers (shared low-level logic)
    // -------------------------------------------------------------------------

    /**
     * Downloads and extracts one addon folder from GitHub into $dest_dir.
     *
     * @param  string $owner   GitHub repository owner
     * @param  string $repo    Repository name
     * @param  string $subdir  Optional subdirectory inside the repository
     * @param  string $addon_id   Addon identifier (folder name)
     * @param  string $dest_dir   Absolute path of the destination parent folder
     *                            (e.g. PATH_TO_ROOT . '/modules/')
     * @param  string $version    PHPBoost major version
     * @param  string $token      GitHub token (may be empty)
     * @return bool              true on success
     */
    public static function download_from_github(
        string $owner,
        string $repo,
        string $subdir,
        string $addon_id,
        string $dest_dir,
        string $version,
        string $token
    ): bool
    {
        $branch       = AddonRemoteHelper::resolve_github_branch($owner, $repo, $version, $token);
        $path         = trim($subdir, '/');
        $addon_prefix = $repo . '-' . $branch . '/' . ($path !== '' ? $path . '/' : '') . $addon_id . '/';

        return AddonRemoteHelper::download_and_extract_from_github(
            $owner, $repo, $branch, $addon_prefix,
            rtrim($dest_dir, '/') . '/' . $addon_id . '/',
            $token
        );
    }

    /**
     * Downloads and extracts one addon zip from a website server into $dest_dir.
     *
     * @param  string $addon_id    Addon identifier
     * @param  string $server_url  Base URL of the addon server
     * @param  string $server_dir  Optional subdirectory on the server
     * @param  string $addon_type  'modules', 'themes' or 'langs'
     * @param  string $dest_dir    Absolute path of the destination parent folder
     * @param  string $version     PHPBoost major version
     * @return bool               true on success
     */
    public static function download_from_website(
        string $addon_id,
        string $server_url,
        string $server_dir,
        string $addon_type,
        string $dest_dir,
        string $version
    ): bool
    {
        list($base_url) = AddonRemoteHelper::fetch_website_index($server_url, $server_dir, $version, $addon_type);
        if (empty($base_url))
            return false;

        $zip_content = AddonRemoteHelper::curl_get(
            $base_url . '/' . rawurlencode($addon_id) . '/' . rawurlencode($addon_id) . '.zip',
            [], 60
        );
        if ($zip_content === false)
            return false;

        $dest = rtrim($dest_dir, '/') . '/' . $addon_id . '/';
        $ok   = AddonRemoteHelper::extract_zip_prefix_to($zip_content, $addon_id . '/', $dest);
        if (!$ok)
            $ok = AddonRemoteHelper::extract_zip_prefix_to($zip_content, '', $dest);

        return $ok;
    }

    // -------------------------------------------------------------------------
    // Remote install
    // -------------------------------------------------------------------------

    /**
     * Downloads from GitHub then calls $install_callback($addon_id).
     * Returns ['success' => bool, 'msg' => string (lang key)].
     */
    public static function install_from_github(
        string   $addon_id,
        string   $owner,
        string   $repo,
        string   $subdir,
        string   $dest_dir,
        string   $already_installed_key,
        callable $is_installed_fn,
        callable $install_fn
    ): array
    {
        $version = GeneralConfig::load()->get_phpboost_major_version();
        $token   = AddonsConfig::load()->get_github_token();
        $lang    = LangLoader::get_all_langs();

        if (!is_writable($dest_dir) && !@chmod($dest_dir, 0755))
            return ['success' => false, 'msg' => $lang['warning.process.error']];

        if ($is_installed_fn($addon_id))
            return ['success' => false, 'msg' => $lang[$already_installed_key]];

        if (!self::download_from_github($owner, $repo, $subdir, $addon_id, $dest_dir, $version, $token))
            return ['success' => false, 'msg' => $lang['addon.warning.download.error']];

        return $install_fn($addon_id);
    }

    /**
     * Downloads from a website server then calls $install_callback($addon_id).
     */
    public static function install_from_website(
        string   $addon_id,
        string   $server_url,
        string   $server_dir,
        string   $addon_type,
        string   $dest_dir,
        string   $already_installed_key,
        callable $is_installed_fn,
        callable $install_fn
    ): array
    {
        $version = GeneralConfig::load()->get_phpboost_major_version();
        $lang    = LangLoader::get_all_langs();

        if (!is_writable($dest_dir) && !@chmod($dest_dir, 0755))
            return ['success' => false, 'msg' => $lang['warning.process.error']];

        if ($is_installed_fn($addon_id))
            return ['success' => false, 'msg' => $lang[$already_installed_key]];

        if (!self::download_from_website($addon_id, $server_url, $server_dir, $addon_type, $dest_dir, $version))
            return ['success' => false, 'msg' => $lang['addon.warning.download.error']];

        return $install_fn($addon_id);
    }

    // -------------------------------------------------------------------------
    // Remote update (download + overwrite + upgrade callback)
    // -------------------------------------------------------------------------

    /**
     * Downloads an updated version from GitHub (overwrites existing files)
     * then calls $upgrade_callback($addon_id).
     */
    public static function update_from_github(
        string   $addon_id,
        string   $owner,
        string   $repo,
        string   $subdir,
        string   $dest_dir,
        string   $not_installed_key,
        callable $is_installed_fn,
        callable $upgrade_fn
    ): array
    {
        $version = GeneralConfig::load()->get_phpboost_major_version();
        $token   = AddonsConfig::load()->get_github_token();
        $lang    = LangLoader::get_all_langs();

        if (!$is_installed_fn($addon_id))
            return ['success' => false, 'msg' => $lang[$not_installed_key]];

        if (!is_writable($dest_dir) && !@chmod($dest_dir, 0755))
            return ['success' => false, 'msg' => $lang['warning.process.error']];

        if (!self::download_from_github($owner, $repo, $subdir, $addon_id, $dest_dir, $version, $token))
            return ['success' => false, 'msg' => $lang['addon.warning.download.error']];

        return $upgrade_fn($addon_id);
    }

    /**
     * Downloads an updated version from a website server then calls $upgrade_callback($addon_id).
     */
    public static function update_from_website(
        string   $addon_id,
        string   $server_url,
        string   $server_dir,
        string   $addon_type,
        string   $dest_dir,
        string   $not_installed_key,
        callable $is_installed_fn,
        callable $upgrade_fn
    ): array
    {
        $version = GeneralConfig::load()->get_phpboost_major_version();
        $lang    = LangLoader::get_all_langs();

        if (!$is_installed_fn($addon_id))
            return ['success' => false, 'msg' => $lang[$not_installed_key]];

        if (!is_writable($dest_dir) && !@chmod($dest_dir, 0755))
            return ['success' => false, 'msg' => $lang['warning.process.error']];

        if (!self::download_from_website($addon_id, $server_url, $server_dir, $addon_type, $dest_dir, $version))
            return ['success' => false, 'msg' => $lang['addon.warning.download.error']];

        return $upgrade_fn($addon_id);
    }

    // -------------------------------------------------------------------------
    // Bulk update helper — determine which installed addons need updating
    // -------------------------------------------------------------------------

    /**
     * Returns the list of installed addon IDs that have a newer version
     * available in the given remote index.
     *
     * Compares installed version (from local config.ini) against the 'version'
     * field in the remote JSON index.
     *
     * @param  array    $remote_index  Decoded remote JSON (array of addon entries)
     * @param  callable $is_installed  fn(string $id): bool
     * @param  callable $get_version   fn(string $id): string  — installed version string
     * @return array                   List of addon IDs that can be updated
     */
    public static function get_updatable_from_index(
        array    $remote_index,
        callable $is_installed,
        callable $get_version
    ): array
    {
        $updatable = [];
        foreach ($remote_index as $entry)
        {
            $addon_id       = $entry['id'] ?? '';
            $remote_version = $entry['version'] ?? '';
            if (empty($addon_id) || empty($remote_version))
                continue;
            if (!$is_installed($addon_id))
                continue;
            $local_version = $get_version($addon_id);
            if (version_compare($remote_version, $local_version, '>'))
                $updatable[] = $addon_id;
        }
        return $updatable;
    }
}
?>
