<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this ViewController
 * @var $model SupportFeedbackView
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 09:41 WIB
 * @modified date 27 September 2018, 15:19 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		Yii::t('phrase', 'Support')=>array('o/feedback/manage'),
		Yii::t('phrase', 'Feedback View')=>array('manage'),
		$model->feedback->subject->title->message=>array('view','id'=>$model->view_id),
		Yii::t('phrase', 'Publish'),
	);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'support-feedback-view-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="dialog-content">
		<?php echo $model->publish == 1 ? Yii::t('phrase', 'Are you sure you want to unpublish this item?') : Yii::t('phrase', 'Are you sure you want to publish this item?')?>
	</div>
	<div class="dialog-submit">
		<?php echo CHtml::submitButton($title, array('onclick' => 'setEnableSave()')); ?>
		<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
	</div>

<?php $this->endWidget(); ?>