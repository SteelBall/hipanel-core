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

use hipanel\widgets\Box;

class BoxedGridView extends GridView
{
    public $boxed = true;

    public $detailViewClass = BoxedDetailView::class;

    /**
     * To grid options, for example, you may add something like this for customize boxes:
     *    'boxOptions' => ['options' => ['class' => 'box-primary']],.
     * @var array
     */
    public $boxOptions = [];

    public function run()
    {
        if ($this->boxed) {
            Box::begin($this->boxOptions);
            parent::run();
            Box::end();
        } else {
            parent::run();
        }
    }
}
