<?php
interface IRestriction
{
    public static function eq($field, $value);
    public static function gt($field, $value);
    public static function lt($field, $value);
    public static function gt_eq($field, $value);
    public static function lt_eq($field, $value);
    public static function neq($field, $value);
    public static function in($field, $values);
    public static function between($field, $min_value, $max_value);
    public static function like($field, $pattern);
    public static function match($field, $text_value);

    public static function and_criterions($left_restriction, $right_restriction);
    public static function or_criterions($left_restriction, $right_restriction);

    public static function not($restriction);
}
?>