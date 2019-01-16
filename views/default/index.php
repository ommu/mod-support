<?php
/**
 * @var $this app\components\View
 * @var $this app\modules\support\controllers\DefaultController
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:05 WIB
 * @contact (+62)856-299-4114
 *
 */
 
use yii\helpers\Html;
?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2><?php echo Html::encode($this->title); ?><small><?php echo $this->context->action->uniqueId ?></small></h2>
			<ul class="nav navbar-right panel_toolbox">
				<li><a href="#" title="<?php echo 'Toggle';?>" class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
				<li class="dropdown">
					<a href="#" title="<?php echo 'Options';?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" title="<?php echo 'Grid Options';?>"><?php echo 'Grid Options';?></a></li>
					</ul>
				</li>
				<li><a href="#" title="<?php echo 'Close';?>" class="close-link"><i class="fa fa-close"></i></a></li>
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<p>
				This is the view content for action "<?php echo $this->context->action->id ?>".
				The action belongs to the controller "<?php echo get_class($this->context) ?>"
				in the "<?php echo $this->context->module->id ?>" module.
			</p>
			<p>
				You may customize this page by editing the following file:<br>
				<code><?php echo __FILE__ ?></code>
			</p>
		</div>
	</div>
</div>

<div class="support-default-index"></div>