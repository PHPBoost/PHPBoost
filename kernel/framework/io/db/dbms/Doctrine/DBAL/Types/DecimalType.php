<?php

/**
 * Type that maps an SQL DECIMAL to a PHP double.
 *
 * @since 2.0
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