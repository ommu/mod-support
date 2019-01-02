<?php
return [
	'id' => 'support',
	'class' => ommu\support\Module::className(),
	'urlManagerRules' => [
		'class'      => 'yii\rest\UrlRule', 
		'controller' => [
			'support/v1/feedbacks',
		],
		'pluralize'  => false,
	],
];