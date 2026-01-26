<?php
/**
 * Type that maps an SQL TIME to a PHP DateTime object.
 * @package     Doctrine
 * @subpackage  DBAL\Types
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @version     PHPBoost 6.0 - last update: 2013 01 01
 * @since       PHPBoost 4.0 - 2013 01 01
*/

class TimeType extends Type
{
    public function getName()
    {
        return 'Time';
    }

    /**
     * {@inheritdoc}
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getTimeTypeDeclarationSql($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     *
     * @override
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? $value->format($platform->getTimeFormatString()) : null;
    }

    /**
     * {@inheritdoc}
     *
     * @override
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value !== null)
            ? \DateTime::createFromFormat($platform->getTimeFormatString(), $value) : null;
    }
}

?>
