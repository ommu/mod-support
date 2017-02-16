<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/ommu/Support
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Support Feedbacks'=>array('manage'),
		$model->feedback_id=>array('view','id'=>$model->feedback_id),
		'Update',
	);
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'support-feedbacks-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<fieldset>

		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="clearfix info">
			<label><?php echo $model->getAttributeLabel('message')?></label>
			<div class="desc">				
				<?php echo $model->message;?><br/>
				<span class="small-px"><strong><?php echo $model->displayname;?></strong><br/><?php echo $model->email;?><br/>Date: <?php echo $model->creation_date;?></span>
			</div>
		</div>

		<div class="clearfix <?php echo $model->reply_id != 0 ? 'info' : ''?>">
			<?php echo $form->labelEx($model,'message_reply'); ?>
			<div class="desc">
				<?php if($model->reply_id == 0) {
					echo $form->textArea($model,'message_reply',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller'));
					echo $form->error($model,'message_reply');
				} else {?>
				<?php echo $model->message_reply;?><br/>
				<span class="small-px">Date: <?php echo $model->replied_date;?></span>
				<?php }?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo $model->reply_id == 0 ? CHtml::submitButton(Yii::t('phrase', 'Reply') ,array('onclick' => 'setEnableSave()')) : ''; ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>
