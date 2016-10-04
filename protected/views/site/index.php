<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Добро пожаловать <?php if(!Yii::app()->user->isGuest){
		echo( Yii::app()->user->name);
	}?>

	</i></h1>
<?php //echo CHtml::encode(Yii::app()->name); ?>

<p>Вау Yii</p>

