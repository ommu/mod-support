<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this yii\web\View
 * @var $this app\modules\support\controllers\AdminController
 * @var $model app\modules\support\models\search\SupportFeedbacks
 * @var $form yii\widgets\ActiveForm
 *
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:11 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?= $form->field($model, 'feedback_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'email') ?>

		<?= $form->field($model, 'displayname') ?>

		<?= $form->field($model, 'phone') ?>

		<?= $form->field($model, 'subject') ?>

		<?= $form->field($model, 'message') ?>

		<?= $form->field($model, 'creation_date') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'updated_date') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
