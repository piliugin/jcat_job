<?php
# подключение хелперов
require dirname(__FILE__).'/../helpers/functions.php';

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'JCat Console Application',

    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=jcat',
            'emulatePrepare' => true,
            'username' => 'jcat',
            'password' => 'diquwhoj',
            'charset' => 'utf8',
            'enableParamLogging' => true,
            'enableProfiling' => true
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);