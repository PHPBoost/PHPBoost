<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 6.0 - 2024 06 14
*/

class StatsPieChart extends PieChart
{
    protected $options = [
        'plugins' => [
            'legend' => [
                'position' => 'right'
            ]
        ],
        'rotation' => -90,
        'circumference' => 180,
        'cutout' => '40%',
        'maintainAspectRatio' => false,
        'layout' => [
            'padding' => 20
        ],
        'hoverOffset' => 20,
    ];
    
}