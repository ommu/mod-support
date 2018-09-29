<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 08:49 WIB
 * @modified date 27 September 2018, 15:17 WIB
 * @link https://github.com/ommu/mod-support
 *
 */
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'feedback_id',
			'value'=>$model->feedback_id,
		),
		array(
			'name'=>'publish',
			'value'=>$this->quickAction(Yii::app()->controller->createUrl('publish', array('id'=>$model->feedback_id)), $model->publish),
			'type'=>'raw',
		),
		array(
			'name'=>'subject_id',
			'value'=>$model->subject->title->message ? $model->subject->title->message : '-',
		),
		array(
			'name'=>'user_search',
			'value'=>$model->user->displayname ? $model->user->displayname : '-',
		),
		array(
			'name'=>'email',
			'value'=>$model->email ? $model->email : '-',
		),
		array(
			'name'=>'displayname',
			'value'=>$model->displayname ? $model->displayname : '-',
		),
		array(
			'name'=>'phone',
			'value'=>$model->phone ? $model->phone : '-',
		),
		array(
			'name'=>'message',
			'value'=>$model->message ? $model->message : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'reply_message',
			'value'=>$model->reply_message ? $model->reply_message : '-',
			'type'=>'raw',
		),
		array(
			'name'=>'replied_date',
			'value'=>!in_array($model->replied_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->replied_date) : '-',
		),
		array(
			'name'=>'replied_search',
			'value'=>$model->replied->displayname ? $model->replied->displayname : '-',
		),
		array(
			'name'=>'creation_date',
			'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')) ? $this->dateFormat($model->creation_date) : '-',
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
		array(
			'name'=>'reply_i',
			'value'=>$this->parseYesNo($model->reply_i),
			'type'=>'raw',
		),
		array(
			'name'=>'view_i',
			'value'=>$this->parseYesNo($model->view_i),
			'type'=>'raw',
		),
		array(
			'name'=>'user_i',
			'value'=>CHtml::link($model->user_i ? $model->user_i : 0, Yii::app()->controller->createUrl('o/user/manage', array('feedback'=>$model->feedback_id))),
			'type'=>'raw',
		),
	),
)); ?>