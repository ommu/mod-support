<?php
/**
 * Support Contacts (support-contacts)
 * @var $this ContactsController
 * @var $model SupportContacts
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-support
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Ommu Metas'=>array('manage'),
		$model->id=>array('view','id'=>$model->id),
		'Update',
	);
?>

<div class="form" name="post-on">

	<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'support-contacts-form',
		'enableAjaxValidation'=>true,
		//'htmlOptions' => array('enctype' => 'multipart/form-data')
	)); ?>

	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_location');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_location',array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_location'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'A struct containing metadata defining the location of a place');?></span>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'office_name', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_name', array('maxlength'=>64, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_name'); ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_place');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'office_place',array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<div class="pt-10"></div>
				<?php echo $form->textField($model,'office_village', array('maxlength'=>32, 'class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_village'))); ?>
				<?php echo $form->textField($model,'office_district', array('maxlength'=>32, 'class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_district'))); ?>
				<?php echo $form->error($model,'office_place'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'The number, street, district and village of the postal address for this business');?></span>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_city_id');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->dropDownList($model,'office_city_id', OmmuZoneCity::getCity($model->office_province_id), array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_city_id'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'The city (or locality) line of the postal address for this business');?></span>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_province_id');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->dropDownList($model,'office_province_id', OmmuZoneProvince::getProvince($model->office_country_id), array('class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_province_id'); ?>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_zipcode');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_zipcode',array('maxlength'=>6, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_zipcode'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'The postcode (or ZIP code) of the postal address for this business');?></span>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_hour');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'office_hour',array('rows'=>6, 'cols'=>50, 'class'=>'form-control smaller')); ?>
				<?php echo $form->error($model,'office_hour'); ?>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'office_phone', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_phone',array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_phone'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'A telephone number to contact this business');?></span>
			</div>
		</div>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'office_fax', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_fax',array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_fax'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'A fax number to contact this business');?></span>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_hotline');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_hotline',array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_hotline'); ?>
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('office_email');?> <span class="required">*</span></label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textField($model,'office_email',array('maxlength'=>32, 'class'=>'form-control')); ?>
				<?php echo $form->error($model,'office_email'); ?>
				<span class="small-px silent"><?php echo Yii::t('phrase', 'An email address to contact this business');?></span>
			</div>
		</div>
		
		<div class="form-group row submit">
			<label class="col-form-label col-lg-4 col-md-3 col-sm-12">&nbsp;</label>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
			</div>
		</div>

	</fieldset>
	<?php $this->endWidget(); ?>

</div>
