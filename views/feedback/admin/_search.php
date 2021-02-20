<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\search\SupportFeedbacks
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 20 September 2017, 13:55 WIB
 * @modified date 27 January 2019, 09:55 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ommu\support\models\SupportFeedbackSubject;
?>

<div class="support-feedbacks-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php $subject = SupportFeedbackSubject::getSubject();
		echo $form->field($model, 'subject_id')
			->dropDownList($subject, ['prompt' => '']);?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php echo $form->field($model, 'email');?>

		<?php echo $form->field($model, 'displayname');?>

		<?php echo $form->field($model, 'phone');?>

		<?php echo $form->field($model, 'message');?>

		<?php echo $form->field($model, 'reply_message');?>

		<?php echo $form->field($model, 'replied_date')
			->input('date');?>

		<?php echo $form->field($model, 'repliedDisplayname');?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<?php echo $form->field($model, 'updated_date')
			->input('date');?>

		<?php echo $form->field($model, 'publish')
			->dropDownList($model->filterYesNo(), ['prompt' => '']);?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>