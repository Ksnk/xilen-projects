function fixPNG(element)
{
  //���� ������� IE ������ 5.5-6
  if (/MSIE (5\.5|6).+Win/.test(navigator.userAgent))
  {
    var src;

    if (element.tagName=='IMG') //���� ������� ������� �������� (��� IMG)
    {
      if (/\.png$/.test(element.src)) //���� ���� �������� ����� ���������� PNG
      {
        src = element.src;
        element.src = "img/x1x1.gif"; //�������� ����������� ���������� gif-��
      }
    }
    else //�����, ���� ��� �� �������� � ������ �������
    {
	  //���� � �������� ������ ������� ��������, �� ����������� �������� �������� background-�mage ���������� src
      src = element.currentStyle.backgroundImage.match(/url\("(.+\.png)"\)/i);
      if (src)
      {
        src = src[1]; //����� �� �������� �������� background-�mage ������ ����� ��������
        element.runtimeStyle.backgroundImage="none"; //������� ������� �����������
      }
    }
    //����, src �� ����, �� ����� ��������� ����������� � ������� ������� AlphaImageLoader
    if (src) {
    	var img=new Image(),el=element;
	    function onload(){
	    	el.style.width=img.width+'px';
	    	el.style.height=img.height+'px';
	    	el.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
//	    	alert([img.width+'px',img.height+'px'])
	    	img.onload=null;
	    }
    	img.src=src;
    	img.onload=onload;
		if(img.complete && img.onload) img.onload();
    }
  }
}