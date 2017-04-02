<?php
/**
 * Created by PhpStorm.
 * User: buntel
 * Date: 02.04.17
 * Time: 20:11
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

\app\assets\BSAsset::register($this);
?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'seriesUrl')->label('Burning Series Url:') ?>

<div class="form-group">
  <?= Html::submitButton('Download', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

<div class="bs" id="series-data">
    <div class="download-step" id="step1">
        <h3>Step 1:<span>Parse Seasons</span></h3>
    </div>
    <div class="download-step" id="step2">
        <h3>Step 2:<span>Download und mehr...</span></h3>
    </div>
    <div class="download-step" id="step3">
        <h3>Step 3:<span>Create Zip</span></h3>
    </div>
</div>
