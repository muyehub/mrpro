<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
            'cache_path'    => __DIR__ . '/../caches/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

		//目录
		'menu'	=>	[
			'youtube'		=>	'YouTube',
			'index'			=>	'仪表盘',
			'ui'			=>	'UI界面',
			'form'			=>	'表单',
			'chart'			=>	'图标',
			'typography'	=>	'排版',
			'gallery'		=>	'画廊',
			'table'			=>	'表格',
			'calendar'		=>	'日历',
			'grid'			=>	'网格',
			'file-manager'	=>	'文件管理',
			'tour'			=>	'游记',
			'icon'			=>	'图标',
			'error'			=>	'错误页面',
			'login'			=>	'登录页面',
		],
    ]
];
