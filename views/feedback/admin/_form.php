<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\SupportFeedbacks
 * @var $form app\components\ActiveForm
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
use app\components\ActiveForm;
use ommu\support\models\SupportFeedbackSubject;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
?>

<div class="support-feedbacks-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php 
// $subject = SupportFeedbackSubject::getSubject();
// echo $form->field($model, 'subject_id')
// 	->dropDownList($subject, ['prompt'=>''])
// 	->label($model->getAttributeLabel('subject_id')); ?>

<?php echo $form->field($model, 'email')
	->textInput(['type'=>'email'])
	->label($model->getAttributeLabel('email')); ?>

<?php echo $form->field($model, 'displayname')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('displayname')); ?>

<?php echo $form->field($model, 'phone')
	->textInput(['type'=>'number', 'maxlength'=>true])
	->label($model->getAttributeLabel('phone')); ?>

<?php
$subject_id = $form->field($model, 'subject_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput()->label(false);
echo $form->field($model, 'subjectName', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}'.$subject_id.'{error}</div>'])
	// ->textInput(['maxlength'=>true])
	->widget(AutoComplete::className(), [
		'options' => [
			'data-toggle' => 'tooltip', 'data-placement' => 'top',
			'class' => 'ui-autocomplete-input form-control'
		],
		'clientOptions' => [
			'source' => Url::to(['feedback/subject/suggest']),
			'minLength' => 2,
			'select' => new JsExpression("function(event, ui) {
				\$('.field-subjectname #subject_id').val(ui.item.id);
				\$('.field-subjectname #subjectname').val(ui.item.label);
				return false;
			}"),
		]
	])
	->label($model->getAttributeLabel('subjectName'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'message')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('message')); ?>

<?php if(!$model->isNewRecord) {
echo $form->field($model, 'publish')
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'));
} ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

</div>