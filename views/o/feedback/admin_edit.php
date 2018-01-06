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

	$this->breadcrumbs=array(
		'Support Feedbacks'=>array('manage'),
		$model->feedback_id=>array('view','id'=>$model->feedback_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
