<?php
/**
 * Type that maps an SQL DATE to a PHP Date object.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class DateType extends Type
{
    public function getName()
    {
        return 'Date';
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format($platform->getDateFormatString()) : null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? \DateTime::createFromFormat($platform->getDateFormatString(), $value) : null;
    }
}

?>
