<?php
mvcimport('mvc/dao/criteria/restriction/irestriction');
abstract class SQLRestriction implements IRestriction
{
	public abstract static function eq($field, $value);
    public abstract static function gt($field, $value);
    public abstract static function lt($field, $value);
    public abstract static function gt_eq($field, $value);
    public abstract static function lt_eq($field, $value);
    public abstract static function neq($field, $value);
    public abstract static function in($field, $values);
    public abstract static function between($field, $min_value, $max_value);
    public abstract static function like($field, $pattern);
    public abstract static function match($field, $text_value);

    public abstract static function and_criterions($left_restriction, $right_restriction);
    public abstract static function or_criterions($left_restriction, $right_restriction);

    public static function not($restriction);
	
    public static function value($field_or_value, $field)
    {
        if (is_a($field_or_value, ModelField))
        {
            return $field_or_value->name();
        }
        else
        {
            return $field->escape($value);
        }
    }
}
?>