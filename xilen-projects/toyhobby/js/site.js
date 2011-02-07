$(function(){	
    var scroll= cookie('scroll');
    if(scroll){
    	window.scrollTo(0,scroll);
    	cookie('scroll',0);
    };
	$('.storepos').mousedown(function(){
	    cookie('scroll'
	    	,(window.scrollY) ? window.scrollY : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop
	    	,{ expires:10000})
	});
	$("#debug").ajaxError(function(event, request, settings){
		$(this).append("<li>Error requesting page " + settings.url + "<"+"/li>");
	});
	$(".ajaxform").each(function(){
		var form = this;
		$(this).find('a.submit').click(function(){
			// в jQuery дурная ашипка с сериализацией select'ов
			var ser=[];
			$(form).find('select').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.options[this.selectedIndex].value));
			}) 
			$(form).find('input').each(function(){
				ser.push(this.name.replace(/_\d*/,'')+'='+encodeURIComponent(this.value));
				if (this.name.match(/^item_?/))
					this.value='';
			})
			$(this).parent().css({position:'relative'});
			var xx=$('<div></div>').css({
				backgroundColor:'white',
				width:'70px',
				border:'1px solid gray',
				padding:'10px 20px',
				position:'absolute',
				top:'1.5em',
				left:0
			}).insertAfter($(this)).html('Товар<br>добавляется'); 
			$.post(
				this.href.replace('ajax=1','').replace(/(\/#?$|\/\?do=\w*)/,'/?do=add&ajax=1'),
				ser.join('&'),
				function(data){
					if(data.error) alert(data.error);
					if(data.debug) alert(data.debug);
					if(data.session){ // just session started
					  if(!cookie(data.session.name)) {
					    var reg=new RegExp('\&'+data.session.name+'=\\w*|'+data.session.name+'=\\w*\&','ig')
					  	document.location=
					  		document.location.href.replace(reg,'')+
					  		'&'+data.session.name+'='+data.session.value;
					  }
					}
					if(data.result) {
						for(a in data.result)
							$('#'+a).html(data.result[a]);
						xx.html('Товар<br>добавлен');	
					} else {	
						alert(data.data);
						xx.html('Ашипка :-( ');
					}
					setTimeout(function(){
						xx.remove();
						xx=null;
					},1000);
				},
				'json'
			)
	   		return false;	
	})});
