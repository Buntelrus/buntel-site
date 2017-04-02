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

class EntryForm extends Model {
    public $name;
    public $email;

    public function rules() {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
        ];
    }
}