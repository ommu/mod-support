<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this ViewController
 * @var $model SupportFeedbackView
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 11 May 2017, 23:13 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		'Feedback Views'=>array('manage'),
		$model->view_id,
	);
?>

<div class="dialog-content">
	<?php echo $this->renderPartial('_detail', array('model'=>$model)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
