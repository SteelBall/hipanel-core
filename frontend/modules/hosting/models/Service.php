<?php
namespace frontend\modules\hosting\models;

use frontend\components\Model;
use frontend\components\ModelTrait;
use Yii;

class Service extends Model
{

    use ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'server_id', 'device_id', 'client_id', 'soft_id'],  'integer'],
            [['name', 'server', 'device', 'client', 'soft'],            'safe'],
            [['ip','bin','etc'],                                        'safe'],
            [['soft_type', 'soft_type_label', 'state', 'state_label'],  'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'soft_id'           => Yii::t('app', 'Soft ID'),
            'soft_type'         => Yii::t('app', 'Soft Type'),
            'soft_type_label'   => Yii::t('app', 'Soft type label'),
        ]);
    }
}