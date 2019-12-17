<?php
/**
 * Type that maps an SQL DATETIME/TIMESTAMP to a PHP DateTime object.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 5.3 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class DateTimeType extends Type
{
    public function getName()
    {
        return 'DateTime';
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format($platform->getDateTimeFormatString()) : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? DateTime::createFromFormat($platform->getDateTimeFormatString(), $value) : null;
    }
}

?>
