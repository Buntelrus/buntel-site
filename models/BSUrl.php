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

class BSUrl extends Model {
    public $seriesUrl;
    public $seasonIndex;
    public $episodeIndex;

    public function rules() {
        return [
            [['seriesUrl'], 'required'],
            ['seriesUrl', 'url'],
            ['seasonIndex', 'integer'],
            ['episodeIndex', 'integer'],
        ];
    }
}