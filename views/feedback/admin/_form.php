<?php
/**
 * Support Feedbacks (support-feedbacks)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\SupportFeedbacks
 * @var $form app\components\widgets\ActiveForm
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
use yii\helpers\Url;
use app\components\widgets\ActiveForm;
use ommu\support\models\SupportFeedbackSubject;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="support-feedbacks-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'displayname')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('displayname')); ?>

<?php echo $form->field($model, 'email')
	->textInput(['type' => 'email'])
	->label($model->getAttributeLabel('email')); ?>

<?php echo $form->field($model, 'phone')
	->textInput(['type' => 'number', 'maxlength' => true])
	->label($model->getAttributeLabel('phone')); ?>

<?php echo $form->field($model, 'subject_id', ['template' => '{label}{beginWrapper}{input}{error}{hint}{endWrapper}'])
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a subject..'),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a subject..')], SupportFeedbackSubject::getSubject()),
		'url' => Url::to(['feedback/subject/suggest']),
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'id',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('subject_id')); ?>

<?php echo $form->field($model, 'message')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('message')); ?>

<?php 
if (!$model->isNewRecord) {
    echo $form->field($model, 'publish')
        ->checkbox()
        ->label($model->getAttributeLabel('publish'));
} ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>