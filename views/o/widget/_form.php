<?php
/**
 * Support Widgets (support-widget)
 * @var $this WidgetController
 * @var $model SupportWidget
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 3 February 2016, 12:26 WIB
 * @link https://github.com/ommu/ommu-support
 * @contact (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.core.components.system.OActiveForm', array(
	'id'=>'support-widget-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<div class="dialog-content">
	<?php //begin.Messages ?>
	<div id="ajax-message">
		<?php echo $form->errorSummary($model); ?>
	</div>
	<?php //begin.Messages ?>

	<fieldset>

		<?php 
		if($model->cat->publish != 2) {
			$category = SupportContactCategory::getCategory(1, 'widget');
			if($category != null) {?>
			<div class="form-group row">
				<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('cat_id');?> <span class="required">*</span></label>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<?php
					if($model->isNewRecord) {
						$category = SupportContactCategory::getCategory(1, 'contact');
						if($category != null)
							echo $form->dropDownList($model,'cat_id', $category, array('prompt'=>'', 'class'=>'form-control'));
						else
							echo $form->dropDownList($model,'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent'), 'class'=>'form-control'));
					} else {?>
						<strong><?php echo $model->cat->title->message; ?></strong>
					<?php }?>
					<?php echo $form->error($model,'cat_id'); ?>
				</div>
			</div>
			<?php }
		}?>

		<div class="form-group row">
			<?php echo $form->labelEx($model,'widget_source', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model,'widget_source',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'widget_source'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model,'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->checkBox($model,'publish', array('class'=>'form-control')); ?>
				<?php echo $form->labelEx($model, 'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
				<?php /*<div class="small-px silent"></div>*/?>
			</div>
		</div>

	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save') ,array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
<?php $this->endWidget(); ?>


