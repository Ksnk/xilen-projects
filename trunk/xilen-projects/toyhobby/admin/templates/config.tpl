<!--begin:config-->
<form action="" method="POST" name="config">
	<input type="submit" name="clear_empty_flesh" value="��������� �������� ��������"><br>
	<input type="submit" name="clear_free_article" value="��������� �������� ������"><br>

	<input type="submit" name="clear_katalogue" value="��������� �������"><br>

	<input type="submit" name="check_uploaded" value="��������� ������ � �����">
	<input type="submit" name="clear_unused" value="������� �������� �����"><br>

	<input type="submit" name="clear_anchors" value="������� ��� �����"><br>
	<input type="submit" name="optimize" value="�������������� �������"><br>
	<input type="submit" name="check_NS" value="��������� ����������� NS"><br>
	<input type="submit" name="heal_NS" value="�������� NS"><br>
	<input type="text" name="delete_id" value="">
	<input type="submit" name="delete" value="������� ��������"><br>
<br>
	<label>����������� �� ����:<input type="text" name="host" ></label>
	<label>������(id):<input type="text" name="razdel" ></label>
	<input type="submit" name="copy" value="�����������"><br>

<!--begin:listfile-->
<b>����������� �����</b>{count}<div style="clear:both;">
<!--begin:list-->
<span style="float:left;">[<a title="found at: {where},{xid}" href="?do=find&id={xid}">{name}</a>]&nbsp;&nbsp; </span>
<!--end:list--></div>
<div style="clear:both;"></div>
<!--end:listfile-->
<!--begin:lostfile-->
<b>�������� �����</b>{count}<div>
<!--begin:list-->
<span style="float:left;">[<a href="#">{name}</a>]&nbsp;&nbsp;</span> 
<!--end:list--></div>
<div style="clear:both;"></div>
<!--end:lostfile-->
</form>
<!--end:config-->


