<?php
/**
 * This class provides easy ways to create a Pie chart
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 6.0 - 2024 06 29
*/

class PieChart extends Chart
{
    protected $type = "pie";

    protected $options = [
        'plugins' => [
            'legend' => [
                'position' => 'right'
            ]
        ],
        'maintainAspectRatio' => false,
        'layout' => [
            'padding' => 10
        ],
        'hoverOffset' => 10,
    ];
}