function WindowO(e,s,w,h)
{
	var $x=$(e).attr('href');
//	if($x && ($x.length>2)) { return true;}
	if(s.match(/\/$/)) return false ;
	try{
	  var par="location=no,toolbar=no,resizable=yes";
	  if((w+100>window.screen.width)||(h+100>window.screen.height))
	  	par+=",scrollbars=yes"
	  if(w) par+=',width='+w;
 	  if(h) par+=',height='+h;
	  wind=open("","win",par);
	  wind.document.writeln('<html>'+
		'<head><title><'+'/title><style>body,html{width:100%;height:100%;padding:0;margin:0;}\n'+
		'body{overflow:auto;}\n</'+'style>\n<'+'script>function fitPic(){'+
	 	'iWidth = window.innerWidth||document.body.clientWidth;'+
	 	'iHeight = window.innerHeight||document.body.clientHeight;'+
		'iWidth = document.images[0].width - iWidth;'+
		'iHeight = document.images[0].height - iHeight;'+
		'if(iWidth && iHeight)window.resizeBy(iWidth,iHeight);self.focus();'+
		//'alert([window.screen.height,window.screen.width, iWidth,iHeight,document.images[0].width,window.innerWidth,document.body.clientWidth]);'+
		'};</'+'script>'+
		'<'+'/head><'+'body onload="fitPic()">'+
		'<img src="'+s+'"><button style="position:absolute;right:10px;bottom:10px;" '+
		'onclick="self.close();">close</button><'+'/body><'+'/html>');
	  wind.document.close();
	} catch(e) {
	  alert('Всплывающие окна заблокированы! Разрешите всплывающие окна для нормального функционирования.')
	}
	return false;
};window.WindowO=WindowO;
    $first=null;
	$('.galleryX a').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|gif)/)) {
		    this.__done=true;
		    $(this).click(function(){ 
				var t=$(this).parents('.galleryX').find('img')[0];
				t.src=this.href;
				return false
			})
			if(!$first){
				$first=true;
				$(this).click();
			}
			
		}			
	})
	$first=null;
	$('.gallery a, .cat_border a').removeAttr('onclick').each(function(){
		if(this.href && this.href.match(/\.(jpe?g|png|fig)/)) {
			if(!this.__done)
				if($.fn.colorbox)
					$(this).colorbox();
				else				
					$(this).click(function(){return WindowO(this,this.href)});
		} else if(this.href && this.href.match(/#$|uploaded\/$/)) {
			$x=$(this).html();
			if($x)
				$(this).parent().html($x);
		}
		$(this).find('img').bind('load',checkImg).each(function(){
			if(this.complete)
				checkImg(this);
		});
	});
	$('a.url_page').click(function(){
		if(this.href && !this.href.match(/javascript/i)){
			var self=this,parent=$(this).parents('.para')[0];
			function clickit(e){
				if(e && self) return false;
			    $(this).find('.back').toggleClass('hidden');
			  	$(this.container).toggle('normal');
				//    $('#debug').append("<li>click "+ "<"+"/li>");
				return false;
			}
			$.getJSON(this.href.replace('ajax=1','').replace(/(do=\w*)/,'$1&ajax=1'),function(data){
				if(data.error) alert(data.error);
				if(data.debug) alert(data.debug);
				if(data.data) {
				    self.container=$('<div class="ainfo"></div>').insertAfter($(parent)).hide().html(data.data)[0];
//				    $('#debug').append("<li>click "+ "<"+"/li>");
				    clickit.apply(self);
				    self=null;
				} 
			})
			this.href='javascript:;';
			$(this).click(clickit);
		}
   		return false;	
	})// init menu
menu('#searchbar',{
	show:function(){$(this).show('hormal')},
	hide:function(){$(this).hide('hormal')}
});
window.showsearchbar=function (){
	var x=$('#searchbar')[0];
	if(!x.shown)
		x.show_menu();
	return false;
};
})	
// поставить куку cookie.
function cookie(name,value,opt){
	if (typeof value != 'undefined') { // name and value given, set cookie
		opt = opt || {};
		if (value === null) {
			value = '';
			opt.expires = -1;
		}
		var expires = '';//expires:10
		if (opt.expires && (typeof (opt.expires) == 'number' || opt.expires.toUTCString)) {
			var date;
			if (typeof opt.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + Math.round(opt.expires * 24 * 60 * 60 * 1000));
			}
			else {
				date = opt.expires;
			}
			expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
		}
		document.cookie = name + '=' + encodeURIComponent(value) + expires +
			(opt.path ? '; path=' + opt.path : '') +
			(opt.domain ? '; domain=' + opt.domain : '') +
			(opt.secure ? '; secure' : '')
	}
	else { // only name given, get cookie
		if (document.cookie && document.cookie != '') {
			var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie);
			return cook && decodeURIComponent(cook[1]);
		}
		return null;
	}
};function checkImg(el,width,height){
    if(!el || !el.src) return ;
	var Img=new Image();
	Img.onload=function(){
		if(Img.width && Img.height) {
			if(!width) if(el.style.width) width=parseInt(el.style.width);
			if(!height) if(el.style.height) height=parseInt(el.style.height);
			// looks like loaded!
			var k=1; // 1 - вписываем 0 - расширяем по габаритам
			if(width) k=Math.max(k,Img.width/width); else width=Img.width;
			if(height) k=Math.max(k,Img.height/height); else height=Img.height;
			if(!k) k=1;
//		if (Img.width>width || Img.height>height){
			el.style.width=Math.round(Img.width/k)+'px';
			el.style.height=Math.round(Img.height/k)+'px';
//		}
		}
		Img=null;
	}
	Img.src=el.src;
}/**
 * Установка выпадающего меню
 */
function menu(_self,param){
    if(!param) param={};
    else if(typeof(param)=='function')
    	param={show:param};
    if(!(_self=$(_self)[0])) return;	
    	
	function checkMouse (e){
	     var el = e.target;
	     while (true){
			if (el == _self) {
				return true;
			} else if (el == document) {
				hide_menu();
				return false;
			} else {
				el = el.parentNode;
			}
		}
	};
	
	function show_menu(){
	  if(param.show) param.show.apply(_self);
	  else $(_self).show();
	  _self.shown=true;
	  $(document).bind('mousedown', checkMouse);
	  return false;
	};
	
	function hide_menu(){
	  $(document).unbind('mousedown', checkMouse);	
	  if(param.hide)
	  	param.hide.apply(_self);
	  else
	  	$(_self).hide();
	  _self.shown=false;
	  return false;
	};
	_self.show_menu=show_menu;
	_self.hide_menu=hide_menu;
	$(window).bind('unload', function(){_self=null});
};