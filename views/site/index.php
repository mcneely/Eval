<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Graph Application';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">

            <div class="col-lg-4 col-lg-offset-4">
                <h2>Upload SpreadSheet</h2>
                <?php $form = ActiveForm::begin([
                                                    'id'          => 'upload-form',
                                                    'layout'      => 'horizontal',
                                                    'fieldConfig' => [
                                                        'template'     => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                                        'labelOptions' => ['class' => 'col-lg-1 control-label'],
                                                    ],
                                                ]); ?>

                <?= $form->field($model, 'file')->fileInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        </div>

    </div>
</div>
