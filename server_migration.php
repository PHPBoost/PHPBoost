<?php
/*##################################################
 *                                server_migration.php
*                            -------------------
*   begin                : May 14, 2012
*   copyright            : (C) 2012 Kevin MASSY
*   email                : kevin.massy@phpboost.com
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
 
define('PATH_TO_ROOT', '.');
 
require_once PATH_TO_ROOT . '/kernel/framework/io/data/cache/CacheData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/io/data/config/ConfigData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/io/data/config/AbstractConfigData.class.php';
require_once PATH_TO_ROOT . '/kernel/framework/phpboost/config/GeneralConfig.class.php';

require_once PATH_TO_ROOT . '/kernel/framework/util/Date.class.php';
 
$config_file = PATH_TO_ROOT . '/cache/CacheManager-kernel-general-config.data';
 
if (file_exists($config_file))
{
	$general_config = unserialize(file_get_contents($config_file));
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
	file_put_contents($config_file, serialize($general_config));
 
	echo 'Success';
}
 
$site_url = $general_config->get_site_url();
$site_path = $general_config->get_site_path();
?>
 
<form action="" method="post">
	<fieldset>
		<legend>Migration</legend>
		<div class="form-element">
			<div class="form-field">
				<label>Url :&nbsp;</label><input type="text" size="65" maxlength="100" id="url" name="url" value="<?php echo $site_url ?>">
			</div>
			<div class="form-field">
				<label>Path :&nbsp;</label><input type="text" size="65" maxlength="100" id="path" name="path" value="<?php echo $site_path ?>">
			</div>	
		</div>
		<div>
			<button type="submit" name="submit" value="true">Submit</button>
		</div>
	</fieldset>
</form>
<style>
<!--
textarea {
	display:block;
	width:94%;
	margin:auto;
}
div {
	text-align:center;
}
input.text{
	padding:2px;	
	width:400px;
	margin-top:5px;
}
 label {
	width:50px;
	margin-top:5px;
	text-align:right;
	display:inline;
	float:left;
}
input.submit, button.submit{
	padding:1px 2px;
	font-family:Verdana, 'Bitstream Vera Sans', Times, serif;
	border:1px solid #515C68;
	border-top:1px solid #8498ae;
	color:#515C68;
	font-weight:bold;
	font-size:10px;
	border-radius:5px;
	cursor:pointer;
	margin:7px auto;
}
fieldset{
	width: 500px;
	font-family:"Lucida Grande","Lucida Sans Unicode",Verdana,'Bitstream Vera Sans',Times,serif;
	margin:20px auto;
	padding:6px;
	border:1px solid #CCCCCC;
	color:#445766;
	background:#eceeef;
	position:relative;
}
legend {
	background:#7191AA;
	color:#FFF;
	border-radius:5px;
	padding:3px 5px;
	border: 1px solid #CCC;
}
-->
</style>