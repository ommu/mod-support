<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\SupportFeedbacks
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 September 2017, 13:55 WIB
 * @modified date 27 January 2019, 09:55 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
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
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
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
$subject_id = $form->field($model, 'subject_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput();
echo $form->field($model, 'subjectName', ['template' => '{label}{beginWrapper}{input}'.$subject_id.'{error}{hint}{endWrapper}'])
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
	->label($model->getAttributeLabel('subjectName')); ?>

<?php echo $form->field($model, 'message')
	->textarea(['rows'=>6, 'cols'=>50])
	->label($model->getAttributeLabel('message')); ?>

<?php if(!$model->isNewRecord) {
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish'));
} ?>

<div class="ln_solid"></div>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>