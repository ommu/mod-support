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
 * @modified date 27 September 2018, 15:19 WIB
 * @link https://github.com/ommu/mod-support
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'view_id',
			'value'=>$model->view_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->view_id)), $model->publish),
			'type'=>'raw',
		),
		array(
			'name'=>'subject_search',
			'value'=>$model->feedback->subject->title->message ? $model->feedback->subject->title->message : '-',
		),
		array(
			'name'=>'feedback_search',
			'value'=>$model->feedback->message ? $model->feedback->message : '-',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'views',
			'value'=>$model->views ? $model->views : '-',
		),
		array(
			'name'=>'view_date',
			'value'=>!in_array($model->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->view_date) : '-',
		),
		array(
			'name'=>'view_ip',
			'value'=>$model->view_ip ? $model->view_ip : '-',
		),
		array(
			'name'=>'modified_date',
			'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->modified_date) : '-',
		),
		array(
			'name'=>'modified_search',
			'value'=>$model->modified->displayname ? $model->modified->displayname : '-',
		),
		array(
			'name'=>'updated_date',
			'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->updated_date) : '-',
		),
	),
)); ?>