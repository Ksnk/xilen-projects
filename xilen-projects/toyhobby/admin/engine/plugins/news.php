<?php
/**
 * ������ - ������� ������
 */
define('VOCABULAR_TPL','tpl_vocabular');

class ml_plugin extends plugin {
	
	/**
	 * ���������� ������� � ��������� �������� ������ �������� � 
	 * ������
	 */
	function get_head(){
		if(isset($this->head_tpl))
			return smart_template($this->head_tpl,array());
		else
			return '';	
	}

	function _init ($arg){
		foreach($arg as $k=>$v){
			$this->$k=$v;
		}
	}

	function getPluginName(){
		if(!empty($this->title))
			return $this->title ;
		return 	__CLASS__;
	}

	function getContext($id){
		$key=array();
		$this->serialize($id,$key,false);
		return $key;
	}

	function check_data(&$data){ return true;}

	function serialize($id,&$key,$dir=false){

		if(!is_array($id))
			$res=$this->data('row',$id);
		else
			$res=$id;
		if($dir){
			$upd=array();
			foreach ($this->fields as $vv){
				if(!isset($vv['serialize']) || $vv['serialize']){
					$v=$vv[1];
					$idx=$v.'_'.$res['id'];
					if($vv[2]=='article' && isset($key['edit_'.$vv[1].'_'.$res['id']])){
						if(empty($res[$v])) { // ����� ������
							$node=$this->parent->nodeAdd(0,array('name'=>'itemarticle','type'=>type_ARTICLE));
							$x=$this->parent->nodeGet($node);
							$upd[$v]=$x[0]['id'];
							$this->newurl='do=page&id='.$upd[$v];
						} else {
							$this->newurl='do=page&id='.$res[$v];	
						}
					} elseif (isset($key[$idx]) && ($key[$idx]!=$res[$v])) {
						$upd[$v]=pps($key[$idx]);
					}
				} 
			}
			if(!empty($upd) && $this->check_data($upd,$res)){
				$this->data('upd',$upd,$res['id']);
			}
		} else {
			//debug(111);debug($res);
			foreach ($this->fields as $vv){
				if(!isset($vv['serialize']) || $vv['serialize']){
					$v=$vv[1];
					$key[$v.'_'.$res['id']]=pps($res[$v]);
				}
			}
		}
	}

	function data($what,$from='',$perpage=''){
		switch($what){
			case "cnt":
				return @$this->database->selectCell('select count(*) from ?'.$this->base.';');
			case "row":
				return $this->database->selectRow('select * from ?'.$this->base.' where `id`=?d',$from);
			case "data":
				return $this->database->query('select * from ?'.$this->base.' '.
					pps($this->orderbystr).' LIMIT ?d,?d',$from,$perpage);
			case "del":
				$this->database->query('DELETE from ?'.$this->base.' where `id`=?d;',$from);
				break;
			case "upd":
				$this->database->query('update ?'.$this->base.' set ?a where `id`=?;',$from,$perpage);
				break;
			case "ins":
				return $this->database->query('INSERT INTO ?'.$this->base.' (?#) VALUES(?a);',
		   			array_keys($from),array_values($from));
		}
	}

	function handlePost(){;}
	function handlePostAfter(){
		if(isset($this->newurl))
			$this->parent->go($this->newurl);
	}
/**
 * ������� �������������:
 
 * ��� ������� � ������������ ��������
// ����-������� � ������ ������� ��������
	array('<input type="checkbox" id="aaa" value="0">','check','checkbox'),
// ���� �������� ����������. ��� �������� �� ������ ����������� ����������� 2
	array('����','pic_small','image','csvfields'=>array('����')),
// ������� "���������" ����
	array('�������','articul','csvfields'=>array('�������')),
// ��������� ���� � ���������� �� 10 ����
	array('��������','sdescr','afilter'=>10,'csvfields'=>array('��������'))
// ������ �� ������� �������
	array('������','the_href','link'),
// ����. ���� - ��� cost
	array('����','cost','csvfields'=>array('����','����')),
// ���������� �������� ����-1/��� -0
	array('����','ostatok','check01'),

 * ��� ������� � ��������� �����
// ���� ����������. ����������� � ���������� � 'sort'=>true
	array('����.','item_order','dontshow'=>true)
		)
		,'sort'=>true

 * 
 * */	
	
