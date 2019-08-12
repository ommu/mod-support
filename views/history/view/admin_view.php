<?php
/**
 * Support Feedback View Histories (support-feedback-view-history)
 * @var $this ViewController
 * @var $model SupportFeedbackViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 28 September 2018, 06:31 WIB
 * @modified date 28 September 2018, 06:31 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		Yii::t('phrase', 'Support')=>array('o/admin/manage'),
		Yii::t('phrase', 'Feedback View')=>array('o/view/manage'),
		Yii::t('phrase', 'History')=>array('manage'),
		$model->view->feedback->subject->title->message,
	);
?>

<?php //begin.Messages ?>
<div id="ajax-message">
<?php if(Yii::app()->user->hasFlash('success'))
	echo $this->flashMessage(Yii::app()->user->getFlash('success'), 'success');?>
</div>
<?php //end.Messages ?>

<div class="dialog-content">
	<?php echo $this->renderPartial('_detail', array('model'=>$model)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
