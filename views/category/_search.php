<?php
/**
 * Support Contact Categories (support-contact-category)
 * @var $this yii\web\View
 * @var $this app\modules\support\controllers\CategoryController
 * @var $model app\modules\support\models\search\SupportContactCategory
 * @var $form yii\widgets\ActiveForm
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 11:08 WIB
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
		<?= $form->field($model, 'cat_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'name') ?>

		<?= $form->field($model, 'cat_icon') ?>

		<?= $form->field($model, 'creation_date') ?>

		<?= $form->field($model, 'creation_id') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'updated_date') ?>

		<?= $form->field($model, 'slug') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
