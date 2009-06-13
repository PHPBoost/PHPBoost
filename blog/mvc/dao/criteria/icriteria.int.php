<?php
interface ICriteria
{
    public function add($restriction);

    public function set_fetch_mode($fetch_attribute, $mode);
    public function set_projection($projection);
    public function set_max_results($max_results);
    public function set_offset($offset);

    public function unique_result();
    public function results_list();
}
?>