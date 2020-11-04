<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\ViewController
 * @var $model ommu\support\models\SupportFeedbackView
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 25 September 2017, 14:11 WIB
 * @modified date 28 January 2019, 12:21 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedback'), 'url' => ['/support/feedback/admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->feedback->displayname, 'url' => ['view', 'id'=>$model->feedback->feedback_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['manage', 'feedback'=>$model->feedback_id]];
    $this->params['breadcrumbs'][] = $model->user->displayname;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->view_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="support-feedback-view-view">

<?php
$attributes = [
	[
		'attribute' => 'view_id',
		'value' => $model->view_id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
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
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->views;
			return Html::a($views, ['feedback/view-detail/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
		},
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'view_ip',
		'value' => $model->view_ip,
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
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>