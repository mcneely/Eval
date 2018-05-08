<?php

/* @var $this yii\web\View */

use yii\amcharts\Widget;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>

<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 text-center"><h2><?= $title?></h2></div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-2">
                <div><?= Widget::widget(['chartConfiguration' => $chart]) ?></div>
            </div>

        </div>

    </div>
</div>
