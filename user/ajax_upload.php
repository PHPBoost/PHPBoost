<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 02 20
 * @since       PHPBoost 6.0 - 2024 02 18
*/

require_once('../kernel/begin.php');

// Adding a file
$files_upload_config = FileUploadConfig::load();

// Groups upload authorizations
$group_limit = AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, $files_upload_config->get_maximum_size_upload());
$unlimited_data = ($group_limit === -1) || AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL);

$member_memory_used = Uploads::Member_memory_used(AppContext::get_current_user()->get_id());
if ($member_memory_used >= $group_limit && !$unlimited_data)
{
    echo json_encode([
        'success' => false,
        'url' => LangLoader::get_message('e_max_data_reach', 'errors'),
        'class' => 'warning'
    ]);
}
else
{
    // if folder is not writable, try CHMOD 755
    @clearstatcache();
    $dir = PATH_TO_ROOT . '/upload/';
    if (!is_writable($dir))
        $is_writable = @chmod($dir, 0755);

    @clearstatcache();
    if (is_writable($dir))
    { // Folder writable, upload is possible
        $weight_max = $unlimited_data ? 100000000 : ($group_limit - $member_memory_used);
        $Upload = new Upload($dir);
        $Upload->file('upload_file', '`\.(' . implode('|', array_map('preg_quote', $files_upload_config->get_authorized_extensions())) . ')+$`iu', Upload::UNIQ_NAME, $weight_max);

        if ($Upload->get_error() != '')
        { // Error, stop here
            if ($Upload->get_error() == 'e_upload_max_weight') {
                echo json_encode([
                    'success' => false,
                    'url' => LangLoader::get_message('e_upload_max_weight', 'errors'),
                    'class' => 'warning'
                ]);
            }
            elseif ($Upload->get_error() != '')
            { // Error, stop here
                if ($Upload->get_error() == 'e_upload_invalid_format') 
                {
                    echo json_encode([
                        'success' => false,
                        'url' => LangLoader::get_message('e_upload_invalid_format', 'errors'),
                        'class' => 'warning'
                    ]);
                }
            }
        }
        else
        { // Insertion in database
            foreach ($Upload->get_files_parameters() as $parameters)
            {
                $result = PersistenceContext::get_querier()->insert(DB_TABLE_UPLOAD, array('shared' => 0, 'idcat' => 0, 'name' => $parameters['name'], 'path' => $parameters['path'], 'user_id' => AppContext::get_current_user()->get_id(), 'size' => $parameters['size'], 'type' => $parameters['extension'], 'timestamp' => time()));
                echo json_encode([
                    'success' => true,
                    'url' => '/upload/' . $parameters['path']
                ]);
            }
        }
    }
    else
    echo json_encode([
        'success' => false,
        'url' => LangLoader::get_message('e_upload_failed_unwritable', 'errors'),
        'class' => 'warning'
    ]);
}
?>