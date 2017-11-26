<?php
/**
 * Support Mails (support-mails)
 * @var $this ContactController
 * @var $model SupportMails
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @link https://github.com/ommu/ommu-support
 * @contact (+62)856-299-4114
 *
 */
 
	$this->breadcrumbs=array(
		'Support Mails'=>array('manage'),
		'Create',
	);
	
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?sensor=false', CClientScript::POS_END);
	$cs->registerScriptFile($this->module->assetsUrl.'/plugin/custom.js', CClientScript::POS_END);
$js = <<<EOP
	initialize();
EOP;
	$ukey = md5(uniqid(mt_rand(), true));
	$cs->registerScript($ukey, $js);
?>

<?php //begin.Maps ?>
<div id="maps"></div>

<?php //begin.Address, Contact and Social Media ?>
<div class="address-social">
	<?php //begin.Address ?>
	<div class="address">
		<strong><?php echo $model->office_name != '' ? $model->office_name : OmmuSettings::getInfo('site_title');?></strong><br/>
		<?php echo $model->office_place.'.<br/>'.$model->office_village.', '.$model->office_district.',<br/>'.$model->view_meta->city.', '.$model->view_meta->province.', '.$model->view_meta->country.',<br/>'.$model->office_zipcode?>
	</div>
	
	<?php //begin.Contact ?>
	<?php if($model->office_phone != '' || $model->office_fax != '' || $model->office_hotline != '' || $model->office_email != '') {?>
	<div class="contact">
		<strong>&nbsp;</strong><br/>
		<?php echo $model->office_phone != '' ? $model->getAttributeLabel('office_phone').': '.$model->office_phone.'<br/>' : ''?>
		<?php echo $model->office_fax != '' ? $model->getAttributeLabel('office_fax').': '.$model->office_fax.'<br/>' : ''?>
		<?php echo $model->office_hotline != '' ? $model->getAttributeLabel('office_hotline').': '.$model->office_hotline.'<br/>' : ''?>
		<?php echo $model->office_email != '' ? $model->getAttributeLabel('office_email').': <a off_address="" href="mailto:'.$model->office_email.'" title="'.$model->office_email.'">'.$model->office_email.'</a>' : ''?>		
	</div>
	<?php }?>
	
	<?php //begin.Social Media ?>
	<?php if($contact != null) {?>
	<div class="social-media">
		<ul class="clearfix">
			<?php foreach($contact as $key => $val) {
				if($val->cat->icons == '') {
					$images = Yii::app()->request->baseUrl.'/public/support/default.png';
				} else {
					$images = Yii::app()->request->baseUrl.'/public/support/'.$val->cat->icons;
				}
				if($val->cat_id == 3) {
					$url = 'ymsgr:sendim?'.$val->value;
					$target = '';
				} else {
					$url = $val->value;
					$target = 'target="_blank"';
				}
				echo '<li><a off_address="" href="'.$url.'" title="'.$val->cat->title->message.'" '.$target.'><img src="'.$images.'" alt="'.$val->cat->title->message.'"></a></li>';
			}?>
		</ul>
	</div>
	<?php }?>
</div>