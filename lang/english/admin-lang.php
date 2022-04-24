<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 24
 * @since       PHPBoost 1.3 - 2005 11 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['admin.administration']     = 'Administration';
$lang['admin.kernel']             = 'Kernel';
$lang['admin.warning']            = 'Warning';
$lang['admin.priority']           = 'Priority';
$lang['admin.priority.very.high'] = 'Immediate';
$lang['admin.priority.high']      = 'Urgent';
$lang['admin.priority.medium']    = 'Medium';
$lang['admin.priority.low']       = 'Low';
$lang['admin.priority.very.low']  = 'Very low';
$lang['admin.code']               = 'Code';

$lang['admin.unknown.robot'] = 'Unknown robot';

// Advice
$lang['admin.advice']                              = 'Advice';
$lang['admin.advice.modules.management']           = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Disable or uninstall modules</a> you don\'t need to free ressources on the website.';
$lang['admin.advice.check_modules.authorizations'] = 'Check the authorizations of all your modules and menus before opening your website to avoit guest or unauthorized users accessing protected areas.';
$lang['admin.advice.disable.debug.mode']           = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Disable debug mode</a> to hide errors to users (the errors are loggued on the <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Loggued errors</a> page).';
$lang['admin.advice.disable.maintenance']          = '<a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Disable maintenance</a> to allow the users to view your website.';
$lang['admin.advice.enable.url.rewriting']         = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable URL rewriting</a> to have more readable urls (usefull for SEO).';
$lang['admin.advice.enable.output.gz']             = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable Output pages compression</a> to gain performance.';
$lang['admin.advice.enable.apcu.cache']            = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Enable APCu cache</a> to allow the RAM cache to be loaded on the server and not on the hard-drive and gain performance.';
$lang['admin.advice.save.database']                = 'Save your database frequently.';
$lang['admin.advice.optimize.database.tables']     = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable auto tables optimization</a> or optimize occasionally your tables in the module <strong>Database</strong> (if it is installed) or in your database management tool to recover the wasted base space.';
$lang['admin.advice.password.security']            = 'Increase strength and length of passwords in <a href="' . AdminMembersUrlBuilder::configuration()->rel() . '">members configuration</a> to strengthen security.';
$lang['admin.advice.upgrade.php.version']          = '
    PHP version ' . ServerConfiguration::get_phpversion() . ' of your server is deprecated, there are no more security updates and it potentially contains vulnerabilities allowing a malicious person to attack your website.
    <br />Update your PHP version to ' . ServerConfiguration::RECOMMENDED_PHP_VERSION . ' minimum if your host allows it, the new versions are faster and more secure.
';

// Alerts
$lang['admin.alerts']               = 'Alerts';
$lang['admin.alerts.list']          = 'Alerts list';
$lang['admin.no.unread.alert']      = 'No unprocessed alerts';
$lang['admin.unread.alerts']        = 'There are some unprocessed alerts. You should go there to process them.';
$lang['admin.no.alert']             = 'No existing alert';
$lang['admin.display.all.alerts']   = 'See all alerts';
$lang['admin.fix.alert']            = 'Consider the alert as fixed';
$lang['admin.unfix.alert']          = 'Consider the alert as not fixed';
$lang['admin.warning.delete.alert'] = 'Are you sure you want to delete this alert?';
$lang['admin.unread.alerts']        = 'There are some unprocessed alerts. You should go there to process them.';
$lang['admin.see.all.alerts']       = 'See all alerts';

// Cache
$lang['admin.clear.cache'] = 'Empty cache';

$lang['admin.cache']                  = 'Cache';
$lang['admin.cache.data.description'] = '
    <p>PHPBoost caches certain data in order to improve dramatically its performance.
    Every data handled by PHPBoost is stored in a data base but each data base access takes a significant time. Data that are often read by PHPBoost (typically the configuration) are stored directly on the web server
    in order to avoid accessing the data base management system.</p>
    <p>However, that means that some data are present in two places: in the database and somewhere in the web server\'s memory. If you update data that are cached in the data base, you won\'t see any change because the cache will still contain the former values.
    In this situation, you have to clear the cache in order to have PHPBoost fetch again data in the data base.
    The reference place for data is the data base. If you update data in the cache file, they will be lost at the same cache generation.</p>
