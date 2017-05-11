<?php
/**
 * Support Feedback Views (support-feedback-view)
 * @var $this ViewsController
 * @var $data SupportFeedbackView
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 11 May 2017, 23:13 WIB
 * @link http://opensource.ommu.co
 * @contact (+62)856-299-4114
 *
 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('view_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->view_id), array('view', 'id'=>$data->view_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('feedback_id')); ?>:</b>
	<?php echo CHtml::encode($data->feedback_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_date')); ?>:</b>
	<?php echo CHtml::encode($data->creation_date); ?>
	<br />


</div>