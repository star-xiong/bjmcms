<?php
return [
	// 'table' => [
	// 	'permission'		   => 'permissions',
	// 	'role'	               => 'roles',
	// 	'user_has_roles'       => 'user_has_roles',
	// 	'role_has_permissions' => 'role_has_permissions',
	// ],

	'model' => [
		//'permission' => think\permissions\model\Permissions::class,
		//'role'		 => think\permissions\model\Roles::class,
		// Must set User Model Class
		'user'		 => app\common\model\MemberModel::class,
	],

	// Login User Session Key
	'user' => 'bjmcmsMember',
	'token' => 'remember_member_token',
	'key_secret' => 'dU2TFhwLqBsKtRycFl7YBsWParYJxAJh',
];