	function admin_plugin($nomenu=true){
//		debug($this->parent);
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');

		if($nomenu)
			$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));

		if(!empty($this->base)){
		//
		$cnt=$this->data('cnt');
		if($cnt===null) return '�� ����������� ������ <a href="'
			.$this->parent->curl('do','id').'do=create&plugin='
			.get_class($this).'">'.get_class($this).'</a>';

		// ����������������� ������ ��������
		$perpage=ppx($this->parent->getPar('items-perpage'),ppi($this->item_perpage,20));
	//	debug($cnt);
		if(empty($cnt))
			$maxpg=1;
		else
			$maxpg=ceil(($cnt-0.5)/$perpage);
		$page=ppi($_GET['pg']);
		if($page>=$maxpg)$page=max(0,$maxpg-1);

		$res=$this->data('data',$page*$perpage,$perpage);

		$pages=$this->parent->calc_Pages($cnt,$perpage,$page);
		//debug($res);
		if(empty($res)) $res='';

		$head=array();
		$row=array();
		$plus=array();
		$colnumb=0;
		foreach ($this->fields as $vv){
			if(!empty($vv['dontshow'])) continue;
			$r=array('title'=>$vv[0]);
			if(pps($vv[2])=='checkbox')
				$r['class']='nopage';
			$head[]=$r;
		}
		foreach ($this->fields as $v){
			if(!empty($v['dontshow'])) continue;
			$colnumb++;	
			$plus[]=array('class'=>pps($v[2]),'name'=>'xx_'.$v[1]);
		}
		if (isset($this->sort) && $this->sort){
			$colnumb++;	
			$head[]=array();		
		}
		
		if(!empty($res)){
			$i=$page*$perpage+1;
		foreach($res as $k=>$v){
			$row=array();
			foreach ($this->fields as $vv){
				if(!empty($vv['dontshow'])) continue;
				$r=array(
					'class'=>pps($vv[2]),
					'name'=>$vv[1],
					'id'=>$v['id']
				);
				if (pps($vv[2])=='checkbox'){
					$r['class']='nopage';
					if(pps($vv[1])=='check'){
						$r['d']='h';
						//$r['style']='background:white;padding:0;';
						$r['value']='<input type="checkbox" class="select" name="aaa" value="'.$v['id'].'">';
					} else {
						//$r['style']='background:white;padding:0;';
						$r['class'].=' align_center';
						$r['value']='<input type="checkbox" class="select" name="'.pps($vv[1]).'" '.(empty($v[$vv[1]])?'checked="checked" ':'').'">';
					}
				} else if (pps($vv[2])=='picture'){
					$r['class']='nopage uploader';
					$r['value']=
					'<input type="text" style="display:none;" name="'.$vv[1].'_'.$v['id'].'">'.
					'<img style="width:80px;height:80px;" src="'.TO_URL(pps($v[$vv[1]])).'" alt="" onload="checkImg(this)">';
				} else if (pps($vv[2])=='align'){
					$r['class']='align_center';
					$r['value']='<input type="text" name="'.$vv[1].'_'.$v['id'].'" class="align hidden">';
				} else if (pps($vv[2])=='article'){
					$r['class']='nopage';
					$r['value']=
					'<input type="submit" name="edit_'.$vv[1].'_'.$v['id'].'" class="button green small" value="+">';
				} else if (pps($vv[2])=='image'){
					$r['class']='nopage uploader action_both';
					$val=TO_URL(pps($v[$vv[1]],'img/1x1t.gif'));
					$r['value']=
					'<div style="display:none;"><div><input type="button" onclick="ReplaceImg(this)"></div></div>'.
					'<input type="text" style="display:none;" name="'.$vv[1].'_'.$v['id'].'">'.
					'<input type="text" style="display:none;" name="'.str_replace('small','big',$vv[1]).'_'.$v['id'].'">'.
					'<img src="'.$val.'" alt="" onload="checkImg(this,80,60)">';
				} else if ($vv[1]=='order'){
					$r['value']='<input class="c_order" type="text" name="'.$vv[1].'_'.$v['id'].'">';
					$r['class'].=' nopage';
					$r['d']='h';
	//				debug($r['value']);
				} else if ($vv[2]=='check01'){
					$r['value']='<input class="win_check" type="text" name="'.$vv[1].'_'.$v['id'].'">';
					$r['class'].=' align_center nopage';
					//$r['d']='h';
	//				debug($r['value']);
				} else if ($vv[2]=='menu'){
					$r['value']='<div class="wide long '.$vv[3].'"><input class="long" type="text" name="'.$vv[1].'_'.$v['id'].'"></div>'	;
				} else if (pps($vv[2])=='link'){
					$r['value']=
					'<input type="text" class="nocontext long link_toolbox" '. 
					'onkeydown="need_Save()" value="" name="'.$vv[1].'_'.$v['id'].'"/>';
					
				} else if (isset($vv['afilter'])){
					//debug($vv['afilter']);
					$s=strip_tags($v[$vv[1]]);
					if(preg_match('/(?:\S+\s+){'.$vv['afilter'].'}/',$s,$m)){//'.$vv['afilter'].'
						$s=$m[0].' ...';
					}
					$r['value']=$s;

				} else {
					//print_r($v);
					$r['value']=pps($v[$vv[1]]);
				}
				if (isset($this->sort) && $this->sort){
					$res[$k]['sort']=array('id'=>$v['id']);	
				}
				$row[]=$r;
			}
			
			$res[$k]['prefix']=pps($this->prefix);
			$res[$k]['trclass']=evenodd($i);
			$res[$k]['numb']=$i++;
			$res[$k]['row']=$row;

		}}

		//$fields=array('the_header','the_text','news_date');
		if(empty($data)) $data='';

		$form=new form('admin'.$this->base);
		$form->nostore=true;
		$form->scanHtml(smart_template(array(VOCABULAR_TPL,'admin_vocabular'),array(
				'name'=>$this->base,
				'additional'=>pps($this->additional),
				'additional2'=>pps($this->additional2),
				'error'=>pps($_SESSION['errormsg']),
				'head'=>$head,
				'colnumb'=>$colnumb,
				'pages'=>$pages,
				'list'=>$res,
		        'plus'=>$plus

			)));
		if($form->handle()){
			$this->handlePost($res);
			if(!empty($res))
				foreach($res as $k=>$v){
					//debug($k);
					$this->serialize($res[$k],$_POST,true);
				}
			$key=array();
			foreach ($this->fields as $v){
				if(!empty($_POST['xx_'.$v[1]]))
					$key[$v[1]]=$_POST['xx_'.$v[1]];
			}
			if(!empty($_POST['del']) && preg_match('/^(\w+)_(\d+)$/',$_POST['del'],$m)){
				$this->data('del',$m[2]);
			}
			if(!empty($key) || !empty($_POST['newRecord'])){
				if($this->check_data($key,$key))
					$this->data('ins',$key);
			}

			$this->handlePostAfter($res);
			
			$this->parent->go($this->parent->curl());
		}

		//		$form->var=array_merge($form_var,array_diff_key($form->var,$form_var));
		//debug($res);
		if(!empty($res))
			foreach($res as $k=>$v){
				$this->serialize($res[$k],$form->var,false);
			}
		//debug($form->var);
		//debug()	
		$x=$form->getHtml(' ');
		} else {
			$x='';
		}
		if (method_exists($this,'get_parameters'))
			$x.=$this->parent->ffirst('do_siteparam', get_class ($this));

		return $x;
	}

	function setupmenu(){
		if (empty($this->parent->cur_menu)){
			$this->parent->cur_menu=get_class($this);
		}
		$this->parent->export('sitemap','getSiteMap');
		$x=$this->parent->export('sitemap','_getCurList');
		if(empty($x)){
			$this->parent->nopoplast=$this->getPluginName();
			$this->parent->export('sitemap','tit',$this->parent->nopoplast);
		
			$this->parent->export('sitemap','sub',$this->getPluginName());
		}
		$this->parent->handle(get_class($this));
	}
}

