<?php
/**
 * Support Widgets (support-widget)
 * @var $this WidgetController
 * @var $model SupportWidget
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 3 February 2016, 12:26 WIB
 * @link https://github.com/ommu/ommu-support
 *
 */

	$this->breadcrumbs=array(
		'Support Widgets'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
)); ?>