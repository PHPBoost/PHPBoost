<?php
class MySQLRestriction extends SQLRestriction
{
    public static function eq($field, $value)
    {
        return  $field->name() . '=' . self::value($value, $field);
    }
    public static function gt($field, $value)
    {
        return  $field->name() . '>' . self::value($value, $field);
    }
    public static function lt($field, $value)
    {
        return  $field->name() . '<' . self::value($value, $field);
    }
    public static function gt_eq($field, $value)
    {
        return  $field->name() . '>=' . self::value($value, $field);
    }
    public static function lt_eq($field, $value)
    {
        return  $field->name() . '<=' . self::value($value, $field);
    }
    public static function neq($field, $value)
    {
        return  $field->name() . '!=' . self::value($value, $field);
    }
    public static function in($field, $values)
    {
        $nb = count(values);
        for ($i = 0; i < $nb; $i++)
        {
            $values[$i] = $field->escape($value[$i]);
        }
        return $field->name() . ' IN (' . implode(',', $values) . ')';
    }
    public static function between($field, $field_or_value_min, $field_or_value_max)
    {
        return $field->name() . ' BETWEEN (' . self::value($field_or_value_min) . ', ' . self::value($field_or_value_max) . ')';
    }
    public static function like($field, $pattern)
    {
        return $field->name() . ' LIKE ' . $field->escape($pattern);
    }
    public static function match($field, $text_value)
    {
        return 'MATCH (' . $field->name() . ') AGAINST (' . $field->escape($text_value) . ')';
    }

    public static function and_criterions($left_restriction, $right_restriction)
    {
        return '(' . $left_restriction . ') AND (' . $right_restriction . ')';
    }
    public static function or_criterions($left_restriction, $right_restriction)
    {
        return '(' . $left_restriction . ') OR (' . $right_restriction . ')';
    }
    public static function not($restriction)
    {
        return 'NOT (' . $restriction . ')';
    }
}
?>