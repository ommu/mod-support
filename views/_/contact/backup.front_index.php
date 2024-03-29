<?php
/**
 * Support Mails (support-mails)
 * @var $this ContactController
 * @var $model SupportMails
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 *
 */
 
	$this->breadcrumbs=array(
		'Support Mails'=>array('manage'),
		'Create',
	);
	$point = explode(',', $maps->value);
	$latitude = $point[0];
	$longitude = $point[1];
	$icons = $this->module->assetsUrl.'/images/map_marker.png';

	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile($this->module->assetsUrl.'/front_contact.css');
	$cs->registerScriptFile($this->module->assetsUrl.'/plugin/jquery.googlemaps1.01.js', CClientScript::POS_END);
$js=<<<EOP
	//Map Settings
	$('#map-canvas').googleMaps({
		scroll: false,
		latitude: '$latitude',
		longitude: '$longitude',
		depth: 15,
		markers: {
			latitude: '$latitude',
			longitude: '$longitude',
			icon: {
				image: '$icons',
				iconSize: '55, 61',
			} 				
		}
	});
EOP;
	$cs->registerScript('maps', $js, CClientScript::POS_END);
?>

<div class="content">
	<?php //begin.Maps Location ?>
	<div class="maps" id="map-canvas"></div>
	<?php //end.Maps Location ?>

	<?php //begin.Contact Form ?>
	<h3 class="title-line"><span><?php echo Yii::t('phrase', 'Hallo');?></span></h3>
	<?php echo Yii::t('phrase', 'Contact our team for any sales, support or general questions, or just say Hi!');?>

	<?php if(Yii::app()->user->hasFlash('success') || Yii::app()->getRequest()->getParam('name')) {
		echo '<div class="notifier success"><strong>'.Yii::t('phrase', 'Hallo').', '.Yii::app()->getRequest()->getParam('name').'</strong><br/>'.Yii::t('phrase', 'Your message was sent. Thank you!').'</div>';

	} else { ?>
		<div class="form">
			<?php $form=$this->beginWidget('application.libraries.yii-traits.system.OActiveForm', array(
				'id'=>'support-contacts-form',
				'enableAjaxValidation'=>true,
			)); ?>

			<?php //begin.Messages ?>
			<div id="ajax-message">
				<?php echo $form->errorSummary($model); ?>
			</div>
			<?php //begin.Messages ?>

			<fieldset>

				<div class="form-group row">
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php 
						$model->displayname = $user->displayname;
						echo $form->textField($model,'displayname', array('maxlength'=>32)); ?>
						<?php echo $form->error($model,'displayname'); ?>
					</div>
					<?php echo $form->labelEx($model,'displayname'); ?>
				</div>

				<div class="form-group row">
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php 
						$model->email = $user->email;
						echo $form->textField($model,'email', array('maxlength'=>32)); ?>
						<?php echo $form->error($model,'email'); ?>
					</div>
					<?php echo $form->labelEx($model,'email'); ?>
				</div>

				<div class="form-group row">
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php echo $form->textField($model,'subject', array('maxlength'=>64)); ?>
						<?php echo $form->error($model,'subject'); ?>
					</div>
					<?php echo $form->labelEx($model,'subject'); ?>
				</div>

				<div class="clearfix message">
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php echo $form->textArea($model,'message', array('rows'=>6, 'cols'=>50)); ?>
						<?php echo $form->error($model,'message'); ?>
					</div>
				</div>

				<div class="form-group row submit">
					<label class="col-form-label col-lg-3 col-md-3 col-sm-12">&nbsp;</label>
					<div class="col-lg-6 col-md-9 col-sm-12">
						<?php echo CHtml::submitButton(Yii::t('phrase', 'Send Message'), array('onclick' => 'setEnableSave()')); ?>
					</div>
				</div>

			</fieldset>
			<?php $this->endWidget(); ?>
		</div>
	<?php } ?>
	<?php //end.Contact Form ?>

</div>

<?php //begin.Sidebar ?>
<div class="sidebar">
	<?php //begin.Office Information ?>
	<?php $this->widget('FrontOfficeInfo'); ?>
</div>
<?php //end.Sidebar ?>


