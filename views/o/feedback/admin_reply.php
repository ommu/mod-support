<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 21 March 2018, 08:48 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		Yii::t('phrase', 'Support')=>array('manage'),
		$model->subject->title->message=>array('view','id'=>$model->feedback_id),
		Yii::t('phrase', 'Reply'),
	);
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'support-feedbacks-form',
	'enableAjaxValidation'=>true,
	/*
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
		'on_post' => '',
	),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	*/
)); ?>
<div class="dialog-content">

	<fieldset>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12"><?php echo $model->getAttributeLabel('feedback_id')?></label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php 
				$subject = $model->subject_id ? $model->subject->title->message : '-';
				echo Yii::t('phrase', 'Subject: {subject}', array('{subject}'=>$subject));?><br/>
				<?php echo $model->message;?><br/>
				<div class="small-px"><strong><?php echo $model->displayname;?></strong><br/><?php echo $model->email;?><br/>Date: <?php echo $model->creation_date;?></div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-4 col-sm-12">
				<?php echo $model->getAttributeLabel('reply_message');?> <span class="required">*</span>
			</label>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textArea($model, 'reply_message', array('rows'=>6, 'cols'=>50, 'class'=>'medium form-control')); ?>
				<?php echo $form->error($model, 'reply_message'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') , array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>
