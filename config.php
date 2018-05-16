<?php
return [
	'id' => 'support',
	'class' => app\modules\support\Module::className(),
	'urlManagerRules' => [
		'class'      => 'yii\rest\UrlRule', 
		'controller' => [
			'support/v1/feedbacks',
		],
		'pluralize'  => false,
	],    
];