class search extends ml_plugin{

	function search(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
			'title'=>'���������� ������'
		));
	}

	function saveres($x){
		//debug($x);
		$_SESSION['search_result']=$x;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'���������� ������','title'=>'���������� ����������� �� ��������','name'=>'search_per_page');
	}

	function admin_search(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function result(){
		ml_plugin::setupmenu();
		if(isset($_SESSION['search_result']))
			$x=$_SESSION['search_result'];
		else
			$x=array();
		if(isset($x['katalog'])){
			$Kat=&new xKatalogue();
			$Kat->v=array('id'=>$x['katalog']);
			return $Kat->getText($x);		
		}	

		$page=ppi($_GET['pg']);
		$perpage=ppi($this->parent->getPar('search_per_page'),20);
		$cnt=count($x);
		if($page>floor($cnt/$perpage)) $page=0;
		$pages=$this->parent->calc_Pages($cnt,$perpage,$page);

		$x=array_slice($x,$page*$perpage,$perpage);
		$last='';$xx=array();
		foreach($x as $v){
			if($last!=$v['tag']){
				$last=$v['tag'];
				$xx[]=array('page'=>$v['tag'],'items'=>array());
			}
			$xx[count($xx)-1]['items'][]=array('item'=>$v['text']);
		}

		if(empty($x))
			return '����� �� ������ �� ������ ����������!';
		else
			return smart_template(array(ELEMENTS_TPL,'searchres'),
				array(
					'searchstr'=>$_SESSION['search_string'],
					'list'=>$xx,
					'pages'=>$pages
				));
	}
}

