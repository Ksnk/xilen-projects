<!--  ////////////////// плагин Новости /////////////////////// -->

<!--begin:news_b-->
<div id="news_b"><div class="cc1"><div  class="cc2 size11">
<div >	
<!--begin:news-->
<div class="newsbox"><div class="c1"><div  class="c2">
<div class="date"><b>{date>>rusDM}</b> {date>>rusY}</div>
<div class="img_gallery" data-time="{secpp}">
<!--begin:img-->
<div style="padding:0 0 5px 20px;">
	{pict}
</div>
<!--end:img--></div>
<div style="font-weight:bold;margin:5px 10px 5px 0;"><a href="{::curl:do:id}do=newslist&id={id}">{title}</a></div>
<div style="margin:5px 10px 5px 0;">{text}</div>
</div></div><a class="newslink" href="{::curl:do:id}do=newslist&id={id}"></a></div>
<!--end:news-->
</div>
<div style="margin:30px 0 10px 38px;">
<span class="size12 ">Архив 
<!--begin:news::years-->
<a href="{::curl:do:id}do=newslist&year={year}">{year}</a> г. {last|/}
<!--end:news::years--></span></div>
</div></div></div>
<!--end:news_b-->

<!--begin:newslist-->

<div class="para tahoma ctext size12" style="margin-top:15px;">
<!--begin:news-->
<div style="clear:both;padding-bottom:30px;">
<b>{date>>rusDM}</b> {date>>rusY}<br><br>
<b>{title}</b><br>
<div class="float_{align|left} img_gallery" data-time="{secpp}">
<!--begin:img-->
<div  style="margin-top:15px;">
	{pict}
</div>
<!--end:img-->
</div>
<p>{text}</p></div>
<!--end:news-->
{pages}
</div>
<!--end:newslist-->