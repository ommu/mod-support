<?php
/**
 * Support Feedback Users (support-feedback-user)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\UserController
 * @var $model ommu\support\models\SupportFeedbackUser
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 September 2017, 15:40 WIB
 * @modified date 28 January 2019, 12:21 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedback'), 'url' => ['/support/feedback/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->feedback->displayname, 'url' => ['view', 'id' => $model->feedback->feedback_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User'), 'url' => ['manage', 'feedback' => $model->feedback_id]];
    $this->params['breadcrumbs'][] = $model->user->displayname;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="support-feedback-user-view">

<?php
$attributes = [
	'id',
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
	],
	[
		'attribute' => 'feedbackDisplayname',
		'value' => isset($model->feedback) ? $model->feedback->displayname : '-',
	],
	[
		'attribute' => 'feedbackEmail',
		'value' => isset($model->feedback) ? Yii::$app->formatter->asEmail($model->feedback->email) : '-',
	],
	[
		'attribute' => 'feedbackPhone',
		'value' => isset($model->feedback) ? $model->feedback->phone : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'feedbackSubject',
		'value' => isset($model->feedback) ? $model->feedback->subject->title->message : '-',
	],
	[
		'attribute' => 'feedbackMessage',
		'value' => isset($model->feedback) ? $model->feedback->message : '-',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>