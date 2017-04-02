<?php
/**
 * Created by PhpStorm.
 * User: buntel
 * Date: 02.04.17
 * Time: 13:12
 */

use yii\helpers\Html;
?>
<p>You have entered the following information:</p>

<ul>
    <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
</ul>