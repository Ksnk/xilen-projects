<html><head>
<title>������ ���������� ������� ���������</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script type="text/javascript" src="js/engine.js"></script>
<script type="text/javascript" src="js/jquery.pack.js"></script>
<style type="text/css">
.thetable input, .thetable textarea {
	background:#FFFFFF none repeat scroll 0%;
	border:1px solid #D0D0D0;
	color:#505050;
	font-family:verdana,arial,sans-serif;
	font-size:11px;
	font-weight:normal;
	width:98%;
}

.text {
	font-family:verdana,arial;
	font-size:11pt;
}

.thetable th ,.thetable td, .text {
	padding:2pt 10pt 2pt 10pt;
}
.thetable th {
	background-color:#e0e0e0;
	color:blue;
}
.thetable td {
	background-color:#f2f2f2;
}

.win_max, input.win_max {
	background: url(img/alert_x.gif) no-repeat 0 -60px;
	border:0;
	width:15px; height:15px;
	display:block;
	cursor:pointer;
}

.win_max.closed {
background-position:0 -45px;
}

.compact td {
   padding:0 ;
   margin:0;
}
.glass input {
	background: transparent; border:0;
}

td.uploader {height:60px;}
.float_left {
	float: left;
}

.float_right {
	float: right;
}

.align_left {
	text-align: left;
}
.align_right table , .align_right div {
	margin-left:auto;
}

.align_middle,.align_center {
	text-align: center;
}

.align_center table , .align_center div {
	margin-left:auto;
	margin-right:auto;
}

.align_right {
	text-align: right;
}
.align_right table , .align_right div {
	margin-left:auto;
}

</style>
<script type="text/javascript">
function Edit (e) {
	var f = document.forms['FormCategory'];
	f['select'].value=e.id.substr(3);
}
function ClearFormEdit () {
    document.getElementById('TitleForm').innerHTML = '�������� ���������';
    document.getElementById('buttonForm').value = '��������';
    document.FormCategory.id.value = 'xx';
    document.FormCategory.name.value = '����� ���������';
    document.FormCategory.doing.value = 'new';
}
$(function(){
	var $drag_tgt,
		droppable=element.cls('droppable');

	$('.draggable').each(function(){
		$DDR({
			drag: this,
			on_movefin: function(opt){
				if(!$drag_tgt || $drag_tgt==opt['drag'])return;
				//alert([opt['drag'].id,$drag_tgt.id]);
				var f = document.forms['FormCategory'];
				f['from_id'].value=opt['drag'].id.substr(3);
				$id=$drag_tgt.id;
				f['near_id'].value=$id.substr(2,1)=='+'?$id.substr(3):'';
				f['parent_id'].value=$id.substr(2,1)!='+'?$id.substr(3):'';
				f['doing'].value='moveit';
				f.submit();
			},
			on_start: function(opt){
				//opt['drag'].style.position="absolute";
			},
			on_move: function(opt,e){
				var o= e.target || e.srcElement;
				/*	o && o.tagName && !droppable.have(o);
					o = o.parentNode ){};*/
				if(droppable.have(o) && (!$drag_tgt || $drag_tgt!=o)){
					if($drag_tgt)$drag_tgt.style.border='';
					$drag_tgt=o;
					o.style.border="1px solid red";
				}
			}
		});
	})
});

</script>
</head>
<body>
<h1>������ ���������� ������� ���������</h1>
<!--begin:head-->
{first|\}<a href="?id={id}">{name}[{id}]</a>
<!--end:head-->
<h2>������ ���������</h2>
<div>
<ul class="text">
<!--begin:row-->
<li style="padding:2pt;">
     <a href="#" id="id-{id}" onclick="Edit(this);return false;" class="draggable droppable">{name} [{id}]</a>
     <span id="id+{id}" class="droppable" style="position:relative; top:7pt;color:blue;">&#8629;</span>
     {ul}
</li>
<!--end:row-->
</ul></div>

<a name="form"></a>
    <form action="" method="post" name="FormCategory">
<table  align="center" width="95%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>��������</td>
        <td colspan=2><input type="text" value="����� ���������" name="name" size="60"></td>
    </tr>
    <tr>
        <td>������� ���������</td>
        <td><select name="select" value="{id}">
            <option value="root">-- �������� ��������� --</option>
            {list_select}
        </select></td>
        <td>
        	<input type="submit" value="�������" name="del">&nbsp;
        	<input type="submit" value="��������" name="new">
        </td>
    </tr>
    <tr>
        <td>�������� ����� ���������</td>
        <td colspan=2>
        	<input type="submit" value="����� �� ���������" name="insertafter">&nbsp;
        	<input type="submit" value="�������� � ���������" name="insert">
</td>
    </tr>
    <tr>
        <td>������� �������</td>
        <td colspan=2>
        	<input type="text"  name="from_id">&nbsp;
        	<input type="text"  name="near_id">
        	<input type="text"  name="parent_id">
        	<input type="text"  name="doing">
		</td>
    </tr>
</table>
     </form>
</body></html>
