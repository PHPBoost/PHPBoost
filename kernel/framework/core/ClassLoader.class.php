<?php
/*##################################################
 *                           ClassLoader.class.php
 *                            -------------------
 *   begin                : October 21, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package core
 */
class ClassLoader
{
	private static $autoload;

	public static function import($classpath)
	{
		import($classpath);
	}

	public static function new_instance($classpath)
	{
		self::import($classpath);
		$classname =& self::get_classname($classpath);
		return new $classname();
	}

	public static function get_classname($classpath)
	{
		return substr($classpath, strrpos($classpath, '/') + 1);
	}

	public static function init_autoload()
	{
		self::$autoload = array (
		  'FormBuilder' => 'builder/form/FormBuilder',
		  'FormCaptchaField' => 'builder/form/FormCaptchaField',
		  'FormCheckbox' => 'builder/form/FormCheckbox',
		  'FormCheckboxOption' => 'builder/form/FormCheckboxOption',
		  'FormField' => 'builder/form/FormField',
		  'FormFieldComposite' => 'builder/form/FormFieldComposite',
		  'FormFieldset' => 'builder/form/FormFieldset',
		  'FormFileUploader' => 'builder/form/FormFileUploader',
		  'FormFreeField' => 'builder/form/FormFreeField',
		  'FormHiddenField' => 'builder/form/FormHiddenField',
		  'FormRadioChoice' => 'builder/form/FormRadioChoice',
		  'FormRadioChoiceOption' => 'builder/form/FormRadioChoiceOption',
		  'FormSelect' => 'builder/form/FormSelect',
		  'FormSelectOption' => 'builder/form/FormSelectOption',
		  'FormTextDate' => 'builder/form/FormTextDate',
		  'FormTextEdit' => 'builder/form/FormTextEdit',
		  'FormTextarea' => 'builder/form/FormTextarea',
		  'CategoriesManager' => 'content/CategoriesManager',
		  'Comments' => 'content/Comments',
		  'Note' => 'content/Note',
		  'Search' => 'content/Search',
		  'BBCodeEditor' => 'content/editor/BBCodeEditor',
		  'ContentEditor' => 'content/editor/ContentEditor',
		  'TinyMCEEditor' => 'content/editor/TinyMCEEditor',
		  'ATOM' => 'content/feed/ATOM',
		  'Feed' => 'content/feed/Feed',
		  'FeedData' => 'content/feed/FeedData',
		  'FeedItem' => 'content/feed/FeedItem',
		  'FeedsCat' => 'content/feed/FeedsCat',
		  'FeedsList' => 'content/feed/FeedsList',
		  'RSS' => 'content/feed/RSS',
		  'mathpublisher' => 'content/math/mathpublisher',
		  'BBCodeHighlighter' => 'content/parser/BBCodeHighlighter',
		  'BBCodeParser' => 'content/parser/BBCodeParser',
		  'BBCodeUnparser' => 'content/parser/BBCodeUnparser',
		  'ContentFormattingFactory' => 'content/parser/ContentFormattingFactory',
		  'ContentParser' => 'content/parser/ContentParser',
		  'ContentSecondParser' => 'content/parser/ContentSecondParser',
		  'ContentUnparser' => 'content/parser/ContentUnparser',
		  'Parser' => 'content/parser/Parser',
		  'TemplateHighlighter' => 'content/parser/TemplateHighlighter',
		  'parserTinyMCEParser' => 'content/parserTinyMCEParser',
		  'parserTinyMCEUnparser' => 'content/parserTinyMCEUnparser',
		  'ModuleMap' => 'content/sitemap/ModuleMap',
		  'SiteMap' => 'content/sitemap/SiteMap',
		  'SiteMapElement' => 'content/sitemap/SiteMapElement',
		  'SiteMapExportConfig' => 'content/sitemap/SiteMapExportConfig',
		  'SiteMapLink' => 'content/sitemap/SiteMapLink',
		  'SiteMapSection' => 'content/sitemap/SiteMapSection',
		  'Application' => 'core/Application',
		  'BreadCrumb' => 'core/BreadCrumb',
		  'Cache' => 'core/Cache',
		  'ClassLoader' => 'core/ClassLoader',
		  'ErrorViewBuilder' => 'core/ErrorViewBuilder',
		  'Errors' => 'core/Errors',
		  'MenuService' => 'core/MenuService',
		  'Repository' => 'core/Repository',
		  'StatsSaver' => 'core/StatsSaver',
		  'Updates' => 'core/Updates',
		  'GroupsCache' => 'core/cache/GroupsCache',
		  'HtaccessFileCache' => 'core/cache/HtaccessFileCache',
		  'ModulesCssFilesCache' => 'core/cache/ModulesCssFilesCache',
		  'LastUseDateConfig' => 'core/config/LastUseDateConfig',
		  'WritingPadConfig' => 'core/config/WritingPadConfig',
		  'AbstractDisplayGraphicalEnvironment' => 'core/environment/AbstractDisplayGraphicalEnvironment',
		  'AbstractGraphicalEnvironment' => 'core/environment/AbstractGraphicalEnvironment',
		  'AdminDisplayGraphicalEnvironment' => 'core/environment/AdminDisplayGraphicalEnvironment',
		  'AdminNodisplayGraphicalEnvironment' => 'core/environment/AdminNodisplayGraphicalEnvironment',
		  'AppContext' => 'core/environment/AppContext',
		  'GraphicalEnvironment' => 'core/environment/GraphicalEnvironment',
		  'SiteDisplayGraphicalEnvironment' => 'core/environment/SiteDisplayGraphicalEnvironment',
		  'SiteNodisplayGraphicalEnvironment' => 'core/environment/SiteNodisplayGraphicalEnvironment',
		  'LangLoader' => 'core/lang/LangLoader',
		  'LangNotFoundException' => 'core/lang/LangNotFoundException',
		  'Backup' => 'db/Backup',
		  'Sql' => 'db/Sql',
		  'AdministratorAlert' => 'events/AdministratorAlert',
		  'AdministratorAlertService' => 'events/AdministratorAlertService',
		  'Contribution' => 'events/Contribution',
		  'ContributionService' => 'events/ContributionService',
		  'Event' => 'events/Event',
		  'Mail' => 'io/Mail',
		  'Upload' => 'io/Upload',
		  'CacheData' => 'io/cache/CacheData',
		  'CacheManager' => 'io/cache/CacheManager',
		  'ConfigData' => 'io/config/ConfigData',
		  'ConfigExceptions' => 'io/config/ConfigExceptions',
		  'ConfigManager' => 'io/config/ConfigManager',
		  'DefaultConfigData' => 'io/config/DefaultConfigData',
		  'AbstractSQLQuerier' => 'io/db/AbstractSQLQuerier',
		  'CommonQuery' => 'io/db/CommonQuery',
		  'DBConnection' => 'io/db/DBConnection',
		  'DBConnectionException' => 'io/db/DBConnectionException',
		  'DBFactory' => 'io/db/DBFactory',
		  'DBMSUtils' => 'io/db/dbms/DBMSUtils',
		  'MySQLDBMSUtils' => 'io/db/dbms/MySQLDBMSUtils',
		  'InjectQueryResult' => 'io/db/InjectQueryResult',
		  'QueryResult' => 'io/db/QueryResult',
		  'SQLQuerier' => 'io/db/SQLQuerier',
		  'SQLQuerierException' => 'io/db/SQLQuerierException',
		  'SQLQueryVars' => 'io/db/SQLQueryVars',
		  'AbstractSelectQueryResult' => 'io/db/AbstractSelectQueryResult',
		  'SelectQueryResult' => 'io/db/SelectQueryResult',
		  'MySQLDBConnection' => 'io/db/mysql/MySQLDBConnection',
		  'MySQLDBConnectionException' => 'io/db/mysql/MySQLDBConnectionException',
		  'MySQLInjectQueryResult' => 'io/db/mysql/MySQLInjectQueryResult',
		  'MySQLQuerier' => 'io/db/mysql/MySQLQuerier',
		  'MySQLQuerierException' => 'io/db/mysql/MySQLQuerierException',
		  'MySQLSelectQueryResult' => 'io/db/mysql/MySQLSelectQueryResult',
		  'PDOInjectQueryResult' => 'io/db/pdo/PDOInjectQueryResult',
		  'PDOQuerier' => 'io/db/pdo/PDOQuerier',
		  'PDOQuerierException' => 'io/db/pdo/PDOQuerierException',
		  'PDOSelectQueryResult' => 'io/db/pdo/PDOSelectQueryResult',
		  'MySQLQueryTranslator' => 'io/db/translator/MySQLQueryTranslator',
		  'SQLQueryTranslator' => 'io/db/translator/SQLQueryTranslator',
		  'File' => 'io/filesystem/File',
		  'FileSystemElement' => 'io/filesystem/FileSystemElement',
		  'Folder' => 'io/filesystem/Folder',
		  'HTTPRequest' => 'io/request/HTTPRequest',
		  'HTTPRequestException' => 'io/request/HTTPRequestException',
		  'template' => 'io/template',
		  'AbstractTemplateParser' => 'io/template/AbstractTemplateParser',
		  'DeprecatedTemplate' => 'io/template/DeprecatedTemplate',
		  'FileTemplateLoader' => 'io/template/FileTemplateLoader',
		  'Template' => 'io/template/Template',
		  'TemplateException' => 'io/template/TemplateException',
		  'TemplateLoader' => 'io/template/TemplateLoader',
		  'TemplateParser' => 'io/template/TemplateParser',
		  'TemplateParserEcho' => 'io/template/TemplateParserEcho',
		  'TemplateParserString' => 'io/template/TemplateParserString',
		  'SHA256' => 'lib/SHA256',
		  'Authorizations' => 'members/Authorizations',
		  'GroupsService' => 'members/GroupsService',
		  'PrivateMsg' => 'members/PrivateMsg',
		  'Session' => 'members/Session',
		  'Uploads' => 'members/Uploads',
		  'User' => 'members/User',
		  'Menu' => 'menu/Menu',
		  'ContentMenu' => 'menu/content/ContentMenu',
		  'FeedMenu' => 'menu/feed/FeedMenu',
		  'LinksMenu' => 'menu/links/LinksMenu',
		  'LinksMenuElement' => 'menu/links/LinksMenuElement',
		  'LinksMenuLink' => 'menu/links/LinksMenuLink',
		  'MiniMenu' => 'menu/mini/MiniMenu',
		  'ModuleMiniMenu' => 'menu/module_mini/ModuleMiniMenu',
		  'ModuleInterface' => 'modules/ModuleInterface',
		  'ModulesDiscoveryService' => 'modules/ModulesDiscoveryService',
		  'PackageManager' => 'modules/PackageManager',
		  'View' => 'mvc/View',
		  'Controller' => 'mvc/controller/Controller',
		  'controller' => 'mvc/controller/controller',
		  'AbstractUrlMapper' => 'mvc/dispatcher/AbstractUrlMapper',
		  'Dispatcher' => 'mvc/dispatcher/Dispatcher',
		  'DispatcherException' => 'mvc/dispatcher/DispatcherException',
		  'UrlControllerMapper' => 'mvc/dispatcher/UrlControllerMapper',
		  'UrlMapper' => 'mvc/dispatcher/UrlMapper',
		  'BusinessObject' => 'mvc/model/BusinessObject',
		  'DAO' => 'mvc/model/DAO',
		  'JoinMappingModel' => 'mvc/model/JoinMappingModel',
		  'MappingModel' => 'mvc/model/MappingModel',
		  'MappingModelException' => 'mvc/model/MappingModelException',
		  'MappingModelField' => 'mvc/model/MappingModelField',
		  'PropertiesMapInterface' => 'mvc/model/PropertiesMapInterface',
		  'SQLDAO' => 'mvc/model/SQLDAO',
		  'SelectQueryResultMapper' => 'mvc/model/SelectQueryResultMapper',
		  'AbstractResponse' => 'mvc/response/AbstractResponse',
		  'AdminDisplayResponse' => 'mvc/response/AdminDisplayResponse',
		  'Response' => 'mvc/response/Response',
		  'SiteDisplayResponse' => 'mvc/response/SiteDisplayResponse',
		  'Bench' => 'util/Bench',
		  'Captcha' => 'util/Captcha',
		  'Date' => 'util/Date',
		  'Debug' => 'util/Debug',
		  'ImagesStats' => 'util/ImagesStats',
		  'MiniCalendar' => 'util/MiniCalendar',
		  'Pagination' => 'util/Pagination',
		  'StringVars' => 'util/StringVars',
		  'Url' => 'util/Url',
		);

		//		asort(self::$autoload);
		//		echo '<pre>'; var_export(self::$autoload); echo '</pre>';
		//		exit;
		spl_autoload_register(array(get_class(), 'autoload'));
	}

	public static function autoload($classname)
	{
		if (isset(self::$autoload[$classname]))
		{
			import(self::$autoload[$classname]);
		}
	}
}
?>