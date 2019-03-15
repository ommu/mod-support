<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\SupportFeedbacks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 September 2017, 13:55 WIB
 * @modified date 27 January 2019, 09:55 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->displayname;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->feedback_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->feedback_id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->feedback_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="support-feedbacks-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'feedback_id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'userDisplayname',
			'value' => isset($model->user) ? $model->user->displayname : '-',
		],
		'email:email',
		'displayname',
		'phone',
		[
			'attribute' => 'subject_id',
			'value' => isset($model->subject) ? $model->subject->title->message : '-',
		],
		[
			'attribute' => 'message',
			'value' => $model->message ? $model->message : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'reply_message',
			'value' => $model->reply_message ? $model->reply_message : '-',
		],
		[
			'attribute' => 'repliedDisplayname',
			'value' => isset($model->replied) ? $model->replied->displayname : '-',
		],
		[
			'attribute' => 'replied_date',
			'value' => Yii::$app->formatter->asDatetime($model->replied_date, 'medium'),
		],
		[
			'attribute' => 'modified_date',
			'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		],
		[
			'attribute' => 'modifiedDisplayname',
			'value' => isset($model->modified) ? $model->modified->displayname : '-',
		],
		[
			'attribute' => 'updated_date',
			'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		],
		[
			'attribute' => 'users',
			'value' => function ($model) {
				$users = $model->getUsers(true);
				return Html::a($users, ['feedback/user/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$users])]);
			},
			'value' => ,
			'format' => 'html',
		],
		[
			'attribute' => 'views',
			'value' => function ($model) {
				$views = $model->getViews(true);
				return Html::a($views, ['feedback/view/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
			},
			'format' => 'html',
		],
	],
]) ?>

</div>