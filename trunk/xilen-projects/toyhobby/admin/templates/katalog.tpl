///// ����� ������� ��������
<!--begin:additional-->
<table class="bblue table long tahoma size11 "><tr >
<td>&nbsp;</td>
<td class="align_right">����������</td>
<td class="align_left">
	<input type="hidden" name="doIt[{i}]" id="doIt[{i}]">
<select class="tahoma fills size11" title="" onchange="return mean(this,{i});" name="sel_act[{i}]">
	<option value=""></option>
	<option value="spec">� ���������������</option>
	<option value="new">� �������</option>
	<option value="del">�������</option>
</select>
<!-- <input type="submit" class="button " name="doIt[{i}]" value="������">  --> </td>
<td><div class="uploader" >
	<input onclick="loadCSV(this)" type="text" style="display:none;"
			name="item_csv_0">	<input type="button" class="button"
		 value="������� CSV">
	 </div>
</td>
		 
<td class="align_right">
<input type="button" class="button" onclick="element.$('newrow').style.display='';this.disabled='disabled';" name="newRecord" value=" �������� {what}">
</td><td>
<input type="submit" class="button savebutton" disabled="disabled" value="���������">
</td>
<td>&nbsp;</td>
</tr></table>
<!--end:additional-->

///// ������ ����������� �������

<!--begin:additional1-->
<table class="bblue table long tahoma size11 "><tr >
<td>&nbsp;</td>
<td class="align_right">����������</td>
<td class="align_left">
	<input type="hidden" name="doIt[{i}]" id="doIt[{i}]">
<select class="tahoma fills size11" title="" onchange="return mean(this,{i});" name="sel_act[{i}]">
	<option value=""></option>
	<!--begin:options-->
	<option value="{id}">{value}</option>
	<!--end:options-->
	<option value="del">�������</option>
</select>
<!-- <input type="submit" class="button " name="doIt[{i}]" value="������">  --> </td>
<td><div class="uploader" >
	<input onclick="loadCSV(this)" type="text" style="display:none;"
			name="item_csv_0">	<input type="button" class="button"
		 value="������� CSV">
	 </div>
</td>
		 
<td class="align_right">
<input type="button" class="button" onclick="element.$('newrow').style.display='';this.disabled='disabled';" name="newRecord" value=" �������� {what}">
</td><td>
<input type="submit" class="button savebutton" disabled="disabled" value="���������">
</td>
<td>&nbsp;</td>
</tr></table>
<!--end:additional1-->

<!--begin:admin_katalog-->
<form name='admin_katalog' action="" method="POST">
<div class="align_center menu long"><span class="red">{paragr}</span>
</div>
<span style="color:red;font-size:16px;">{error}</span>
<input type='hidden' class="del" name="del">
<table class="table long ctext size11"><tr style="display:{xxx|none};">
<td>��������</td>
<td>
<select class="tahoma size11" onchange="_goto('cat',this.value)"
	 name="selcat">
	<!--begin:option--><option value="{id}">{name}</option><!--end:option-->
	<option value="null">&raquo; ��� ��������� &laquo;</option>
	<option value="all">&raquo; ��� ������ &laquo;</option>
</select></td>
</tr></table>

<table class="table long ctext size11 bblue"><tr >
<td>&nbsp;</td>
<td nowrap  class="align_right">� ����������</td>
<td  class="align_left">
<select class="tahoma size11" name="selact">
	<option value="null">� ���������������</option>
	<option value="null">� �������</option>
	<option value="all">�������</option>
</select>
<input type="submit" class="button " value="�������"></td>
<td class="align_center">
<input type="submit" class="button savebutton" disabled="disabled" value="���������">
</td>
<td>&nbsp;</td>
</tr></table>
<div style="width:100%;overflow:auto;">
<table class="table long ctext size11">
<tr><td style="background:white;"><input type="checkbox" id="aaa" value="0"></td>
<th style="padding:10px 0;" >�</th>
<!--begin:head-->
<th class="{class}">{title}</th>
<!--end:head-->
<th></th>
</tr>
<!--begin:list-->
<tr class="{trclass|odd}" id="rl_{id}"><th style="background:white;padding:0;"><input type="checkbox" class="select" name="aaa" value="{id}"></th><th class="nopage">{numb}</th>
<!--begin:row-->
<td class="{class|text_edit}" id="{name}_{id}">{value}</td>
<!--end:row-->
<td style="width:20px">{::tpl:admin:delrec_elm}</td>
</tr>
<!--end:list-->
</table>

<table class="thetable long tahoma ctext size11">
<tr>
<th class="align_center" >
	<input class="button savebutton"  type="submit" disabled="disabled" value="���������">
	<input class="button"  type="submit" name="add_item" value="��������">
</th></tr>
<tr><td  style="background:white;height:30px">
{pages}
</td></tr></table>
</div>
<script type="text/javascript">
element.add_event(element.$('aaa'),'click',function(){
	var e = this;
	element.allClass(this.form,'select',function(el){
		el.checked = e.checked;
	})
	e=null;
})
</script>
</form>
<!--end:admin_katalog-->
<!--begin:category_list-->
<div class="menu size11">���c�� ���������</div>
<ul class="menu size11">{data}</ul>
<hr>
<div class="menu size11"><a href="{::curl:cat}cat=new">�������</a> </div>
<div class="menu size11"><a href="{::curl:cat}cat=spec">���������������</a> </div>
<div class="menu size11"><a href="{::curl:cat}cat=null">��� ���������</a> </div>

<!--end:category_list-->