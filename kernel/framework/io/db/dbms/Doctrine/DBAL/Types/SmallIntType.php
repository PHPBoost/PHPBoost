<?php
/**
 * Type that maps a database SMALLINT to a PHP integer.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class SmallIntType
{
    public function getName()
    {
        return "SmallInteger";
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getSmallIntTypeDeclarationSql($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (int) $value;
    }

    public function getTypeCode()
    {
        return self::CODE_INT;
    }
}

?>
