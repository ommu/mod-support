<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 27 September 2018, 15:17 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('select#SupportFeedbacks_subject_id').on('change', function() {
		var id = $(this).val();
		if(id == '') {
			$('div#subject_i').slideDown().css('display', '').removeClass('hide');
		} else {
			$('div#subject_i').slideUp().addClass('hide');
		}
	});
EOP;
	$cs->registerScript('type', $js, CClientScript::POS_END);
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

		<?php //begin.Messages ?>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>
		<?php //begin.Messages ?>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'displayname', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model, 'displayname', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'displayname'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'email', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model, 'email', array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'email'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'phone', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model, 'phone', array('maxlength'=>15, 'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'phone'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'subject_id', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php $subject = SupportFeedbackSubject::getSubject();
				if($subject != null)
					echo $form->dropDownList($model, 'subject_id', $subject, array('prompt'=>Yii::t('phrase', 'Other Subject'), 'class'=>'form-control'));
				else
					echo $form->dropDownList($model, 'subject_id', array(0=>Yii::t('phrase', 'Other Subject')), array('class'=>'form-control')); ?>
				<?php echo $form->error($model, 'subject_id'); ?>
			</div>
		</div>

		<div id="subject_i" class="form-group row hide">
			<?php echo $form->labelEx($model, 'subject_i', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textField($model, 'subject_i', array('maxlength'=>64, 'class'=>'form-control'));?>
				<?php echo $form->error($model, 'subject_i');?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'message', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->textArea($model, 'message', array('rows'=>6, 'cols'=>50, 'class'=>'medium form-control')); ?>
				<?php echo $form->error($model, 'message'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-4 col-md-4 col-sm-12')); ?>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<?php echo $form->checkBox($model, 'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model, 'publish'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Cancel'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>