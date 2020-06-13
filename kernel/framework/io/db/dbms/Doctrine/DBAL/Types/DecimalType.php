<?php
/**
 * Type that maps an SQL DECIMAL to a PHP double.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class DecimalType extends Type
{
    public function getName()
    {
        return 'Decimal';
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDecimalTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (double) $value;
    }
}

?>
