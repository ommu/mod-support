<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this yii\web\View
 * @var $this app\modules\support\controllers\AdminController
 * @var $model app\modules\support\models\SupportFeedbacks
 *
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:11 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Support Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->feedback_id], ['class' => 'btn btn-primary']) ?>
	<?php echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->feedback_id], [
		'class' => 'btn btn-danger',
		'data' => [
			'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
			'method' => 'post',
		],
	]) ?>
	
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode($this->title); ?></h2>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo Yii::t('app', 'Toggle');?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li><a href="#" title="<?php echo Yii::t('app', 'Close');?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<?php echo DetailView::widget([
				'model' => $model,
				'options' => [
					'class'=>'table table-striped detail-view',
				],
				'attributes' => [
					'feedback_id',
					'publish',
					[
						'attribute' => 'user_search',
						'value' => $model->user_id ? $model->user->displayname : '-',
					],
					'email:email',
					'displayname',
					'phone',
					'subject',
					'message:ntext',
					[
						'attribute' => 'creation_date',
						'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
					],
					[
						'attribute' => 'modified_date',
						'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
					],
					[
						'attribute' => 'modified_search',
						'value' => $model->modified_id ? $model->modified->displayname : '-',
					],
					[
						'attribute' => 'updated_date',
						'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
					],
				],
			]) ?>
		</div>
	</div>
</div>