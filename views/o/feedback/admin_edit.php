<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 21 March 2018, 08:48 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		'Support Feedbacks'=>array('manage'),
		$model->displayname=>array('view','id'=>$model->feedback_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
