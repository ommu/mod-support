<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this app\components\View
 * @var $this app\modules\support\controllers\ViewController
 * @var $model app\modules\support\models\search\SupportFeedbackView
 * @var $form app\components\ActiveForm
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Arifin Avicena <avicenaarifin@gmail.com>
 * @created date 25 September 2017, 14:11 WIB
 * @contact (+62)857-2971-9487
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$js = <<<JS
	$('form[name="gridoption"] :checkbox').click(function() {
		var url = $('form[name="gridoption"]').attr('action');
		var data = $('form[name="gridoption"] :checked').serialize();
		$.ajax({
			url: url,
			data: data,
			success: function(response) {
				//$("#w0").yiiGridView("applyFilter");
				//$.pjax({container: '#w0'});
				return false;
			}
		});
	});
JS;
	$this->registerJs($js, \app\components\View::POS_READY);
?>

<div class="grid-form">
	<?php echo Html::beginForm(Url::to(['/'.$route]), 'get', ['name' => 'gridoption']);
		$columns = [];

		foreach($model->templateColumns as $key => $column) {
			if($key == '_no')
				continue;
			
			$columns[$key] = $key;
		}
	?>
		<ul>
			<?php foreach($columns as $key => $val): ?> 
			<li>
				<?php echo Html::checkBox(sprintf("GridColumn[%s]", $key), in_array($key, $gridColumns) ? true : false, ['id'=>'GridColumn_'.$key]); ?>
				<?php echo Html::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="clear"></div>
	<?php echo Html::endForm(); ?>
</div>