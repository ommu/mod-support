<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this FeedbackController
 * @var $model SupportFeedbacks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 23 August 2017, 08:49 WIB
 * @link https://github.com/ommu/ommu-support
 *
 */

	$this->breadcrumbs=array(
		'Support Feedbacks'=>array('manage'),
		$model->feedback_id,
	);
?>

<div class="dialog-content">
	<?php $this->widget('application.libraries.core.components.system.FDetailView', array(
		'data'=>$model,
		'attributes'=>array(
			array(
				'name'=>'feedback_id',
				'value'=>$model->feedback_id,
			),
			array(
				'name'=>'publish',
				'value'=>$model->publish == '1' ? CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/publish.png') : CHtml::image(Yii::app()->theme->baseUrl.'/images/icons/unpublish.png'),
				'type'=>'raw',
			),
			array(
				'name'=>'subject_id',
				'value'=>$model->subject_id ? $model->subject->title->message : '-',
			),
			array(
				'name'=>'user_id',
				'value'=>$model->user_id ? $model->user->displayname : '-',
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
				'name'=>'reply_search',
				'value'=>$model->view->reply_condition ? Yii::t('phrase', 'Yes') : Yii::t('phrase', 'No'),
			),
			array(
				'name'=>'reply_message',
				'value'=>$model->reply_message ? $model->reply_message : '-',
				'type'=>'raw',
			),
			array(
				'name'=>'replied_date',
				'value'=>!in_array($model->replied_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->replied_date, true) : '-',
			),
			array(
				'name'=>'replied_id',
				'value'=>$model->replied_id ? $model->replied->displayname : '-',
			),
			array(
				'name'=>'views_search',
				'value'=>$model->view->views ? $model->view->views : '0',
			),
			array(
				'name'=>'users_search',
				'value'=>$model->view->view_users ? $model->view->view_users : '0',
			),
			array(
				'name'=>'creation_date',
				'value'=>!in_array($model->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->creation_date, true) : '-',
			),
			array(
				'name'=>'modified_date',
				'value'=>!in_array($model->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->modified_date, true) : '-',
			),
			array(
				'name'=>'modified_id',
				'value'=>$model->modified_id ? $model->modified->displayname : '-',
			),
			array(
				'name'=>'updated_date',
				'value'=>!in_array($model->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00')) ? Utility::dateFormat($model->updated_date, true) : '-',
			),
		),
	)); ?>
</div>
<div class="dialog-submit">
	<?php echo CHtml::button(Yii::t('phrase', 'Close'), array('id'=>'closed')); ?>
</div>
