<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\common\model\ModelModel;
//use app\model\SiteModel;
// $model = new ModelModel();
// $model_list=$model->where('siteid',1)->select();
// $lang = get_current_lang();
// print_r($lang);
Route::pattern(['id' => '\d+']);
#后台面板
Route::get('admin_main', 'admin/Index/main')->name('admin.main');
# 登录
Route::rule('admin_login','admin/Login/login','GET|POST')->name('admin.login');

# 登出
Route::rule('admin_logout','admin/Login/logout')->name('admin.logout');
#用户中心
//Route::rule('ucenter/:id','home/User/index')->name('ucenter.index');

//用户中心
Route::rule('ucenter/index','ucenter/Member/index')->name('ucenter.index');
Route::rule('ucenter/edit','ucenter/Member/edit')->name('ucenter.edit');
Route::rule('ucenter/upload','ucenter/Member/upload','POST')->name('ucenter.upload');
Route::rule('ucenter/address','ucenter/Member/address','GET')->name('ucenter.address');
Route::rule('ucenter/orderlist','ucenter/Order/index','GET')->name('ucenter.orderlist');
Route::rule('ucenter/orderdetail/:id','ucenter/Order/detail','GET')->name('ucenter.orderdetail');
Route::rule('ucenter/ordercancel/:id','ucenter/Order/cancel','GET')->name('ucenter.ordercancel');
Route::rule('resetpasswd','ucenter/Member/resetpasswd','GET')->name('resetpasswd');

//收藏夹
Route::rule('myfavorite','ucenter/Member/myfavorite')->name('myfavorite');
Route::rule('favorite/:id','ucenter/Member/favorite','GET')->name('favorite');

//会员地址
Route::rule('address_add','ucenter/Address/save','POST')->name('address.save');
Route::rule('address_del/:id','ucenter/Address/delete','POST')->name('address.delete');
Route::rule('address_set/:id','ucenter/Address/setAddress','POST')->name('address.set');
Route::rule('address/:id','ucenter/Address/getAddress','GET')->name('address.get');


//PC前端首页
Route::rule('/','home/Index/index')->name('/');

//会员登录、注册、忘记密码、发短信、发邮件
Route::rule('register','home/User/register')->name('register');
Route::rule('login','home/User/login')->name('login');
Route::rule('pop_login','home/User/pop_login')->name('pop_login');
Route::rule('logout','home/User/logout')->name('logout');
Route::rule('password','home/User/password')->name('password');
Route::rule('sendsms','home/User/send')->name('sendsms');
Route::rule('sendemail','home/User/sendemail')->name('sendemail');
Route::rule('sendpwd','home/User/sendpwd')->name('sendpwd');

//商城
Route::rule('category/:id', 'shop/Goods/category')->name('category');
Route::rule('goods/:id', 'shop/Goods/detail')->name('goods');
Route::rule('catalog', 'shop/Goods/catalog')->name('catalog');
Route::rule('getstocks', 'shop/Goods/getstocks')->name('getstocks');
Route::rule('getgoodsattr', 'shop/Goods/getgoodsattr')->name('getgoodsattr');
//购物车
Route::rule('addcart','shop/Cart/add','POST')->name('addcart');
Route::rule('cart/update','shop/Cart/update','POST')->name('cart.update');
Route::rule('cart/delete/:id','shop/Cart/delete')->name('cart.delete');
Route::rule('cart/clear','shop/Cart/clear')->name('cart.clear');
Route::rule('flow/checkout','shop/Cart/checkout')->name('checkout');
Route::rule('flow/payinfo/:id','shop/Cart/payinfo')->name('payinfo');
Route::rule('flow','shop/Cart/flow')->name('flow');

Route::rule('addorder','shop/Order/add')->name('order.add');




Route::rule('search','home/Index/search')->name('search');
Route::rule('guestbook/save','home/GuestBook/save')->name('guestbook.save');
Route::rule('guestbook/upload','home/GuestBook/upload')->name('guestbook.upload');
Route::rule('guestbook/purchase/:id','home/GuestBook/purchase')->name('guestbook.purchase');
Route::rule('guestbook/sell/:id','home/GuestBook/sell')->name('guestbook.sell');

//Route::rule('list/:id','home/Index/list')->name('list');

Route::rule('articles/:id','home/Index/list')->name('articles');
Route::rule('page/:id','home/Index/list')->name('page');
Route::rule('guestbook/:id','home/Index/list')->name('guestbook');
Route::rule('images/:id','home/Index/list')->name('images');
Route::rule('cases/:id','home/Index/list')->name('cases');
Route::rule('downloads/:id','home/Index/list')->name('downloads');
Route::rule('videos/:id','home/Index/list')->name('videos');
Route::rule('reservation/:id','home/Index/list')->name('reservation');
Route::rule('jobs/:id','home/Index/list')->name('jobs');

Route::rule('show/:id','home/Index/show')->name('show');



//API
//获取地区
Route::rule('region','api/region/getRegions')->name('region');
//首页
Route::rule('api/home','api/home/index')->name('api.home.index');
Route::rule('api/goods/categories/:id','api/goods/getCategories')->name('api.goods.getcategories');
Route::rule('api/goodslist/:id','api/goods/goodsList')->name('api.goods.goodslist');
Route::rule('api/goods/detail/:id','api/goods/detail')->name('api.goods.detail');


//PC 手机页面
// Route::rule('m/list/:id','wap/Index/list')->name('m.list');

// Route::rule('m/news/:id','wap/Index/list')->name('m.news');
// Route::rule('m/articles/:id','wap/Index/list')->name('m.articles');
// Route::rule('m/cases/:id','wap/Index/list')->name('m.cases');
// Route::rule('m/products/:id','wap/Index/list')->name('m.products');
// Route::rule('m/downloads/:id','wap/Index/list')->name('m.downloads');
// Route::rule('m/page/:id','wap/Index/list')->name('m.page');
// Route::rule('m/images/:id','wap/Index/list')->name('m.images');


// Route::rule('m/show/:id','wap/Index/show')->name('m.show');
// Route::rule('m/category','wap/Index/category')->name('m.category');
// Route::rule('m','wap/Index/index')->name('m');