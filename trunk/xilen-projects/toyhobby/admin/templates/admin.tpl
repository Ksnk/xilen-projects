<!--begin:paramedit-->
<form action="" method="POST" name="paramedit">
	<div class="red">{error}</div>
<table  class="thetable tahoma">
<!--begin:list-->
<!--begin:subx-->
<tr><th  class="bblue align_center" colspan=2>{sub}</th></tr>
<!--end:subx-->
<tr  class="odd"><th class="align_right" >{title}</th>
<!--begin:input-->
<th ><input class='fills' type="text" style="width:auto;" name="{name}"></th>
<!--end:input-->
<!--begin:button-->
<th ><input class='button' type="submit" name="{function}" value="{name}"></th>
<!--end:button-->
<!--begin:textarea-->
<th ><textarea class='fills' style="height:3em;width:auto;" name="{name}"></textarea></th>
<!--end:textarea-->

</tr>
<!--end:list-->
<tr  ><th class="bblue" style="padding:2px;"colspan=2 ><input type="submit" class="button" value="���������">
</th></tr>
</table>
</form>
<!--end:paramedit-->
<!--begin:theheader-->
<div style="padding:5px 0;"
class="size16 long menu align_center red"><u>{header}</u></div>
<!--begin:descr-->
<div style="padding:0px;" class="size16 long align_center">{descr}</div>
<!--end:descr-->
<div style="padding:5px 0;" class="align_center">{data}</div>
<!--end:theheader-->

<!--begin:thecounters-->
<div style="padding:5px 0;"
class="size16 long menu align_center red"><u>{header}</u></div>
<!--begin:data-->
<hr>
	������� : {counter}; ����� ���������� ������:{total}<br>
	�� ��������� �����:<br>
<!--begin:list-->
    {date}: {cnt}<br>
<!--end:list-->
<!--end:data-->
<!--end:thecounters-->

<!--begin:techonline_pref-->
<!--end:techonline_pref-->

<!--
 �����������������

 - ����� ����� ������ "������ �������� ������"

 -->
<!--begin:order_elm_start-->
<table class="compact"><tr><td><input type="button" class="win_max p120"
 onclick="order(this,'+');"
>
</td><td>
<!--end:order_elm_start-->

<!--begin:order_elm_fin-->
</td><td>
<input type="button" class="win_max p105"
 onclick="order(this,'-');"
>
</td></tr></table>
<!--end:order_elm_fin-->

<!--begin:align_elm-->
<table class="compact glass"><tr><td>c����</td><td>�����</td><td>������</td></tr>
<tr><td><input  type="radio" name="align" value="0"></td><td>
<input  type="radio" name="align" value="1"></td><td>
<input  type="radio" name="align"  value="2"></td></tr></table>
<!--end:align_elm-->

<!--begin:win_elm-->
	<div class="win_max open_close">&nbsp;</div>
<!--end:win_elm-->
<!--begin:win_elm2-->
	<div class="win_max closed  open_close">&nbsp;</div>
<!--end:win_elm2-->

<!--begin:delrec_elm-->
<input type="button" class="win_max p00"
 onclick="delrec(this)">
<!--end:delrec_elm-->

<!--begin:psubm_elm-->
<input type="submit" name="add_new_line" class="win_max p90" value='&nbsp;'>
<!--end:psubm_elm-->


<!--begin:href-->
<form action="" method="POST" name="href">

<table class="thetable">
<tr><th colspan=2>
�������� �������� (�� ������������ �� �����)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
���������� �������� ��� ������ ������
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
������������
</th><td colspan=3>{::tpl:admin:align_elm}
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>������</th>
<th>�����</th>
<th>�������</th>
<th>{::tpl:admin:win_elm}</th>
</tr>
<!--begin:list-->
<tr id="id_{id}">
<td >
<input type="text" name="filename_{id}">
</td>
<td ><input type="text" style="width:400px" name="text_{id}"></td>
<td class="align_center" style="width:55px;" nowrap>
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td>{::tpl:admin:delrec_elm}
</td>
</tr>
<!--end:list-->
<tr>
<td>
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
{::tpl:admin:psubm_elm}
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr><tr>
<th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="���������">
</th>
</table>
{pages}
</form>
<!--end:href-->

<!--begin:uploads-->
<form action="" method="POST" name="uploads">

<table class="thetable">
<tr><th colspan=2>
�������� �������� (�� ������������ �� �����)
</th><td colspan=3><input type="text" name="name">
</td></tr>
<tr><th colspan=2>
���������� �������� ��� ������ ������
</th><td colspan=3><input  type="text" name="columns">
</td></tr>
<tr><th colspan=2>
������������
</th><td colspan=3>{::tpl:admin:align_elm}
</td></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
</th></tr>
<tr>
<th>����</th>
<th>��������</th>
<th>�������</th>
<th>{::tpl:admin:win_elm}</th>
</tr>
<!--begin:list-->
<tr id="id_{id}">
<td class="uploader" style="width:150px;height:auto;">
<input type="text" name="filename_{id}">
</td>
<td ><input type="text" style="width:400px" name="text_{id}"></td>
<td class="align_center" style="width:55px;">
{::tpl:admin:order_elm_start}
<input type="text" class="order" style="width:15px;" name="order_{id}">
{::tpl:admin:order_elm_fin}
</td>
<td>{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
<tr>
<td class="uploader" style="height:auto;">
<input  type="text" name="filename">
</td>
<td><input type="hidden" name="id">
<input  type="text" name="text"></td>
<td></td><td>
{::tpl:admin:psubm_elm}
<input type="hidden" class="del" name="del">
</td>
</tr>
<tr><th colspan=5 height="5px;"></th></tr>
<tr><th colspan=5 class="align_center" style="background:white;height:30px">
<input type="submit" style="width:auto;"  name="save" value="���������">
</th></tr>
</table>
{pages}
</form>
<!--end:uploads-->
