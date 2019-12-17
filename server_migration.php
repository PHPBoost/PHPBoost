<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 19
 * @since       PHPBoost 3.0 - 2012 05 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '.');

require_once PATH_TO_ROOT . '/kernel/framework/io/data/cache/CacheData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/io/data/config/ConfigData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/io/data/config/AbstractConfigData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/phpboost/config/GeneralConfig.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/helper/TextHelper.class.php';

require_once PATH_TO_ROOT . '/kernel/framework/util/Date.class.php';

$config_file = PATH_TO_ROOT . '/cache/CacheManager-kernel-general-config.data';

if (file_exists($config_file))
{
	$general_config = TextHelper::unserialize(file_get_contents($config_file));
}
else
{
	$general_config = new GeneralConfig();
	$general_config->set_default_values();
}

if (isset($_POST['url']) && isset($_POST['path']))
{
	$general_config->set_site_url($_POST['url']);
	$general_config->set_site_path($_POST['path']);
	file_put_contents($config_file, TextHelper::serialize($general_config));

	echo 'Success';
}

$site_url = $general_config->get_site_url();
$site_path = $general_config->get_site_path();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Server migration</title>
		<meta charset="UTF-8" />
		<meta name="generator" content="PHPBoost" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="templates/default/theme/default.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="templates/default/theme/admin_design.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="templates/default/theme/admin_form.css" type="text/css" media="screen, print" />
		<link rel="stylesheet" href="templates/default/theme/admin_colors.css" type="text/css" media="screen, print" />
	</head>

	<body itemscope="itemscope" itemtype="http://schema.org/WebPage">
		<form action="" method="post" class="fieldset-content">
			<fieldset>
				<legend><h1>Migration</h1></legend>
				<div class="fieldset-inset">
					<div class="form-element half-field">
						<label for="url">Url :&nbsp;</label>
						<input type="text" size="55" maxlength="100" id="url" name="url" value="<?php echo $site_url ?>">
					</div>
					<div class="form-element half-field">
						<label for="path">Path :&nbsp;</label>
						<input type="text" size="55" maxlength="100" id="path" name="path" value="<?php echo $site_path ?>">
					</div>
				</div>
			</fieldset>
			<fieldset class="fieldset-submit">
				<div class="fieldset-inset">
					<button class="button submit" type="submit" name="submit" value="true">Submit</button>
				</div>
			</fieldset>
		</form>
	</body>
</html>
