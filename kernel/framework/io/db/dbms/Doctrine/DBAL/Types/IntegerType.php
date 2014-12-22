<?php

/**
 * Type that maps an SQL INT to a PHP integer.
 *
 */
class IntegerType extends Type
{
    public function getName()
    {
        return 'Integer';
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSql($fieldDeclaration);
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