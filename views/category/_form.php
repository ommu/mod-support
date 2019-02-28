<?php
/**
 * Support Contact Categories (support-contact-category)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\CategoryController
 * @var $model ommu\support\models\SupportContactCategory
 * @var $form app\components\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 11:08 WIB
 * @contact (+62)856-299-4114
 *
 */

use yii\helpers\Html;
use app\components\ActiveForm;
?>

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
]); ?>

<?php echo $form->field($model, 'name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('name')); ?>

<?php echo $form->field($model, 'cat_icon')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('cat_icon')); ?>

<?php echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<div class="ln_solid"></div>
<div class="form-group row">
	<div class="col-md-6 col-sm-9 col-12 offset-sm-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>