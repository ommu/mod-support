<?php
/**
 * Support Feedback Replies (support-feedback-reply)
 * @var $this app\components\View
 * @var $this app\modules\support\controllers\ReplyController
 * @var $model app\modules\support\models\search\SupportFeedbackReply
 * @var $form app\components\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 14:16 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<div class="search-form">
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
		<?= $form->field($model, 'reply_id') ?>

		<?= $form->field($model, 'publish') ?>

		<?= $form->field($model, 'feedback_id') ?>

		<?= $form->field($model, 'reply_message') ?>

		<?= $form->field($model, 'creation_date') ?>

		<?= $form->field($model, 'creation_id') ?>

		<?= $form->field($model, 'modified_date') ?>

		<?= $form->field($model, 'modified_id') ?>

		<?= $form->field($model, 'updated_date') ?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
		</div>
	<?php ActiveForm::end(); ?>
</div>
