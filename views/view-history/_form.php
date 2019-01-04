<?php
/**
 * Support Feedback View Histories (support-feedback-view-history)
 * @var $this yii\web\View
 * @var $this app\modules\support\controllers\ViewHistoryController
 * @var $model app\modules\support\models\SupportFeedbackViewHistory
 * @var $form yii\widgets\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Arifin Avicena <avicenaarifin@gmail.com>
 * @created date 25 September 2017, 14:32 WIB
 * @contact (+62)857-2971-9487
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
?>

<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'form-horizontal form-label-left',
		//'enctype' => 'multipart/form-data',
	],
]); ?>

<?php echo $form->field($model, 'view_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('view_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'view_date', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->passwordInput()
	->widget(DatePicker::classname(), ['dateFormat' => Yii::$app->formatter->dateFormat, 'options' => ['class' => 'form-control']])
	->label($model->getAttributeLabel('view_date'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'view_ip', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('view_ip'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-md-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>