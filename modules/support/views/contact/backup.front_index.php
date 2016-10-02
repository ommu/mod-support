<?php
/**
 * Support Mails (support-mails)
 * @var $this ContactController
 * @var $model SupportMails
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Support
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Support Mails'=>array('manage'),
		'Create',
	);
	$point = explode(',', $maps->value);
	$latitude = $point[0];
	$longitude = $point[1];
	$icons = Yii::app()->request->baseUrl.'/externals/support/images/map_marker.png';

	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile(Yii::app()->request->baseUrl.'/externals/support/front_contact.css');
	$cs->registerScriptFile(Yii::app()->request->baseUrl.'/externals/support/plugin/jquery.googlemaps1.01.js', CClientScript::POS_END);
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

	<?php if(Yii::app()->user->hasFlash('success') || isset($_GET['name'])) {
		echo '<div class="notifier success"><strong>'.Yii::t('phrase', 'Hallo').', '.$_GET['name'].'</strong><br/>'.Yii::t('phrase', 'Your message was sent. Thank you!').'</div>';

	} else { ?>
		<div class="form">
			<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
				'id'=>'support-contacts-form',
				'enableAjaxValidation'=>true,
				//'htmlOptions' => array('enctype' => 'multipart/form-data')
			)); ?>

			<?php //begin.Messages ?>
			<div id="ajax-message">
				<?php echo $form->errorSummary($model); ?>
			</div>
			<?php //begin.Messages ?>

			<fieldset>

				<div class="clearfix">
					<div class="desc">
						<?php 
						$model->displayname = $user->displayname;
						echo $form->textField($model,'displayname',array('maxlength'=>32)); ?>
						<?php echo $form->error($model,'displayname'); ?>
					</div>
					<?php echo $form->labelEx($model,'displayname'); ?>
				</div>

				<div class="clearfix">
					<div class="desc">
						<?php 
						$model->email = $user->email;
						echo $form->textField($model,'email',array('maxlength'=>32)); ?>
						<?php echo $form->error($model,'email'); ?>
					</div>
					<?php echo $form->labelEx($model,'email'); ?>
				</div>

				<div class="clearfix">
					<div class="desc">
						<?php echo $form->textField($model,'subject',array('maxlength'=>64)); ?>
						<?php echo $form->error($model,'subject'); ?>
					</div>
					<?php echo $form->labelEx($model,'subject'); ?>
				</div>

				<div class="clearfix message">
					<div class="desc">
						<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50)); ?>
						<?php echo $form->error($model,'message'); ?>
					</div>
				</div>

				<div class="submit clearfix">
					<label>&nbsp;</label>
					<div class="desc">
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


