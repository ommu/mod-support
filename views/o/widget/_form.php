<?php
/**
 * Support Widgets (support-widget)
 * @var $this WidgetController
 * @var $model SupportWidget
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 3 February 2016, 12:26 WIB
 * @modified date 20 March 2018, 14:30 WIB
 * @link https://github.com/ommu/mod-support
 *
 */
?>

<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
	'id'=>'support-widget-form',
	'enableAjaxValidation'=>true,
	/*
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data',
	),
	*/
)); ?>

<div class="dialog-content">
	<fieldset>

		<?php 
		if($model->category->publish != '2') {
			$category = SupportContactCategory::getCategory(1, 'widget');
			if($category != null) {?>
			<div class="form-group row">
				<label class="col-form-label col-lg-4 col-md-3 col-sm-12"><?php echo $model->getAttributeLabel('cat_id');?> <span class="required">*</span></label>
				<div class="col-lg-8 col-md-9 col-sm-12">
					<?php
					if($model->isNewRecord) {
						$category = SupportContactCategory::getCategory('1', 'contact');
						if($category != null)
							echo $form->dropDownList($model, 'cat_id', $category, array('prompt'=>'', 'class'=>'form-control'));
						else
							echo $form->dropDownList($model, 'cat_id', array('prompt'=>Yii::t('phrase', 'No Parent')), array('class'=>'form-control'));
					} else {?>
						<strong><?php echo $model->category->title->message; ?></strong>
					<?php }?>
					<?php echo $form->error($model,'cat_id'); ?>
				</div>
			</div>
			<?php }
		}?>

		<div class="form-group row">
			<?php echo $form->labelEx($model, 'widget_source', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
				<?php echo $form->textArea($model, 'widget_source', array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
				<?php echo $form->error($model, 'widget_source'); ?>
			</div>
		</div>

		<div class="form-group row publish">
			<?php echo $form->labelEx($model, 'publish', array('class'=>'col-form-label col-lg-4 col-md-3 col-sm-12')); ?>
			<div class="col-lg-8 col-md-9 col-sm-12">
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