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
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 27 January 2019, 16:49 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/admin/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedback'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="support-feedbacks-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