';
    // Syndication
$lang['admin.cache.syndication']             = 'Syndication';
$lang['admin.cache.syndication.description'] = '
    <p>PHPBoost caches all syndication data.
    In fact, at the first time a feed is asked, it\'s fecthed in the data base and cache, and the next times the cache is read without accessing the data base.
    <p>On this configuration page, you can clear the syndication cache. It\'s useful if you have manually updated data in the data base, without clearing it manually, changes aren\'t visible in the feeds.</p>
';
    // CSS
$lang['admin.cache.css']             = 'CSS Cache';
$lang['admin.cache.css.description'] = '
    <p>PHPBoost caches all the CSS files provided by the themes and modules.
    Normally, the display site, a set of css files are going to be loaded. The cache CSS meanwhile, will first optimize files and then create a single CSS file hash.</p>
    <p>Via this page in the administration of PHPBoost, you can clear the cache so as to force CSS to recreate PHPBoost CSS files optimized.</p>
';
    // Configuration
$lang['admin.cache.configuration'] = 'Cache configuration';
$lang['admin.php.accelerator']     = 'PHP accelerator';
$lang['admin.php.description']     = '
    <p>Some additional PHP modules enable to improve dramatically the execution time of PHP applications.
    PHP supports <acronym aria-label="Alternative PHP Cache">APCu</acronym> which is a cache system to improve pages loading duration.</p>
    <p>By default, cache data are stored on the filesystem (in the files tree) but that kind of module allows to store directly in the server\'s central memory (RAM) which is far faster.</p>
';
$lang['admin.enable.apc']            = 'Enable APCu cache';
$lang['admin.apc.available']         = 'Availability of the APCu extension';
$lang['admin.apc.available.clue']    = 'The extension is available on a few servers. If it\'s not available, you cannot benefit from the performance improvement.';
$lang['admin.apcu.cache']            = 'APCu Cache';
$lang['admin.css.cache.description'] = '
    <p>PHPBoost caches all CSS files provided by the themes and modules to improve the time to display pages.
    You through this setup, choose to activate this feature or not and to choose its level of intensity.<br/>
    Disabling this option can allow you to more easily customize your themes.</p>
';
$lang['admin.enable.css.cache']   = 'Enable CSS cache';
$lang['admin.optimization.level'] = 'Optimization level';
$lang['admin.low.level']          = 'Low';
$lang['admin.high.level']         = 'High';
$lang['admin.level.clue']         = 'The low level can only remove the tabs and spaces while the upper level fully optimizes your CSS files.';

// Content
$lang['admin.content.configuration'] = 'Content configuration';
$lang['admin.forbidden.module']      = 'Forbidden modules';
    // Format
$lang['admin.formatting.language']         = 'Formatting language';
$lang['admin.default.formatting.language'] = 'Default formatting language on the website';
$lang['admin.formatting.language.clue']    = 'Every user will be able to choose';
$lang['admin.forbidden.tags']              = 'Forbidden tags';
    // HTML
$lang['admin.html.language']            = 'HTML language';
$lang['admin.html.authorizations']      = 'Authorization level to insert HTML langage in the content';
$lang['admin.html.authorizations.clue'] = 'Warning : if you can insert HTML tags, you can also insert some JavaScript and this code can be the source of vulnerabilities. People who can insert some HTML language must be people who you trust.';
    // Messages
$lang['admin.messages.management'] = 'Post Management';
$lang['admin.max.pm.number']       = 'Maximum number of private messages';
$lang['admin.max.pm.number.clue']  = 'Unlimited for administrators and moderators';
$lang['admin.anti.flood']          = 'Anti flood';
$lang['admin.anti.flood.clue']     = 'Block too rapid repeat messages, except if the visitors are authorized';
$lang['admin.flood.delay']         = 'Minimal interval of time between two messages';
$lang['admin.flood.delay.clue']    = 'In seconds. 7 seconds per default.';
    // Share
