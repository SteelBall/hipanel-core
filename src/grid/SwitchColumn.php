<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\SwitchInput;
use yii\helpers\ArrayHelper;

class SwitchColumn extends DataColumn
{
    /** {@inheritdoc} */
    public $format = 'raw';

    /** @var boolean Filtering is disabled for SwitchColumn */
    public $filter = false;

    /** @var array pluginOptions for widget */
    public $pluginOptions = [];

    /** {@inheritdoc} */
    public $defaultOptions = [
        'pluginOptions' => [
            'size' => 'mini',
        ],
    ];

    /**
     * @var array options that will be passed to [[SwitchInput]] widget
     */
    public $switchInputOptions = [];

    public function getDataCellValue($model, $key, $index)
    {
        return SwitchInput::widget(ArrayHelper::merge([
            'name' => 'swc' . $key . $model->id,
            'pluginOptions' => ArrayHelper::merge($this->pluginOptions, [
                'state' => (bool) parent::getDataCellValue($model, $key, $index),
            ]),
        ], $this->switchInputOptions));
    }
}
