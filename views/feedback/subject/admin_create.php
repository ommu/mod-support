<?php
/**
 * Support Feedback Subjects (support-feedback-subject)
 * @var $this app\components\View
 * @var $this ommu\support\controllers\feedback\SubjectController
 * @var $model ommu\support\models\SupportFeedbackSubject
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 27 January 2019, 18:54 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedback Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="support-feedback-subject-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
