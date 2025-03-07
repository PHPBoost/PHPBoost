<?php
/**
 * Type that maps an SQL boolean to a PHP boolean.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class BooleanType extends Type
{
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBooleanTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $platform->convertBooleans($value);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (bool) $value;
    }

    public function getName()
    {
        return 'boolean';
    }
}

?>
