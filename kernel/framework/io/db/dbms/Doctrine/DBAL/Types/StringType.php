<?php
/**
 * Type that maps an SQL VARCHAR to a PHP string.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 5.3 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class StringType extends Type
{
    /** @override */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSql($fieldDeclaration);
    }

    /** @override */
    public function getDefaultLength(AbstractPlatform $platform)
    {
        return $platform->getVarcharDefaultLength();
    }

    /** @override */
    public function getName()
    {
        return 'string';
    }
}

?>
