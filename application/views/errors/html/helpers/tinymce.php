<script type="text/javascript" src="<?=base_url();?>js/tiny_mce/tiny_mce.js"></script>
<!-- подключаем плагин выбора картинки для загрузки-->
<script type="text/javascript" src="<?=base_url();?>js/tiny_mce/plugins/upload/upload_init.php"></script>

<script  type="text/javascript" src="<?=base_url();?>js/tiny_mce/fullajax.js"></script>

<script type="text/javascript">
   SRAX.onReady(function(){
     tinyMCEPreInit = { base : "<?=base_url();?>js/tiny_mce", suffix : ""};
     document.documentElement.doScroll = false;
   })
 </script>
 <script type="text/javascript">
	tinyMCE.init({
		// General options
		//mode : "textareas",
        mode : "exact",
       elements : "simp",
		theme : "simple",
		language : "ru",
        content_css : "themes/simple/skins/default/content.css"
		});
</script>

 <script type="text/javascript">
	tinyMCE.init({
		// General options
		//mode : 'textareas',
		//если нужно подключить не все textarea, а только помеченные id (elm1 и elm2)
		mode : "exact",
       elements : "full",
       
		theme : "advanced",
	    file_browser_callback : 'upload',
	    language : "ru",
	    skin : "default",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",


//полный набор кнопок
/*theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
11.
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
12.
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
13.
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",*/




		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,code,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
		//theme_advanced_buttons4 : "visualchars,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
        relative_urls : true,
        //при нажатии enter вставляет <br> вместо <p>
		//force_br_newlines : true, 
		
		//отключаем автоматическую вставку <p> при нажатии enter в Mozilla/Firefox
		//force_p_newlines : false,
		
		verify_html : false,
	    //cleanup : false,
        //remove_script_host : true,

		// Example content CSS (should be your site CSS)
		content_css : "themes/advanced/skins/default/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
        
	});
</script>