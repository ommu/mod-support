<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this app\modules\support\controllers\AdminController
 * @var $model app\modules\support\models\SupportFeedbacks
 * @var $form app\components\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:11 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Support Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->displayname, 'url' => ['view', 'id'=>$model->feedback_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<?php echo Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->feedback_id], ['class' => 'btn btn-primary']) ?>
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
			<?php echo $this->render('_form', [
				'model' => $model,
			]); ?>
		</div>
	</div>
</div>