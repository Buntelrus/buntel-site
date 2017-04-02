<?php
/**
 * Created by PhpStorm.
 * User: buntel
 * Date: 02.04.17
 * Time: 12:56
 */

namespace app\models;

use Yii;
use yii\base\Model;

class BSEpisodes extends Model {
    public $seasonIndex;

    public function rules() {
        return [
            [['seasonIndex'], 'required'],
            ['seasonIndex', 'integer'],
        ];
    }
}