define ('NEWS_TPL','tpl_news');

class news extends ml_plugin
{
	
	function news(&$parent){
		parent::ml_plugin($parent);
		$this->head_tpl=array('tpl_news','news_head');
		parent::_init(
		array(
		'title'=>'�������'
		,'fields'=>array(
					array('����','news_date','text_edit'),
					array('���������','the_header','html_edit'),
					array('��������','pic_small','image'),
					array('��������','pic_big','image','dontshow'=>true),
					array('��������2','pic1_small','image'),
					array('��������2','pic1_big','image','dontshow'=>true),
					array('��������3','pic2_small','image'),
					array('��������3','pic2_big','image','dontshow'=>true),
					array('�����','the_text','html_edit','afilter'=>30)
		)
		,'base'=>'_news'
		,'orderbystr'=>' order by `news_date` DESC, `id` DESC'
		,'prefix'=>'nw'));
	}
	
	function handlePostAfter($v){
		parent::handlePostAfter($v);
		$this->parent->export('rss','do_rebuild');
	}
	
	/**
	 * ������� ������� ������-������ ��� ������� - �������� �������� �� �����
	 *  - ����� ����� ��������� ����� � �������
	 */
	function years(){
		static $x ; if(isset($x)) return $x;
		$res=$this->database->query('SELECT YEAR( `news_date` ) AS year
FROM ?_news
GROUP BY year
ORDER BY year;');
		$x=array();
		$today = getdate();
		$xYear=ppi($_GET['year'],$today['year']);
		foreach($res as $v){
			$x[]=array('year'=>$v['year'],'current'=>($v['year']==$xYear));
		}
		$x[count($x)-1]['last']=true;
		return $x ;
	}

	function check_data(&$upd,&$res){
		if(empty($res['news_date']))
			$upd['news_date']=date('Y/m/d H:i:s');
		else if(isset($upd['news_date'])){
			$dres=strtotime ($upd['news_date']);
			if( $dres===-1 || $dres===false)
				$upd['news_date']=date('Y/m/d H:i:s');
		}
		return true;
	}

	function do_newslist(){
		return $this->do_news('newslist',pps($_GET['id']));
	}

	function admin_news(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function search($s){
		$s=strtolower($s);
debug($s);
			//return array(page,item)
		$sql='SELECT *,LOCATE(?, LCASE(`the_text`)) as res, LOCATE(?, LCASE(`the_header`)) as res2
FROM ?_news
WHERE LOCATE(?,  LCASE(`the_text`))!=0 or LOCATE(?, LCASE(`the_header`))!=0
order by `id` LIMIT 50;';
		$res=$this->database->select($sql,$s,$s,$s,$s);
		if (empty($res)) return array();

		$result=array(); $lastparent=0;
		//debug($res);
		foreach($res as $v){
			if ($v['id']==$lastparent) continue;
			$lastparent=$v['id'];
			if(!empty($v['res']))
				$x=strip_tags(preg_replace('/^[^<]*>|<[^>]*$/','',substr($v['the_text'],max(0,$v['res']-200),200)));
			else
				$x=strip_tags($v['the_header']);

			$result[]=array(
				'tag'=>'<a href="?do=newslist">�������</a>',
				'text'=>'<a href="?do=news&id="'.$v['id'].'">'.$x.'</a>'
			);
		}
		debug($result);
		return $result;
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_news;');
		}
		$this->database->query("CREATE TABLE ?_news (
  `id` int(5) NOT NULL auto_increment,
  `news_date` date default NULL,
  `the_header` text,
  `the_text` text,
  `pic_small` text,
  `pic_big` text,
  PRIMARY KEY  (`id`)
);");
	}

	function news_b(){
		return $this->do_news('news_b');
	}
	
	function news_x(){
		return $this->do_news('news_x',0,1);
	}
	/**
	 * ������ ��� ������� ����� ����� ����
	 *
	 * @param template_name $tpl
	 * @param date_str $date
	 */
	function getNewsAfter($date=0,$tpl='newslist'){
		$res= $this->database->select(
			'select * from ?_news '.
			'where `news_date`>=?',$date
		);
		$news=array();
		foreach($res as $v) {
			$news[]=$this->getData($v,$tpl);
		}
		return smart_template(array(NEWS_TPL,$tpl),array('news'=>$news));
	}
	/**
	 * ��������� ������� news
	 */
	function do_news($tpl='newslist',$id=0,$limit=0){
/**
 * ������� ������� �� ����
 * .$this->get_limit()
 */
		static $x=array(); if(isset($x[$tpl])) return $x[$tpl] ;
		$sm='';
		ml_plugin::setupmenu();
		if($tpl=='') $tpl='newslist';
		$cnt=0;
		$pages='';
		$where='';
		
		$sql='select * from ?_news';
		if($id){
			$res=$this->database->query($sql.' where `id`=?d',$id);
		} else {
			if ($tpl=='news_b' || $tpl=='news_x'){
				$res=$this->database->query($sql." ORDER BY `news_date` DESC, `id` DESC".
					' LIMIT '.ppi($limit,ppi($this->parent->getPar('news_per_list'),3)).';');
			} else {
				$y=ppi($_GET['year']);
				if(!empty($y))
					$where=' where YEAR(`news_date`)='.$y.' ';
				else
					$where='';
				$page=ppi($_GET['pg']);
				$res=$this->database->selectRow('select count(*)as cnt from ?_news'.$where);
				$cnt=$res['cnt'];
				$perpage=ppi($this->parent->getPar('news_per_page'),3);
				//$limit = ' LIMIT '.($page*$perpage).','.$perpage;
				$res=$this->database->select(
					$sql.$where." ORDER BY `news_date` DESC, `id` DESC".
					' LIMIT ?d,?d',$page*$perpage,$perpage);
				$pages=$this->parent->calc_Pages($cnt,$perpage,$page);
			}
//*--*/ echo('/(?:\w+\W+){'.$this->get_limit(2).'}/');// LIMIT '.($page*$perpage).','.$perpage);
		}
		$news=array();
		if (!empty($res))
		foreach($res as $v) {
			$xx=$this->getData($v,$tpl);
			$xx['secpp']=>$this->parent->getPar('sec_per_picture',4);
			$news[]=$xx;
		}
		$news[count($news)-1]['last']=true;
	//*--*/echo '<!-- ';print_r($pages);print_r(debug_backtrace());echo ' -->';
	//debug($pages);
	debug($news);
	//debug(array(NEWS_TPL,''.$tpl));
		return $x[$tpl]=smart_template(array(NEWS_TPL,''.$tpl),array(
		'news'=>$news,
		'pages'=>$pages));
	}
	
	function getData(&$v,$tpl){
		if($tpl=="news_b") {
			if(preg_match('/(?:\S+\s+){'.ppi($this->parent->getPar('news_words_at_page'),30).'}/',trim(strip_tags(str_replace('&nbsp;','',$v['the_text']))),$m)){
				$v['the_text']=trim($m[0]).' ...';
			}
		}
		$s=trim(pps($v['the_text']));
		if(preg_match('/(?:\S+\s+){15}/',trim(strip_tags($s)),$m)){//'.$vv['afilter'].'
			$s=trim($m[0]).' ...';
		}
	//	debug('"'.$s.'"');
		$text_x=$s;			
		//$title_x=htmlspecialchars(trim(strip_tags(str_replace('&nbsp;','',pps($v['the_header'])))));
		if(empty($title_x)) $title_x=$test_x;
		$curnews=array(
			'id'=>pps($v['id']),
			'date'=>pps($v['news_date']),
			'title'=>pps($v['the_header']),
			'text'=>pps($v['the_text']),
			'text_b'=>$text_x
		);
		$sm=TO_URL($v['pic_small'],' ');
		//debug('xxx-"'.$sm.'" '.$v['pic_small'] );
		if(!empty($sm)){
			$pic= new xPic();
			foreach(array('pic','pic1','pic2') as $x ){
				if(!empty($v[$x.'_small'])){
					$pic->v=array(
						'pic_comment'=>'',
						'item_url'=>$tpl=='news_b'?$this->parent->curl('do','id').'do=newslist&id='.$v['id']:'',
						'pic_small'=>$v[$x.'_small'],
						'pic_big'=>$v[$x.'_big']
					);
					$pic->v[$x]=$v[$x];
					$curnews['img'][]=$pic->getData();
//					$curnews['img']['align']=xElement::aligns($v['align']);
				}
			};
//			$curnews['img']=$pic->getData();

		}
		return $curnews;
	}

	function get_parameters($par){
		$par['list'][]=array('sub'=>'������� - �������� ��������','title'=>'���������� ���� ��������� � ������','name'=>'news_words_at_page');
		$par['list'][]=array('title'=>'���������� �������� � ������','name'=>'news_per_list');
		$par['list'][]=array('title'=>'����� �������� (�������)','name'=>'sec_per_picture');
		$par['list'][]=array('sub'=>'������� - ����� ��������','title'=>'���������� �������� �� ��������','name'=>'news_per_page');
		$par['list'][]=array('sub'=>'������� - �����������������','title'=>'���������� �������� �� ��������','name'=>'words-perpage');
	}

}

class PhotoSizes extends plugin {

