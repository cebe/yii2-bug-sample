<?php


namespace app\models;


use yii\db\ActiveRecord;
use yii\validators\RangeValidator;

class A extends ActiveRecord
{

    public function rules()
    {
        return [
            [['special_b_id'], RangeValidator::class, 'range' => $this->getIds()]
        ];
    }

    public function getBs()
    {
        return $this->hasMany(B::class, ['a_id' => 'id']);
    }

    public function getIds() {
        foreach($this->getBs()->each() as $b) {
            yield $b->id;
        }
    }
}