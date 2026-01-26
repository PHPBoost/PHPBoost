<?php
/**
 * Type that maps a database BIGINT to a PHP string.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @author      robo
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class BigIntType extends Type
{
    public function getName()
    {
        return 'BigInteger';
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBigIntTypeDeclarationSql($fieldDeclaration);
    }

    public function getTypeCode()
    {
        return self::CODE_INT;
    }
}

?>
