<?php
/**
 * Static helper for fetching remote addon lists via cURL.
 *
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 09
 * @since       PHPBoost 6.1 - 2026 03 29
*/

class AddonRemoteHelper
{
    /** Time-to-live for remote index cache files (in seconds). */
    const CACHE_TTL = 3600; // 1 hour

    /**
     * Supported addon types and their corresponding index file.
     * Provides uniform handling for modules / themes / languages.
     */
    const ADDON_INDEX_FILES = [
        'module' => 'modules.json',
        'theme'  => 'themes.json',
        'lang'   => 'langs.json',
    ];

    // -------------------------------------------------------------------------
    // Base cURL layer
    // -------------------------------------------------------------------------

    public static function curl_get($url, $extra_headers = [], $timeout = 15)
    {
        if (!function_exists('curl_init'))
            return false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS,      5);
        curl_setopt($ch, CURLOPT_TIMEOUT,        $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT,      'PHPBoost/' . GeneralConfig::load()->get_phpboost_major_version());
        curl_setopt($ch, CURLOPT_ENCODING,       '');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array_merge(['Accept: application/json'], $extra_headers));

        $body = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);

        if (\PHP_VERSION_ID < 80100)
            curl_close($ch);

        if ($body === false || !empty($err) || $http < 200 || $http >= 300)
            return false;

        return $body;
    }

    public static function remote_file_exists(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY,         true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,        5);
        curl_setopt($ch, CURLOPT_USERAGENT,      'PHPBoost/' . GeneralConfig::load()->get_phpboost_major_version());
        curl_exec($ch);
        $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (\PHP_VERSION_ID < 80100)
            curl_close($ch);
        return $http_code >= 200 && $http_code < 300;
    }

    public static function curl_get_json($url, $extra_headers = [], $timeout = 15)
    {
        $raw = self::curl_get($url, $extra_headers, $timeout);
        if ($raw === false)
            return null;
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }

    // -------------------------------------------------------------------------
    // GitHub helpers
    // -------------------------------------------------------------------------

    public static function github_headers($token)
    {
        $headers = ['Accept: application/vnd.github.v3+json'];
        if (!empty($token))
            $headers[] = 'Authorization: token ' . $token;
        return $headers;
    }

    public static function resolve_github_branch($owner, $repo, $version, $token)
    {
        $url      = 'https://api.github.com/repos/' . $owner . '/' . $repo . '/branches';
        $branches = self::curl_get_json($url, self::github_headers($token));
        if (is_array($branches))
        {
            $names = array_column($branches, 'name');
            if (in_array($version, $names)) return $version;
            if (in_array('master', $names)) return 'master';
            if (in_array('main',   $names)) return 'main';
        }
        return 'master';
    }

    public static function github_list_dirs($owner, $repo, $path, $branch, $token)
    {
        $encoded = $path !== '' ? implode('/', array_map('rawurlencode', explode('/', $path))) : '';
        $url     = 'https://api.github.com/repos/' . $owner . '/' . $repo . '/contents/' . $encoded . '?ref=' . rawurlencode($branch);
        $data    = self::curl_get_json($url, self::github_headers($token));
        if (!is_array($data))
            return null;
        $dirs = [];
        foreach ($data as $item)
        {
            if (isset($item['type']) && $item['type'] === 'dir')
                $dirs[] = $item;
        }
        return $dirs;
    }

    public static function github_read_config_ini($owner, $repo, $path, $branch, $token)
    {
        $encoded = implode('/', array_map('rawurlencode', explode('/', $path)));
        $url     = 'https://api.github.com/repos/' . $owner . '/' . $repo . '/contents/' . $encoded . '?ref=' . rawurlencode($branch);
        $data    = self::curl_get_json($url, self::github_headers($token));
        if (!is_array($data) || empty($data['content']))
            return [];
        $ini    = base64_decode(str_replace("\n", '', $data['content']));
        $parsed = @parse_ini_string($ini);
        return is_array($parsed) ? $parsed : [];
    }

    // -------------------------------------------------------------------------
    // Centralised JSON index (modules.json / themes.json / langs.json …)
    // -------------------------------------------------------------------------

    /**
     * Returns the local cache file path for a remote index URL.
     *
     * The filename includes the addon type (modules, themes, langs…) for
     * readability and to prevent collisions between repositories or addon types.
     *
     * Examples: /cache/remote_index_modules_a3f2c1d4.json
     *           /cache/remote_index_themes_b9e1f2a8.json
     */
    private static function cache_path(string $source, string $name, string $raw_url, string $index_file): string
    {
        $type = pathinfo($index_file, PATHINFO_FILENAME); // "modules", "themes", "langs"…
        return PATH_TO_ROOT . '/cache/addons/'. $source . '_' . Url::encode_rewrite($name) . '_' . $type . '_' . md5($raw_url) . '.json';
    }

    /**
     * Returns whether the cache file for a given URL is still fresh.
     */
    private static function cache_is_fresh(string $cache_file): bool
    {
        return file_exists($cache_file) && (time() - filemtime($cache_file)) < self::CACHE_TTL;
    }

    /**
     * Fetches and caches the JSON index of a GitHub repository.
     *
     * Generic method used for all addon types:
     *   - modules → $index_file = 'modules.json'  (or self::ADDON_INDEX_FILES['module'])
     *   - themes  → $index_file = 'themes.json'   (or self::ADDON_INDEX_FILES['theme'])
     *   - langs   → $index_file = 'langs.json'    (or self::ADDON_INDEX_FILES['lang'])
     *
     * The index file is expected at the repository root (or inside $subdir if provided).
     * The cache is stored in /cache/ with a name combining the addon type and a hash
     * of the raw URL to prevent any collision across repositories.
     *
     * @param  string $owner       GitHub repository owner (e.g. "PHPBoost")
     * @param  string $repo        Repository name         (e.g. "official-modules")
     * @param  string $subdir      Optional subdirectory inside the repository (may be empty)
     * @param  string $branch      Branch already resolved via resolve_github_branch()
     * @param  string $token       GitHub token (may be empty)
     * @param  string $index_file  Index filename; use ADDON_INDEX_FILES[type]
     * @param  bool   $force       true = bypass the cache and reload from GitHub
     * @return array|null          Decoded PHP array, or null if unreachable and no cache exists
     */
    public static function fetch_github_index_json(
        string $owner,
        string $repo,
        string $subdir,
        string $branch,
        string $token,
        string $index_file = 'modules.json',
        bool   $force      = false
    ): ?array
    {
        $path_parts = array_filter([$subdir, $index_file]);
        $raw_url    = 'https://raw.githubusercontent.com/'
            . $owner . '/' . $repo . '/' . $branch . '/'
            . implode('/', $path_parts);

        $cache_file = self::cache_path('github', $owner, $raw_url, $index_file);

        // Serve from cache if available and not expired
        if (!$force && self::cache_is_fresh($cache_file))
        {
            $cached = @file_get_contents($cache_file);
            if ($cached !== false)
            {
                $data = json_decode($cached, true);
                if (is_array($data))
                    return $data;
            }
        }

        // Download from GitHub (raw content)
        $raw = self::curl_get($raw_url, self::github_headers($token), 20);
        if ($raw === false)
        {
            // On network failure, return the stale cache rather than nothing
            if (file_exists($cache_file))
            {
                $stale = @file_get_contents($cache_file);
                if ($stale !== false)
                {
                    $data = json_decode($stale, true);
                    if (is_array($data))
                        return $data;
                }
            }
            return null;
        }

        $data = json_decode($raw, true);
        if (!is_array($data))
            return null;

        // Persist cache atomically: write to a temp file then rename,
        // so concurrent readers never see a partially written file.
        $cache_dir = dirname($cache_file);
        if (!is_dir($cache_dir))
            @mkdir($cache_dir, 0755, true);

        $tmp     = $cache_file . '.tmp.' . getmypid();
        $written = @file_put_contents($tmp, $raw, LOCK_EX);
        if ($written !== false)
            @rename($tmp, $cache_file); // atomic on POSIX systems
        else
            @unlink($tmp); // clean up if write failed

        return $data;
    }

    /**
     * Invalidates the cache for a given repository and addon type.
     *
     * Should be called after a successful installation or from the configuration
     * panel ("Clear remote addon cache" button).
     *
     * @param string $index_file  Same value passed to fetch_github_index_json()
     */
    public static function invalidate_github_index_cache(
        string $owner,
        string $repo,
        string $subdir,
        string $branch,
        string $index_file = 'modules.json'
    ): void
    {
        $path_parts = array_filter([$subdir, $index_file]);
        $raw_url    = 'https://raw.githubusercontent.com/'
            . $owner . '/' . $repo . '/' . $branch . '/'
            . implode('/', $path_parts);

        $cache_file = self::cache_path('github', $owner, $raw_url, $index_file);
        if (file_exists($cache_file))
            @unlink($cache_file);
    }

    /**
     * Pre-warms (or refreshes) all remote addon index caches.
     *
     * Iterates over every source declared in AddonsConfig:
     *   - 3 GitHub repos  (modules / themes / langs)
     *   - N website servers × 3 addon types
     *
     * Called by AdminCacheController after a full cache clear so that the next
     * page load serves the lists from the fresh cache instead of hitting the
     * network on-demand.
     *
     * Network errors per source are silently ignored (the stale cache, if any,
     * will be used on the next request).
     *
     * @param  bool $force  true = ignore existing cache and force re-download
     */
    public static function build_addons_caches(bool $force = true): void
    {
        $addons_config    = AddonsConfig::load();
        $phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
        $github_token     = $addons_config->get_github_token();

        // Map: addon_type => AddonsConfig getter method name
        $github_repo_getters = [
            'module' => 'get_modules_repo',
            'theme'  => 'get_themes_repo',
            'lang'   => 'get_langs_repo',
        ];

        // ---- GitHub sources ----
        foreach ($github_repo_getters as $addon_type => $getter)
        {
            $repos      = $addons_config->$getter();
            $index_file = self::ADDON_INDEX_FILES[$addon_type];

            foreach ($repos as $repo)
            {
                $owner  = $repo['owner']      ?? '';
                $rep    = $repo['repository'] ?? '';
                $subdir = $repo['directory']  ?? '';

                if (empty($owner) || empty($rep))
                    continue;

                // resolve_github_branch makes one API call — acceptable at warm-up time
                $branch = self::resolve_github_branch($owner, $rep, $phpboost_version, $github_token);
                self::fetch_github_index_json($owner, $rep, $subdir, $branch, $github_token, $index_file, $force);
            }
        }

        // ---- Website sources ----
        // fetch_website_index expects plural folder names: 'modules', 'themes', 'langs'
        // ADDON_INDEX_FILES values are 'modules.json', 'themes.json', 'langs.json'
        // Strip the extension to get the correct folder name.
        $website_servers = $addons_config->get_addons_server();
        foreach ($website_servers as $server)
        {
            $url = $server['url']       ?? '';
            $dir = $server['directory'] ?? '';

            if (empty($url))
                continue;

            foreach (self::ADDON_INDEX_FILES as $index_file)
            {
                $addon_folder = pathinfo($index_file, PATHINFO_FILENAME); // 'modules', 'themes', 'langs'
                self::fetch_website_index($url, $dir, $phpboost_version, $addon_folder, $force);
            }
        }
    }

    public static function clear_all_index_caches(): void
    {
        $cache_dir = PATH_TO_ROOT . '/cache/addons/';
        if (!is_dir($cache_dir))
            return;

        foreach (glob($cache_dir . '*.json') ?: [] as $file)
        {
            @unlink($file);
        }
    }

    // -------------------------------------------------------------------------
    // Web server index (with cache)
    // -------------------------------------------------------------------------

    /**
     * Fetches and caches the JSON index of a website addon server.
     *
     * Tries the versioned URL first, then /dev/ as fallback.
     * The result is cached in /cache/addons/ with a TTL of CACHE_TTL seconds,
     * using the same strategy as fetch_github_index_json().
     *
     * @param  string $server_url   Base URL of the addon server
     * @param  string $directory    Optional subdirectory on the server (may be empty)
     * @param  string $version      PHPBoost major version (e.g. '6.1')
     * @param  string $addon_folder Addon type folder name: 'modules', 'themes' or 'langs'
     * @param  bool   $force        true = bypass the cache and reload from the server
     * @return array                Two-element array: [base_url_string, data_array]
     */
    public static function fetch_website_index($server_url, $directory, $version, $addon_folder, bool $force = false)
    {
        $base = rtrim($server_url, '/');
        if (!empty($directory))
            $base .= '/' . trim($directory, '/');

        $real_version = $version === GeneralConfig::load()->get_phpboost_major_version() ? $version : 'dev';

        // Candidate URLs (versioned first, then /dev/ as fallback)
        $candidates = [
            $base . '/' . $real_version . '/' . $addon_folder,
            $base . '/dev/' . $addon_folder,
        ];

        // Build a stable cache key from the server base + addon type
        $index_file = $addon_folder . '.json';
        $cache_key  = $base . '/' . $addon_folder . '/' . $index_file;
        $cache_file = self::cache_path('website', parse_url($base, PHP_URL_HOST) ?: 'server', $cache_key, $index_file);

        // Serve from cache if still fresh
        if (!$force && self::cache_is_fresh($cache_file))
        {
            $cached = @file_get_contents($cache_file);
            if ($cached !== false)
            {
                $payload = json_decode($cached, true);
                if (is_array($payload) && isset($payload['base_url'], $payload['data']))
                    return [$payload['base_url'], $payload['data']];
            }
        }

        // Fetch from remote
        foreach ($candidates as $versioned_base)
        {
            $raw = self::curl_get($versioned_base . '/' . $index_file);
            if ($raw !== false)
            {
                $data = json_decode($raw, true);
                if (is_array($data))
                {
                    $tmp     = $cache_file . '.tmp.' . getmypid();
                    $written = @file_put_contents($tmp, $raw, LOCK_EX);
                    if ($written !== false)
                        @rename($tmp, $cache_file);
                    else
                        @unlink($tmp);

                    return [$versioned_base, $data];
                }
            }
        }

        // On fetch failure return stale cache if available rather than nothing
        if (file_exists($cache_file))
        {
            $stale = @file_get_contents($cache_file);
            if ($stale !== false)
            {
                $payload = json_decode($stale, true);
                if (is_array($payload) && isset($payload['base_url'], $payload['data']))
                    return [$payload['base_url'], $payload['data']];
            }
        }

        return ['', []];
    }

    // -------------------------------------------------------------------------
    // Download and extraction
    // -------------------------------------------------------------------------

    public static function download_and_extract_from_github($owner, $repo, $branch, $addon_prefix, $dest_dir, $token)
    {
        $zip_url = 'https://github.com/' . $owner . '/' . $repo . '/archive/refs/heads/' . rawurlencode($branch) . '.zip';
        $headers = [];
        if (!empty($token))
            $headers[] = 'Authorization: token ' . $token;
        $content = self::curl_get($zip_url, $headers, 60);
        if ($content === false)
            return false;
        return self::extract_zip_prefix_to($content, $addon_prefix, $dest_dir);
    }

    public static function extract_zip_prefix_to($zip_binary, $prefix, $dest_dir)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'pbt_addon_') . '.zip';
        file_put_contents($tmp, $zip_binary);
        $zip = new ZipArchive();
        if ($zip->open($tmp) !== true)
        {
            @unlink($tmp);
            return false;
        }
        $extracted = false;
        for ($i = 0; $i < $zip->numFiles; $i++)
        {
            $stat     = $zip->statIndex($i);
            $name     = $stat['name'];
            if ($prefix !== '' && strpos($name, $prefix) !== 0)
                continue;
            $relative = $prefix !== '' ? substr($name, strlen($prefix)) : $name;
            if ($relative === '' || $relative === '/')
                continue;
            $target = $dest_dir . $relative;
            if (substr($name, -1) === '/')
            {
                if (!is_dir($target))
                    @mkdir($target, 0755, true);
            }
            else
            {
                $parent = dirname($target);
                if (!is_dir($parent))
                    @mkdir($parent, 0755, true);
                $stream = $zip->getStream($name);
                if ($stream !== false)
                {
                    file_put_contents($target, stream_get_contents($stream));
                    fclose($stream);
                    $extracted = true;
                }
            }
        }
        $zip->close();
        @unlink($tmp);
        return $extracted;
    }

    public static function apply_permissions($path, $mode)
    {
        $prev = error_reporting(0);
        if (file_exists($path))
            chmod($path, $mode);
        error_reporting($prev);
        if (is_dir($path))
        {
            foreach (scandir($path) as $item)
            {
                if ($item === '.' || $item === '..')
                    continue;
                self::apply_permissions($path . '/' . $item, $mode);
            }
        }
    }
}
?>
