<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this ViewController
 * @var $model SupportFeedbackView
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 11 May 2017, 23:13 WIB
 * @modified date 21 March 2018, 08:46 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$cs = Yii::app()->getClientScript();
$js=<<<EOP
	$('form[name="gridoption"] :checkbox').click(function(){
		var url = $('form[name="gridoption"]').attr('action');
		$.ajax({
			url: url,
			data: $('form[name="gridoption"] :checked').serialize(),
			success: function(response) {
				$.fn.yiiGridView.update('support-feedback-view-grid', {
					data: $('form[name="gridoption"]').serialize()
				});
				return false;
			}
		});
	});
EOP;
	$cs->registerScript('grid-option', $js, CClientScript::POS_END);
?>

<?php echo CHtml::beginForm(Yii::app()->createUrl($this->route), 'get', array(
	'name' => 'gridoption',
));
$columns   = array();
$exception = array('_option','_no','id');
foreach($model->templateColumns as $key => $val) {
	if(!in_array($key, $exception))
		$columns[$key] = $key;
}
?>
<ul>
	<?php foreach($columns as $key => $val): ?>
	<li>
		<?php echo CHtml::checkBox('GridColumn['.$val.']', in_array($key, $gridColumns) ? true : false); ?>
		<?php echo CHtml::label($model->getAttributeLabel($val), 'GridColumn_'.$val); ?>
	</li>
	<?php endforeach; ?>
</ul>
<?php echo CHtml::endForm(); ?>