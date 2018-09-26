<?php
/**
 * Support Mail Settings (support-mail-setting)
 * @var $this MailsettingController
 * @var $model SupportMailSetting
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 21 March 2018, 08:48 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

	$this->breadcrumbs=array(
		'Support Mail Settings'=>array('manage'),
		Yii::t('phrase', 'Update'),
	);
?>

<div class="form" name="post-on">
	<?php echo $this->renderPartial('/o/mail_setting/_form', array(
		'model'=>$model,
	)); ?>
</div>
