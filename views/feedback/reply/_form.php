<?php
/**
 * Support Feedback Replies (support-feedback-reply)
 * @var $this app\components\View
 * @var $this app\modules\support\controllers\feedback\ReplyController
 * @var $model app\modules\support\models\SupportFeedbackReply
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
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;
use app\modules\support\models\SupportFeedbacks;

$redactorOptions = [
	'imageManagerJson' => ['/redactor/upload/image-json'],
	'imageUpload'	  => ['/redactor/upload/image'],
	'fileUpload'	   => ['/redactor/upload/file'],
	'plugins'		  => ['clips', 'fontcolor','imagemanager']
];
?>

<?php $form = ActiveForm::begin(); ?>

<!-- <?php echo $form->field($model, 'feedback_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('feedback_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?> -->

<?php 
	$data = ArrayHelper::map(SupportFeedbacks::find()->all(), 'feedback_id', 'displayname');
	echo $form
	->field($model, 'feedback_id', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->dropDownList($data, ['prompt' => 'Pilih Feedback'])
	->label($model->getAttributeLabel('feedback_id'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); 
?>

<?php echo $form->field($model, 'reply_message', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12">{input}{error}</div>'])
	->textarea(['rows'=>6, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('reply_message'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<?php echo $form->field($model, 'publish', ['template' => '{label}<div class="col-md-6 col-sm-9 col-xs-12 checkbox">{input}{error}</div>'])
	->checkbox(['label'=>''])
	->label($model->getAttributeLabel('publish'), ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']); ?>

<div class="ln_solid"></div>
<div class="form-group">
	<div class="col-md-6 col-sm-9 col-xs-12 col-sm-offset-3">
		<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
	</div>
</div>

<?php ActiveForm::end(); ?>