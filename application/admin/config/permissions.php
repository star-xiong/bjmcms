<?php
return [
	'table' => [
		'permission'		   => 'permissions',
		'role'	               => 'roles',
		'user_has_roles'       => 'user_has_roles',
		'role_has_permissions' => 'role_has_permissions',
	],

	'model' => [
		'permission' => think\permissions\model\Permissions::class,
		'role'		 => think\permissions\model\Roles::class,
		// Must set User Model Class
		'user'		 => app\admin\model\UserModel::class,
	],

	// Login User Session Key
	'user' => 'bjmcmsAdmin',
	'token' => 'remember_token',
	'key_secret' => 'r3QhnyyurlHRLLnTD1bSe8sBk5JmDcOm',
];