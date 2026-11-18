<?php
/**
 * Type that maps a PHP object to a clob SQL type.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2013 01 01
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ObjectType extends Type
{
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return TextHelper::serialize($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return TextHelper::unserialize($value);
    }

    public function getName()
    {
        return 'Object';
    }
}

?>
