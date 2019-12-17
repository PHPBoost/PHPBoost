<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2010 08 07
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang = array();
$lang['cache'] = 'Cache';
$lang['clear_cache'] = 'Clear';
$lang['explain_data_cache'] = '<p>PHPBoost caches certain data in order to improve dramatically its performance.
Every data handled by PHPBoost is stored in a data base but each data base access takes a significant time. Data that are often read by PHPBoost (typically the configuration) are stored directly on the web server
in order to avoid accessing the data base management system.</p>
<p>However, that means that some data are present in two places: in the database and somewhere in the web server\'s memory. If you update data that are cached in the data base, you won\'t see any change because the cache will still contain the former values.
In this situation, you have to clear the cache in order to have PHPBoost fetch again data in the data base.
The reference place for data is the data base. If you update data in the cache file, they will be lost at the same cache generation.</p>';
$lang['syndication_cache'] = 'Syndication';
$lang['explain_syndication_cache'] = '<p>PHPBoost caches all syndication data.
In fact, at the first time a feed is asked, it\'s fecthed in the data base and cache, and the next times the cache is read without accessing the data base.
<p>On this configuration page, you can clear the syndication cache. It\'s useful if you have manually updated data in the data base, without clearing it manually, changes aren\'t visible in the feeds.</p>';
$lang['cache_configuration'] = 'Cache configuration';
$lang['php_cache'] = 'PHP accelerator';
$lang['explain_php_cache'] = '<p>Some additional PHP modules enable to improve dramatically the execution time of PHP applications.
PHP supports <acronym aria-label="Alternative PHP Cache">APCu</acronym> which is a cache system to improve pages loading duration.</p>
<p>By default, cache data are stored on the filesystem (in the files tree) but that kind of module allows to store directly in the server\'s central memory (RAM) which is far faster.</p>';
$lang['enable_apc'] = 'Enable APCu cache';
$lang['apc_available'] = 'Availability of the APCu extension';
$lang['apcu_cache'] = 'APCu Cache';
$lang['explain_apc_available'] = 'The extension is available on a few servers. If it\'s not available, you cannot benefit from the performance improvement.';
$lang['css_cache'] = 'CSS Cache';
$lang['explain_css_cache'] = '<p>PHPBoost caches all the CSS files provided by the themes and modules.
Normally, the display site, a set of css files are going to be loaded. The cache CSS meanwhile, will first optimize files and then create a single CSS file hash.</p>
<p>Via this page in the administration of PHPBoost, you can clear the cache so as to force CSS to recreate PHPBoost CSS files optimized.</p>';
$lang['explain_css_cache_config'] = '<p>PHPBoost caches all CSS files provided by the themes and modules to improve the time to display pages.
You through this setup, choose to activate this feature or not and to choose its level of intensity.<br/>
Disabling this option can allow you to more easily customize your themes.</p>';
$lang['enable_css_cache'] = 'Enable CSS cache';
$lang['level_css_cache'] = 'Optimization level';
$lang['low_level_css_cache'] = 'Low';
$lang['high_level_css_cache'] = 'High';
$lang['explain_level_css_cache'] = 'The low level can only remove the tabs and spaces while the upper level fully optimizes your CSS files.';
?>
