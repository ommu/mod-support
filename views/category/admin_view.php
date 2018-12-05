<?php
/**
 * Support Contact Categories (support-contact-category)
 * @var $this yii\web\View
 * @var $this app\modules\support\controllers\CategoryController
 * @var $model app\modules\support\models\SupportContactCategory
 *
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 11:08 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\menu\MenuContent;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Support Contact Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['index']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->cat_id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->cat_id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode($this->title); ?></h2>
			<?php if($this->params['menu']['content']):
			echo MenuContent::widget(['items' => $this->params['menu']['content']]);
			endif;?>
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
					'cat_id',
					'publish',
					'name',
					'cat_icon',
					[
						'attribute' => 'creation_date',
						'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
					],
					[
						'attribute' => 'creation_search',
						'value' => $model->creation_id ? $model->creation->displayname : '-',
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
					'slug',
				],
			]) ?>
		</div>
	</div>
</div>