$lang['admin.sharing.management']      = 'Sharing options management';
$lang['admin.display.content.sharing'] = 'Display sharing links on content pages';
$lang['admin.display.email.sharing']   = 'Display Email sharing link';
$lang['admin.display.print.sharing']   = 'Display page print link';
$lang['admin.print.sharing.clue']      = 'Visible on computer only';
$lang['admin.display.sms.sharing']     = 'Display SMS sharing link';
$lang['admin.sms.sharing.clue']        = 'Visible on mobile device only';
    // S.E.O.
$lang['admin.opengraph']       = 'Improve SEO with OpenGraph tags';
$lang['admin.opengraph.clue']  = 'Allows to give precise information about pages to search engines and social networks';
$lang['admin.default.picture'] = 'Site default picture for SEO';
    // Captcha
$lang['admin.captcha']      = 'Captcha';
$lang['admin.used.captcha'] = 'Captcha used on your site';
$lang['admin.captcha.clue'] = 'The captcha allows you to protect yourself against spam on your site.';
    // New content
$lang['admin.new.content.config']        = 'Recent content tag management';
$lang['admin.enable.new.content']        = 'Enable new content tag';
$lang['admin.new.content.clue']          = 'This option allows to identify newly added elements.';
$lang['admin.new.content.duration']      = 'Tag display time';
$lang['admin.new.content.duration.clue'] = 'In days. 5 days by default.';
$lang['admin.new.content.forbidden.module.clue'] = 'Select modules in which you do not want to enable new-content tag';
    // Notation
$lang['admin.notation.config'] = 'Notation configuration';
$lang['admin.enable.notation'] = 'Enable notation';
$lang['admin.notation.scale']  = 'Notation scale';
$lang['admin.notation.forbidden.module.clue'] = 'Select modules in which you do not want to enable notation';
    // ID card
$lang['admin.id.card']        = 'Author information management';
$lang['admin.enable.id.card'] = 'Enable the author information';
$lang['admin.id.card.clue']   = 'Display the author information of an article (profile + avatar + biography)';
$lang['admin.id.card.forbidden.module.clue'] = 'Select modules where you don\'t want to enable the author information';

// Errors lists
$lang['admin.errors']               = 'Errors';
$lang['admin.clear.list']           = 'Clear list';
$lang['admin.warning.clear.errors'] = 'Erase all errors?';
    // Logged
$lang['admin.logged.errors']      = 'Logged errors';
$lang['admin.logged.errors.list'] = 'Logged errors list';
    // 404
$lang['admin.404.errors']        = '404 errors';
$lang['admin.404.errors.list']   = '404 errors list';
$lang['admin.404.requested.url'] = 'Requested url';
$lang['admin.404.from.url']      = 'Source url';

// Index
$lang['admin.quick.access']        = 'Quick Access';
$lang['admin.welcome.title']       = 'Welcome to the administration panel of your site';
$lang['admin.welcome.description'] = 'The administration lets you manage content and configuration of your site<br />The home page lists the most common actions<br />Take time to read the tips to optimize the security of your site';
$lang['admin.website.management']  = 'Manage the website';
$lang['admin.customize.website']   = 'Customize the website';
$lang['admin.add.content']         = 'Add content';
$lang['admin.customize.theme']     = 'Customize a theme';
$lang['admin.add.article']         = 'Add article';
$lang['admin.add.news']            = 'Add news';
$lang['admin.last.comments']       = 'Last comments';
$lang['admin.writing.pad']         = 'Writing pad';
$lang['admin.writing.pad.clue']    = 'This form is provided to enter your personal notes.';

// Maintenance
$lang['admin.maintenance']         = 'Maintenance';
$lang['admin.maintenance.title']   = 'Website in maintenance';
$lang['admin.maintenance.content'] = 'The website is under maintenance. Thank you for your patience.';
$lang['admin.maintenance.delay']   = 'Time remaining:';
$lang['admin.disable.maintenance'] = 'Disable maintenance';
    // Form
