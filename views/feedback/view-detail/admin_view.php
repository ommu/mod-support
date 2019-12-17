<?php
/**
 * Support Feedback View Histories (support-feedback-view-history)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\ViewDetailController
 * @var $model ommu\support\models\SupportFeedbackViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 25 September 2017, 14:32 WIB
 * @modified date 28 January 2019, 14:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

if(!$small) {
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedback View Histories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->view->feedback->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="support-feedback-view-history-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'feedbackDisplayname',
		'value' => isset($model->view) ? $model->view->feedback->displayname : '-',
	],
	[
		'attribute' => 'feedbackEmail',
		'value' => isset($model->view) ? Yii::$app->formatter->asEmail($model->view->feedback->email) : '-',
	],
	[
		'attribute' => 'feedbackPhone',
		'value' => isset($model->view) ? $model->view->feedback->phone : '-',
	],
	[
		'attribute' => 'feedbackSubject',
		'value' => isset($model->view) ? $model->view->feedback->subject->title->message : '-',
	],
	[
		'attribute' => 'feedbackMessage',
		'value' => isset($model->view) ? $model->view->feedback->message : '-',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->view) ? $model->view->user->displayname : '-',
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
	],
	'view_ip',
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>