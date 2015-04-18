<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 07.04.15
 * Time: 15:22
 */
namespace frontend\components\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{

    /**
     * @var
     */
    private $buttonOptions = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->getCountButtons();
        $this->template = ($this->getCountButtons() > 1) ? '<div class="btn-group">' . $this->template . '</ul></div>' : '<div class="btn-group">' . $this->template . '</div>';
    }

    public function getCountButtons() {
        return preg_match_all('/\\{([\w\-\/]+)\\}/', $this->template);
    }

    public function renderFirstButton($item) {
        return ($this->getCountButtons() > 1) ? $item . '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">' : $item;
    }

    public function renderOtherButtons($item) {
        return '<li>' . $item . '</li>';
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons() {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default',
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'Update'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a(Yii::t('yii', 'Delete'), $url, $options);
            };
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index) {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            static $isFirst = true;
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                $renderedItem = call_user_func($this->buttons[$name], $url, $model, $key);
                $result = ($isFirst == true) ? $this->renderFirstButton($renderedItem) : $this->renderOtherButtons($renderedItem);
                $isFirst = false;

                return $result;
            }
            else {
                return '';
            }

        }, $this->template);
    }
}