	function get_parameters($par){
		$par['list'][]=array('sub'=>'��������� ����������',
			'title'=>'������  ��������� ���������� (� ��������)','name'=>'pictute_xwidth');
		$par['list'][]=array('title'=>'������ ��������� ���������� (� ��������)','name'=>'pictute_xheight');
		$par['list'][]=array('sub'=>'������� ����������','title'=>'������ ������� ���������� (� ��������)','name'=>'pictute_xxwidth');
		$par['list'][]=array('title'=>'������ ������� ���������� (� ��������)','name'=>'pictute_xxheight');
		$par['list'][]=array('sub'=>'����������',
			'title'=>'���� �����������(0xFFFFFF - �����, -1 - ����������)','name'=>'pictute_background');
		$par['list'][]=array(
			'title'=>'������ �� �������','name'=>'pictute_tpl_width');
		$par['list'][]=array(
			'title'=>'������ �� �������','name'=>'pictute_tpl_height');
	}

	function admin_PhotoSizes(){
		$this->parent->menu['head']=array('MAIN','_modules',$this->getPluginName(),get_class($this));
		return smart_template(array(ADMIN_TPL,'theheader'),array(
			'header'=>'������� ����������',
			'descr'=>array('descr'=>'���������� ����� ������������� ����������� � ������������ ��������.<br>��������� ���������� �� ����������'),
			'data'=>$this->parent->ffirst('do_siteparam', get_class ($this))
		));
	}

