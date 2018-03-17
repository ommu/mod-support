<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-support
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('select#SupportFeedbacks_subject_id').on('change', function() {
		var id = $(this).val();
		if(id == '') {
			$('div#subject_i').slideDown();
		} else {
			$('div#subject_i').slideUp();
		}
	});
EOP;
	$cs->registerScript('type', $js, CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'support-feedbacks-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">

	<fieldset>
		<div id="ajax-message">
			<?php echo $form->errorSummary($model); ?>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'displayname', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'displayname',array('maxlength'=>32,'class'=>'form-control'));?>
				<?php echo $form->error($model,'displayname');?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'email', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'email',array('maxlength'=>32,'class'=>'form-control'));?>
				<?php echo $form->error($model,'email');?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'phone', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'phone',array('maxlength'=>15,'class'=>'form-control'));?>
				<?php echo $form->error($model,'phone');?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'subject_id', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				$subjects = SupportFeedbackSubject::getSubject();
				if($subjects != null)
					echo $form->dropDownList($model, 'subject_id', $subjects, array('prompt'=>Yii::t('phrase', 'Other Subject'), 'class'=>'form-control'));
				else 
					echo $form->dropDownList($model, 'paresubject_idnt_id', array(0=>Yii::t('phrase', 'Other Subject')), array('class'=>'form-control'));?>
				<?php echo $form->error($model,'subject_id');?>
			</div>
		</div>

		<div id="subject_i" class="form-group row hide">
			<?php echo $form->labelEx($model,'subject_i', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'subject_i',array('maxlength'=>64,'class'=>'form-control'));?>
				<?php echo $form->error($model,'subject_i');?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'message', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php 
				//echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller'));
				$this->widget('yiiext.imperavi-redactor-widget.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>message,
					// Redactor options
					'options'=>array(
						//'lang'=>'fi',
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
					 ),
				)); ?>
				<?php echo $form->error($model,'message'); ?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>

<?php $this->endWidget(); ?>
