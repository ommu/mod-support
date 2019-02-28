<?php
/**
 * Support Contacts (support-contacts)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\ContactsController
 * @var $model ommu\support\models\SupportContacts
 * @var $form app\components\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 12:59 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;
use ommu\support\models\SupportContactCategory;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	  => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		  => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php 
	$data = ArrayHelper::map(SupportContactCategory::find()->all(), 'cat_id', 'name');
	echo $form
	->field($model, 'cat_id')
	->dropDownList($data, ['prompt' => 'Pilih Cat'])
	->label($model->getAttributeLabel('cat_id')); 
?>

<?php echo $form->field($model, 'contact_name')
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('contact_name')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-xs-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>