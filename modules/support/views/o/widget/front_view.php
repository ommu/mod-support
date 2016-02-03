<?php
/**
 * Support Widgets (support-widget)
 * @var $this WidgetController
 * @var $model SupportWidget
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 3 February 2016, 12:26 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Support Widgets'=>array('manage'),
		$model->widget_id,
	);
?>

<?php //begin.Messages ?>
<?php
if(Yii::app()->user->hasFlash('success'))
	echo Utility::flashSuccess(Yii::app()->user->getFlash('success'));
?>
<?php //end.Messages ?>

<?php $this->widget('application.components.system.FDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'widget_id',
			'value'=>$model->widget_id,
			//'value'=>'value'=>$model->widget_id != '' ? $model->widget_id : '-',
		),
		array(
			'name'=>'publish',
			'value'=>$model->publish == '1' ? Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : Chtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
			//'value'=>$model->publish,
		),
		array(
			'name'=>'cat_id',
			'value'=>$model->cat_id,
			//'value'=>'value'=>$model->cat_id != '' ? $model->cat_id : '-',
		),
		array(
			'name'=>'widget_source',
			'value'=>'value'=>$model->widget_source != '' ? $model->widget_source : '-',
			//'value'=>'value'=>$model->widget_source != '' ? CHtml::link($model->widget_source, Yii::app()->request->baseUrl.'/public/visit/'.$model->widget_source, array('target' => '_blank')) : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'creation_date',
			'value'=>Utility::dateFormat($model->creation_date, true),
		),
		array(
			'name'=>'creation_id',
			'value'=>$model->creation_id,
			//'value'=>'value'=>$model->creation_id != '' ? $model->creation_id : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>Utility::dateFormat($model->modified_date, true),
		),
		array(
			'name'=>'modified_id',
			'value'=>$model->modified_id,
			//'value'=>'value'=>$model->modified_id != '' ? $model->modified_id : '-',
		),
	),
)); ?>

<div class="dialog-content">
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Phrase::trans(4,0), array('id'=>'closed')); ?>
</div>
