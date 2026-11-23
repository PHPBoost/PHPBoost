<?php
/**
 * @package     PHPBoost
 * @subpackage  Member
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html) GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 21
 * @since       PHPBoost 1.6 - 2007 04 18
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/


class Uploads
{
    const EMPTY_FOLDER   = true;
    const ADMIN_NO_CHECK = true;


    private static DBQuerier $db_querier;


    public static function __static(): void
    {
        self::$db_querier = PersistenceContext::get_querier();
    }


    // Adding a virtual folder
    public static function Add_folder(int $id_parent, int $user_id, string $name): int
    {
        $check_folder = self::$db_querier->count(DB_TABLE_UPLOAD_CAT, 'WHERE name = :name AND id_parent = :id_parent AND user_id = :user_id', ['name' => $name, 'id_parent' => $id_parent, 'user_id' => $user_id]);
        if (!empty($check_folder) || preg_match('`/|\.|\\\|"|<|>|\||\?`u', stripslashes($name)))
            return 0;


        $result = self::$db_querier->insert(DB_TABLE_UPLOAD_CAT, ['id_parent' => $id_parent, 'user_id' => $user_id, 'name' => $name]);


        return $result->get_last_inserted_id();
    }


    // Recursively deletes the member's folders and files.
    public static function Empty_folder_member(int $user_id): void
    {
        // Deleting files.
        $result = self::$db_querier->select("
            SELECT path
            FROM " . DB_TABLE_UPLOAD . "
            WHERE user_id = :user_id", [
                'user_id' => $user_id
            ]
        );
        while($row = $result->fetch())
        {
            $file = new File(PATH_TO_ROOT . '/upload/' . $row['path']);
            $file->delete();
        }
        $result->dispose();


        // Deleting database entries
        self::$db_querier->delete(DB_TABLE_UPLOAD_CAT, 'WHERE user_id = :user_id', ['user_id' => $user_id]);
        self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE user_id = :user_id', ['user_id' => $user_id]);
    }


    // Recursively deletes a folder and its contents
    public static function Del_folder(int $id_folder): void
    {
        // Deleting files
        $result = self::$db_querier->select("
            SELECT path
            FROM " . DB_TABLE_UPLOAD . "
            WHERE idcat = :idcat", [
                'idcat' => $id_folder
            ]
        );
        while ($row = $result->fetch())
        {
            $file = new File(PATH_TO_ROOT . '/upload/' . $row['path']);
            $file->delete();
        }
        $result->dispose();


        // Deleting database entries
        self::$db_querier->delete(DB_TABLE_UPLOAD_CAT, 'WHERE id = :id', ['id' => $id_folder]);


        self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE idcat = :idcat', ['idcat' => $id_folder]);


        $result = self::$db_querier->select("
            SELECT id
            FROM " . DB_TABLE_UPLOAD_CAT . "
            WHERE id_parent = :id", [
                'id' => $id_folder
            ]
        );
        while ($row = $result->fetch())
        {
            if (!empty($row['id']))
            {
                self::Del_folder($row['id']);
            }
        }
        $result->dispose();
    }


    // Deleting a file.
    public static function Del_file(int $id_file, int $user_id, bool $admin = false): string
    {
        if ($admin) // Administration, ownership is not checked.
        {
            $name = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'path', 'WHERE id = :id', ['id' => $id_file]);
            self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE id = :id', ['id' => $id_file]);


            $file = new File(PATH_TO_ROOT . '/upload/' . $name);
            $file->delete();


            return '';
        }
        else
        {
            $check_id_auth = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'user_id', 'WHERE id = :id', ['id' => $id_file]);
            // Deleting a file.
            if ($check_id_auth == $user_id)
            {
                $name = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'path', 'WHERE id = :id', ['id' => $id_file]);
                self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE id = :id', ['id' => $id_file]);


                $file = new File(PATH_TO_ROOT . '/upload/' . $name);
                $file->delete();


                return '';
            }
            return 'e_auth';
        }
    }


    // Renames a virtual folder
    public static function Rename_folder(int $id_folder, string $name, string $previous_name, int $user_id, bool $admin = false): string
    {
        $info_folder = [
            'id_parent' => '',
            'user_id' => ''
        ];
        try
        {
            $info_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", ["id_parent", "user_id"], 'WHERE id=:id', ['id' => $id_folder]);
        } catch (RowNotFoundException $e) {}


        // Checking that the folder name is unique.
        $check_folder = self::$db_querier->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id_parent AND name = :name AND id <> :id AND user_id = :user_id', ['id_parent' => $info_folder['id_parent'], 'name' => $name, 'id' => $id_folder, 'user_id' => $user_id]);
        if ($check_folder > 0 || preg_match('`/|\.|\\\|"|<|>|\||\?`u', stripslashes($name)))
        {
            return '';
        }


        if ($admin) // Administration, ownership is not checked.
        {
            self::$db_querier->update(DB_TABLE_UPLOAD_CAT, ['name' => $name], 'WHERE id = :id', ['id' => $id_folder]);
            return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
        }
        else
        {
            if ($user_id == $info_folder['user_id'])
            {
                self::$db_querier->update(DB_TABLE_UPLOAD_CAT, ['name' => $name], 'WHERE id = :id', ['id' => $id_folder]);
                return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
            }
        }
        return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($previous_name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
    }


    // Renames a virtual file
    public static function Rename_file(int $id_file, string $name, string $previous_name, int $user_id, bool $admin = false): string
    {
        $info_cat = [
            'idcat' => '',
            'user_id' => ''
        ];
        try
        {
            $info_cat = self::$db_querier->select_single_row(PREFIX . "upload", ["idcat", "user_id"], 'WHERE id=:id', ['id' => $id_file]);
        }
        catch (RowNotFoundException $e) {}


        // Checking that the file name is unique.
        $check_file = self::$db_querier->count(DB_TABLE_UPLOAD, 'WHERE idcat = :idcat AND name = :name AND id <> :id AND user_id = :user_id', ['idcat' => $info_cat['idcat'], 'name' => $name, 'id' => $id_file, 'user_id' => $user_id]);
        if ($check_file > 0 || preg_match('`/|\\\|"|<|>|\||\?`u', stripslashes($name)))
        {
            return '/';
        }


        if ($admin) // Administration, ownership is not checked.
        {
            self::$db_querier->update(DB_TABLE_UPLOAD, ['name' => $name], 'WHERE id = :id', ['id' => $id_file]);
            return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
        }
        else
        {
            if ($user_id == $info_cat['user_id'])
            {
                self::$db_querier->update(DB_TABLE_UPLOAD, ['name' => $name], 'WHERE id = :id', ['id' => $id_file]);
                return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
            }
        }
        return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($previous_name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
    }


    // Moving a folder
    public static function Move_folder(int $move, int $to, int $user_id, bool $admin = false): string
    {
        if ($admin) // Administration, ownership is not checked.
        {
            // Changing the owner of the file.
            $change_user_id = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', ['id' => $to]);
            if (empty($change_user_id))
            {
                $change_user_id = -1;
            }
            if ($to != $move)
            {
                self::$db_querier->update(DB_TABLE_UPLOAD_CAT, ['id_parent' => $to, 'user_id' => $change_user_id], 'WHERE id = :id', ['id' => $move]);
            }
            return '';
        }
        else
        {
            if ($to == 0) // Moving a member's root folder
            {
                $get_mbr_folder = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'id', 'WHERE user_id = :user_id', ['user_id' => $user_id]);
                self::$db_querier->update(DB_TABLE_UPLOAD_CAT, ['id_parent' => $get_mbr_folder], 'WHERE id = :id AND user_id = :user_id', ['id' => $move, 'user_id' => $user_id]);
                return '';
            }


            // Checking ownership of the destination folder.
            $check_user_id_move = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', ['id' => $move]);
            $check_user_id_to = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', ['id' => $to]);
            if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
            {
                self::$db_querier->update(DB_TABLE_UPLOAD_CAT, ['id_parent' => $to], 'WHERE id = :id AND user_id = :user_id', ['id' => $move, 'user_id' => $user_id]);
                return '';
            }
            else
            {
                return 'e_auth';
            }
        }
    }


    // Moving a file
    public static function Move_file(int $move, int $to, int $user_id, bool $admin = false): string
    {
        if ($admin) // Administration, ownership is not checked.
        {
            // Changing the owner of the file
            $change_user_id = 0;
            try
            {
                $change_user_id = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', ['id' => $to]);
            }
            catch (RowNotFoundException $e) {}


            $properties = ['idcat' => $to];
            if (!empty($change_user_id))
            {
                $properties['user_id'] = $change_user_id;
            }


            self::$db_querier->update(DB_TABLE_UPLOAD, $properties, 'WHERE id = :id', ['id' => $move]);
            return '';
        }
        else
        {
            if ($to == 0) // Moving to the member's root folder.
            {
                $get_mbr_folder = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'id', 'WHERE user_id = :user_id AND id_parent = 0', ['user_id' => $user_id]);
                self::$db_querier->update(DB_TABLE_UPLOAD, ['idcat' => $get_mbr_folder], 'WHERE id = :id AND user_id = :user_id', ['id' => $move, 'user_id' => $user_id]);
                return '';
            }


            // Checking ownership of the destination folder.
            $check_user_id_move = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'user_id', 'WHERE id = :id', ['id' => $move]);
            $check_user_id_to = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', ['id' => $to]);
            if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
            {
                self::$db_querier->update(DB_TABLE_UPLOAD, ['idcat' => $to], 'WHERE id = :id AND user_id = :user_id', ['id' => $move, 'user_id' => $user_id]);
                return '';
            }
            else
            {
                return 'e_auth';
            }
        }
    }


    // Function that determines all subcategories of a category (recursive)
    public static function Find_subfolder(array $array_folders, int $id_cat, array &$array_child_folder): void
    {
        // Iterating through the categories and identifying child categories
        foreach ($array_folders as $key => $value)
        {
            if ($value == $id_cat)
            {
                $array_child_folder[] = $key;
                // Calling the function again for the child category
                self::Find_subfolder($array_folders, $key, $array_child_folder);
            }
        }
    }


    // Retrieving the current directory (administration).
    public static function get_admin_url(int $id_folder, string $pwd, string $member_link = ''): string
    {
        $parent_folder = [
            'id_parent' => '',
            'name' => '',
            'user_id' => ''
        ];
        try
        {
            $parent_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", ["id_parent", "name", "user_id"], 'WHERE id=:id', ['id' => $id_folder]);
        }
        catch (RowNotFoundException $e) {}


        if (!empty($parent_folder['id_parent']))
        {
            $pwd .= self::get_admin_url($parent_folder['id_parent'], $pwd, $member_link);
            return $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
        }
        else
        {
            return ($parent_folder['user_id'] == '-1') ? $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>' : $pwd . '/' . $member_link . '<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
        }
    }


    // Retrieving the current directory.
    public static function get_url(int $id_folder, string $pwd, bool $popup): string
    {
        $parent_folder = [
            'id_parent' => '',
            'name' => ''
        ];
        try
        {
            $parent_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", ["id_parent", "name", "user_id"], 'WHERE id=:id', ['id' => $id_folder]);
        }
        catch (RowNotFoundException $e) {}


        if (!empty($parent_folder['id_parent']))
        {
            $pwd .= self::get_url($parent_folder['id_parent'], $pwd, $popup);
            return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
        }
        else
        {
            return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
        }
    }


    // Retrieving the total storage space used by a member.
    public static function Member_memory_used(int $user_id): int
    {
        return self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'SUM(size)', 'WHERE user_id = :user_id', ['user_id' => $user_id]);
    }


    // MIME type to image conversion.
    public static function get_img_mimetype(string $type): array
    {
        $filetype = sprintf(LangLoader::get_message('upload.file.type', 'upload-lang'), TextHelper::strtoupper($type));
        switch ($type)
        {
            // Images
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'webp':
            case 'gif':
            case 'bmp':
            case 'svg':
            case 'nef':
            case 'raw':
            case 'ico':
            case 'tif':
            $img = 'far fa-file-image';
            $filetype = sprintf(LangLoader::get_message('upload.image.type', 'upload-lang'), TextHelper::strtoupper($type));
            break;
            // Archives
            case 'rar':
            case 'gz':
            case 'zip':
            case '7z':
                $img = 'far fa-file-archive';
                $filetype = sprintf(LangLoader::get_message('upload.zip.type', 'upload-lang'), TextHelper::strtoupper($type));
                break;
            // PDF
            case 'pdf':
                $img = 'far fa-file-pdf';
                $filetype = LangLoader::get_message('upload.adobe.pdf', 'upload-lang');
                break;
            // Audio
            case 'wav':
            case 'midi':
            case 'ogg':
            case 'mp3':
                $img = 'far fa-file-audio';
                $filetype = sprintf(LangLoader::get_message('upload.audio.type', 'upload-lang'), TextHelper::strtoupper($type));
                break;
            // Scripts
            case 'html':
            case 'tpl':
            case 'css':
            case 'js':
            case 'php':
                $img = 'far fa-file-code';
                break;
            // Video
            case 'wmv':
            case 'avi':
            case 'mp4':
            case 'mkv':
            case 'mpg':
            case 'mpeg':
            case 'mov':
                $img = 'far fa-file-video';
                break;
            // Executables
            case 'exe':
                $img = 'fa fa-cog';
                break;
            // Text
            case 'txt':
            case 'csv':
                $img = 'far fa-file-alt';
                break;
            // Office
            case 'xls':
            case 'xlsx':
            case 'xlsm':
            case 'gsheet':
            case 'ods':
                $img = 'far fa-file-exel';
                break;
            case 'doc':
            case 'docx':
            case 'gdoc':
            case 'odt':
                $img = 'far fa-file-word';
                break;
            case 'ppt':
            case 'pptx':
            case 'gslides':
            case 'odp':
                $img = 'far fa-file-powerpoint';
                break;
            // 3D files
            case 'blend':
            case '3ds':
            case 'obj':
            case 'stl':
            case 'fbx':
            case 'dae':
            case 'c4d':
                $img = 'fa fa-cubes';
                break;
            // Default
            default:
                $img = 'fa fa-file-upload';
                $filetype = sprintf(LangLoader::get_message('upload.document.type', 'upload-lang'), TextHelper::strtoupper($type));
        }

        return ['img' => $img, 'filetype' => $filetype];
    }
}
?>