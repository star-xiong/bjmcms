/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	//config.uiColor = '#AADC6E';
	config.language = 'zh-cn';	/*将编辑器的语言设置为中文*/
	config.skin = 'moono-lisa';		//moono kama moono-lisa
	config.image_previewText = ' ';	/*去掉图片预览框的文字*/ 	
	config.filebrowserImageUploadUrl= "/admin/upload/ckimage.html";	/*开启工具栏“图像”中文件上传功能，后面的url为图片上传要指向的的action或servlet*/
	config.toolbar = "Full";
	config.allowedContent=true;
	config.protectedSource.push(/<i[^>]*><\/i>/g);
	config.protectedSource.push(/<a[^>]*><\/a>/g);
	/* 	config.toolbarGroups = [
			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
			{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
			{ name: 'links' },
			{ name: 'insert' },
			{ name: 'forms' },
			{ name: 'tools' },
			{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
			{ name: 'others' },
			'/',
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
			{ name: 'styles' },
			{ name: 'colors' },
			{ name: 'about' }
		]; */
};
