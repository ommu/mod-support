<?php
/**
 * Support Feedback Subjects (support-feedback-subject)
 * @var $this SubjectController
 * @var $model SupportFeedbackSubject
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 15 March 2018, 14:05 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		'Feedback Subjects'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->subject_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
