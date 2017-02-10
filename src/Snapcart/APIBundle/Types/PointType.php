<?php

namespace Snapcart\APIBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Snapcart\APIBundle\Entity\Point;


/**
 * Created by PhpStorm.
 * User: OP-User
 * Date: 2/10/2017
 * Time: 1:45 PM
 */
class PointType extends Type
{
    const NAME = 'point';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        // TODO: Implement getSQLDeclaration() method.
        return 'POINT';
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $abstractPlatform)
    {
        if ($value instanceof Point) {
            $value = sprintf('POINT(%F %F)', $value->getLng(), $value->getLat());
        }

        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $abstractPlatform)
    {
        list($lng, $lat) = sscanf($value, 'POINT(%f %f)');

        return new Point($lat, $lng);
    }

    public function canRequireSQLConversion()
    {
        return true;
    }

    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return sprintf('AsText(%s)', $sqlExpr);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('PointFromText(%s)', $sqlExpr);
    }
}