	function getPluginName(){
		return 	"������ ����������";
	}

}

class vocabular extends ml_plugin {

	function vocabular(&$parent){
		parent::ml_plugin($parent);
		parent::_init(
		array(
			'title'=>'������� ��������'
			,'fields'=>array(
					array('�����','word','text_edit'),
					array('��������','descr','text_edit'),
				)
			,'base'=>'_vocabular'
			,'orderbystr'=>' order by `word`'
		));
	}

	function admin_vocabular(){
		return
		smart_template(array(ADMIN_TPL,'theheader'),array(
		'header'=>$this->getPluginName(),
		'data'=>parent::admin_plugin()));
	}

	function do_create($killall=true){
		if(!$this->parent->has_rights(right_WRITE))
			return $this->parent->ffirst('_loginform');
		if($killall){
			$this->database->query('DROP TABLE IF EXISTS ?_vocabular;');
		}
		$this->database->query("CREATE TABLE ?_vocabular (
  `id` int(11) NOT NULL auto_increment,
  `word` varchar(255) NOT NULL default '',
  `letter` char(1) NOT NULL default '',
  `descr` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `word` (`word`)
);");
	}

	function do_vocabular(){
		// create list
		$letters=array('������������������','��������');
		$cur=ppi($_GET['ch']);
		$sql='SELECT DISTINCT LCASE(LEFT(`word`,1)) FROM ?_vocabular';
		$lets=$this->database->selectCol($sql);
		debug($lets);
		$str='';$start=0;$curlet='�';
		foreach(array('������������������','��������') as $v){
			$menu='';
			for($i=0; $i<strlen($v);$i++){
				if($start+$i!=$cur){
					$menu.='<td class="align_center" style="padding:8px 0 8px 16px;">';
				} else {
					$curlet=$v{$i};
					$menu.='<td style="padding:8px 8px 0 8px;"><span class="button">';
				}
				if (in_array($v{$i},$lets))
					$menu.='<a href="'.$this->parent->curl('ch').'ch='.($start+$i).'">'.$v{$i}.'</a>';
				else
					$menu.='<span>'.$v{$i}.'</span>';
				if($start+$i!=$cur){
					$menu.='</td>';
				} else {
					$menu.='</span></td>';
				}
			}
			$start+=strlen($v);
			$str.='<table width="10%"><tr>'.$menu.'</tr></table>';
		}
		$sql='SELECT * from ?_vocabular where LCASE(LEFT(`word`,1))=?';
		$res=$this->database->select($sql,$curlet);

		$str='<div style="padding:25px 0 30px 0;margin-bottom:30px;" class="borderdn menu link align_center">'.$str.'</div>';
		$str.='<table class="tahoma">';
		foreach($res as $v){
			$str.='<tr><td style="padding:20px;"><img src="img/arr_red.gif"></td>'.
				'<td class="blue" style="padding:15px 0;">'.$v['word'].'</td><td  style="padding:15px;">'.$v['descr'].'</td></tr>'.
				'<tr><td colspan=3 class="borderdn" style="height:20px;">&nbsp;</td></tr>';
		}
		return $str.'</table>';
	}
}

?>