<?php
/**
 * Support Feedback View Histories (support-feedback-view-history)
 * @var $this ViewController
 * @var $model SupportFeedbackViewHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 28 September 2018, 06:31 WIB
 * @modified date 28 September 2018, 06:31 WIB
 * @link https://github.com/ommu/mod-support
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value'=>$model->id,
		),
		array(
			'name'=>'subject_search',
			'value'=>$model->view->feedback->subject->title->message ? $model->view->feedback->subject->title->message : '-',
		),
		array(
			'name'=>'feedback_search',
			'value'=>$model->view->feedback->message ? $model->view->feedback->message : '-',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->view->user->displayname ? $model->view->user->displayname : '-',
		),
		array(
			'name'=>'view_date',
			'value'=>!in_array($model->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->view_date) : '-',
		),
		array(
			'name'=>'view_ip',
			'value'=>$model->view_ip ? $model->view_ip : '-',
		),
	),
)); ?>