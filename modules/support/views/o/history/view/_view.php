<?php
/**
 * Support Feedback View Histories (support-feedback-view-history)
 * @var $this ViewController
 * @var $data SupportFeedbackViewHistory
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 23 August 2017, 17:21 WIB
 * @link https://github.com/ommu/mod-support
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view_id')); ?>:</b>
	<?php echo CHtml::encode($data->view->column_name_relation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view_date')); ?>:</b>
	<?php echo CHtml::encode(Utility::dateFormat($data->view_date, true)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view_ip')); ?>:</b>
	<?php echo CHtml::encode($data->view_ip); ?>
	<br />


</div>