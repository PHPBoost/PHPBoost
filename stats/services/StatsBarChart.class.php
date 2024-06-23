<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mx.koder@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 14
 * @since       PHPBoost 6.0 - 2024 06 14
*/

class StatsBarChart extends StatsChart
{
    protected $type = "bar";

    protected $additional_options = [
        'plugins' => [
            'legend' => [
                'display' => false
            ]
        ]
    ];

    public function add_average_dataset($label, $average = false, $color = '#36a2eb')
    {
        $number_datas = count($this->dataset_datas);
        if ($average === false)
        {
            $sum = 0;
            foreach ($this->dataset_datas as $val)
            {
                $sum += $val;
            }
            $average = NumberHelper::round($sum/$number_datas,1);
        }
        $dataset = [
            'label' => $label,
            'borderColor' => $color,
            'type' => 'line'
        ];
        $dataset['data'] = array_fill(0,$number_datas, $average);

        $this->additionals_datasets[] = $dataset;
    }

}