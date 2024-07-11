<?php
/**
 * This class provides easy ways to create a Pie chart
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 6.0 - 2024 06 29
*/

class BarChart extends Chart
{
    protected $type = "bar";

    /**
     * Add a dataset average to display a line behind the bars
     * @param ChartDataset $origin_dataset Dataset to take values to create average
     * @param string $label Displayed name
     * @param mixed $average If given, will be displayed. Otherwise, average will be calculated
     * @param mixed $color RGB color line
     */
    public function add_average_dataset(ChartDataset $origin_dataset, string $label, $average = false, $color = '75, 192, 192'):void
    {
        $dataset = new ChartDataset($label);
        $dataset->set_colors([$color]);
        $number_datas = count($origin_dataset->data_values);
        if ($average === false)
        {
            $sum = 0;
            foreach ($origin_dataset->data_values as $val)
            {
                $sum += $val;
            }
            $average = NumberHelper::round($sum/$number_datas,1);
        }
        $dataset->type = 'line';
        $dataset->use_many_colors = false;
        $dataset->set_datas(array_fill(0,$number_datas, $average));
        $this->add_dataset($dataset);
        
    }
}