$lang['admin.maintenance.type']                   = 'Put the website in maintenance';
$lang['admin.maintenance.type.during']            = 'Less than a day';
$lang['admin.maintenance.type.until']             = 'Many days';
$lang['admin.maintenance.type.unlimited']         = 'For an unspecified duration';
$lang['admin.maintenance.display.duration']       = 'Display maintenance duration';
$lang['admin.maintenance.admin.display.duration'] = 'Display maintenance duration to the administrator';
$lang['admin.maintenance.text']                   = 'Text to display when the website is under maintenance';
$lang['admin.maintenance.authorization']          = 'Permission to access to the website during maintenance';

// Server report
$lang['admin.server']                     = 'Server';
$lang['admin.phpinfo']                    = 'PHP info';
$lang['admin.system.report']              = 'System report';
$lang['admin.php.version']                = 'PHP version';
$lang['admin.dbms.version']               = 'DBMS version';
$lang['admin.gd.library']                 = 'GD Library';
$lang['admin.curl.library']               = 'Curl Extension';
$lang['admin.mbstring.library']           = 'Mbstring Extension (UTF-8)';
$lang['admin.url.rewriting']              = 'URL rewriting';
$lang['admin.phpboost.config']            = 'PHPBoost configuration';
$lang['admin.kernel.version']             = 'Kernel version';
$lang['admin.output.gz']                  = 'Output pages compression';
$lang['admin.directories.auth']           = 'Directories authorization';
$lang['admin.system.report.summary']      = 'Summary';
$lang['admin.system.report.summary.clue'] = 'This is a summary of the report, it will be useful for support, when you will be asked about your configuration.';
$lang['admin.copy.report']                = 'Copy report';

// Smileys
$lang['admin.smileys.management']     = 'Smiley management';
$lang['admin.upload.smileys']         = 'Upload smileys';
$lang['admin.smiley']                 = 'Smiley';
$lang['admin.add.smileys']            = 'Add smileys';
$lang['admin.edit.smiley']            = 'Edit smiley';
$lang['admin.smiley.code']            = 'Smiley code (ex: :D)';
$lang['admin.available.smileys']      = 'Available smileys';
$lang['admin.available.smileys.clue'] = '/images/smileys';
$lang['admin.smiley.success.add']     = 'The smiley has been successfully add';

// Updates
$lang['admin.updates']                = 'Updates';
$lang['admin.available.updates']      = 'Available updates';
$lang['admin.available.updates.clue'] = 'Updates are available<br />Please, update quickly';
$lang['admin.available.version']      = 'The %1$s %2$s is available in its %3$s version';
$lang['admin.kernel.update']          = 'PHPBoost\'s kernel %s is available';
$lang['admin.download.app']           = 'Download';
$lang['admin.download.pack']          = 'Complete pack';
$lang['admin.update.pack']            = 'Update pack';
$lang['admin.new.features']           = 'New features';
$lang['admin.improvements']           = 'Improvements';
$lang['admin.security.improvements']  = 'Security improvements';
$lang['admin.fixed.bugs']             = 'Fixed bugs';
$lang['admin.details']                = 'Details';
$lang['admin.more.details']           = 'More details';
$lang['admin.download.full.pack']     = 'Download the full pack';
$lang['admin.download.update.pack']   = 'Download the update pack';
$lang['admin.no.available.update']    = 'No update is available for the moment.';
$lang['admin.updates.check']          = 'Check for updates now!';
$lang['admin.update.php.version']     = '
    Can\'t check for updates.<br />
    Please upgrade to PHP version %s or above.<br />
    If you can\'t use PHP5, check for updates on our <a href="https://www.phpboost.com">official website</a>.
';
$lang['admin.update.verification.impossible'] = 'Checking for updates is not possible, the function is not available on your server.<br/>Enable the Curl extension in your server\'s PHP options to run the update availability check.';

?>
