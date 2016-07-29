<?php
namespace app\components;

use Yii;

class SqlQuery {
	public	$_num_row			= null;   //记录行数
	public  $_result			= false;  //增删改是否成功
	public  $_id				= null;	  //插入后自增id
	private $_dbName;
	private $_from				= null;
	private $_distinct			= false;
	private $_field				= array();
	public  $_where				= array();
	private $_max_min_avg_sum	= array();
	private $_limit				= null;
	private $_orderby			= array();
	private $_groupby			= null;
	private $_join				= array();
	private $_or				= array();
    public	$resultKey          = '';
    public $db = null;

	public function __construct() {
        $this->db = Yii::$app->db;
	}
	
	/**
	 * 得到查询结果的例子
	 *
	 *	$result = Core_Result::model()
	 *						->distinct()
	 *						->field('username')
	 *						->from('mf_new_user')
	 *						->join('mf_sina_relation', 'uid', 'inner')
	 *						->where("is_verfiy='Y'")
	 *						->where_or("city>'1'")
	 *						->where_in('user_type',array('company','person'))
	 *						->like('email', '%@%')
	 *						->is_not_null('phone')
	 *						->group_by('is_check')
	 *						->order_by('province','desc')
	 *						->limit(1,1)
	 *						->find();
	 */
	public static function model($default = 'biz_scrm'){
		return new self($default);
	}

	/**
	 * 得到查询结果数组
	 *
	 * @param string $table 数据库查询表名
	 * @param int	 $limit $offset 查询结果的偏移量 仅当$limit为1,$offset为空时返回一维数组,其他返回二维数组
	 * @return array
	 */
	public function find($table = '', $limit = null, $offset = null) {
		if ($table != ''){
			$this->from($table);
		}

		if ( ! is_null($limit)){
			$this->limit($limit, $offset);
		}

		$sql = $this->_compile_select();
		if(is_null($sql)){
			return false;
		}
		if($limit == 1 && $offset === null ){
			return $this->db->createCommand($sql)->queryOne();
		}else{
			return $this->db->createCommand($sql)->queryAll();
		}

	}

	/**
	 * 过滤参数
	 *
	 * @param string $key 需要过滤的参数
	 * @return unknown
	 */
	public function _clearing_params($string = null, $type = false) {
		if(is_array($string)) {
			if(!empty($string)) {
				foreach($string as $key => $val) {
					if(!empty($val)) {
						$string[$key] = $this->_clearing_params($val);
					}
				}
			}
		} elseif($string == NULL || empty($string)) {

		} else {
			$string = preg_replace('/(\w+)(\s*[>|<|=])/','`$1`$2',$string);
			$string = preg_replace('/(\w+)(\s(not)?\s+like)/','`$1`$2',$string);
			$string = preg_replace('/(\w+)(\s+is)/','`$1`$2',$string);
			$string = preg_replace('/(order\s+by\s*)(\w+)/','$1`$2`',$string);
			$string = preg_replace('/`(\w+)\.(\w+)`/','$1.`$2`',$string);
		}
		return $string;
	}

	/**
	 * 得到字段最大值
	 *
	 * @param string $select 需要求最大值的字段
	 * @param string $as 别名
	 * @return object
	 */
	public function max($select = '', $as = '') {
		$this->_max_min_avg_sum($select, $as, 'max');
		return $this;
	}

	/**
	 * 得到字段最小值
	 *
	 * @param string $select 需要求最小值的字段
	 * @param string $as 别名
	 * @return object
	 */
	public function min($select = '', $as = '') {
		$this->_max_min_avg_sum($select, $as, 'min');
		return $this;
	}

	/**
	 * 得到字段平均值
	 *
	 * @param string $select 需要求平均值的字段
	 * @param string $as 别名
	 * @return object
	 */
	public function avg($select = '', $as = '') {
		$this->_max_min_avg_sum($select, $as, 'avg');
		return $this;
	}

	/**
	 * 得到字段值的和
	 *
	 * @param string $select 需要求和的字段
	 * @param string $as 别名
	 * @return object
	 */
	public function sum($select = '', $as = '') {
		$this->_max_min_avg_sum($select, $as, 'sum');
		return $this;
	}

	/**
	 * 排组聚合函数
	 *
	 * @param string $select 需要使用聚合函数的字段
	 * @param string $as 别名
	 * @param string $type 聚合函数类别
	 */
	public function _max_min_avg_sum($select = '', $as = '', $type = 'max') {
		if ( in_array($type, array('max', 'min', 'avg', 'sum')) && is_string($select) && $select !== '' ) {
			if ($as === '')
				$this->_max_min_avg_sum[] = $type . '('.$select.') ';
			else
				$this->_max_min_avg_sum[] = $this->_as(array($type . '('.$select.')'=>$as));
		}
	}

	/**
	 * 为查询的字段取别名
	 *
	 * @param array $param 键为字段名值为别名
	 * @return string
	 */
	public function _as($param) {
		if( is_array($param) ){
			foreach( $param as $key=>$val ){
				if( !is_array($val) )
					return  $this->_clearing_params($key) . ' as ' .  $this->_clearing_params($val);
			}
		}
	}

	/**
	 * 数据库查询结果排重
	 *
	 * @param bool $value 默认true为查询结果排重
	 * @return object
	 */
	public function distinct( $value = true ) {
		$this->_distinct = (is_bool($value)) ? $value : true;
		return $this;
	}

	/**
	 * 数据库查询结果选取字段
	 *
	 * @param string $param 需要在结果中取用的字段(参数数量不固定,请使用字符串格式)
	 * @return object
	 */
	public function field() {
		if( func_num_args() > 0 ){
			foreach(  func_get_args() as $param ){
				if( is_array($param) ){
					$this->_field[] = $this->_as($param);
				}else{
					$this->_field[] = $this->_clearing_params($param);
				}
			}
		}
		return $this;
	}

	/**
	 * 数据库所查询数据表
	 *
	 * @param string $table 查询的表名
	 * @return object
	 */
	public function from($table = null) {
		$this->_from = $this->_clearing_params($table);
		return $this;
	}

	/**
	 * 数据库连表查询
	 *
	 * @param string $table 需要联查的表名
	 * @param string $cond 公共字段,如果连表的字段名相同则不要穿第4个参数$file
						   否则$cond代表所连表的共同字段名,$field代表from()引入表的字段名
	 * @param string $type 连表类型
	 * @param string $field 主表的公共字段,如和所连表字段名相同则不需要制定
	 * @return object
	 * 注意:调用本方法前,使用from()声明主表,否则将导致意外错误!
	 */
	public function join($table, $cond, $type = '', $field = null) {
		if( $this->_from === null ){
			return false;
		}

		$table = $this->_clearing_params($table);
		if( !in_array($type, array('left', 'right', 'outer', 'inner', 'left outer', 'right outer')) ){
			$type = '';
		}
		else{
			$type .= ' ';
		}

		if( is_null($field) ){
			$field = $cond;
		}

		if(is_array($cond) && count($cond)>0){
			foreach($cond as $key=>$val){
				$join[] = $table.'.'.$val.' = '.$this->_from.'.'.$field[$key].' ';
			}
			$this->_join[] = $type.'join '.$table.' on '.implode( $join, ' and ');
		}else{
			$this->_join[] = $type.'join '.$table.' on '.$table.'.'.$cond.' = '.$this->_from.'.'.$field.' ';
		}

		return $this;
	}

	/**
	 * 数据库查询条件
	 *
	 * @param string $param 参数数量不固定,用字符串格式穿入条件例如->where('id=1','type=2');
	 * @return object
	 */
	public function where() {
		$this->_where( func_get_args() );
		return $this;
	}

	/**
	 * 数据库查询条件(或)
	 *
	 * @param string $param 参数数量不固定,用字符串格式穿入条件例如->where_or('id=1','type=2');
	 * @return object
	 * 注意:此方法是在where()后添入或者条件,如只调用本方法且在提取结果前未调用where()方法,不会发生意外错误,但是查询结果将不准确!
	 */
	public function where_or() {
		if( func_num_args() > 0 ){
			foreach(  func_get_args() as $param ){
				$this->_or[] = $this->_clearing_params($param);
			}
		}
		return $this;
	}

	/**
	 * 数据库查询条件(in)
	 *
	 * @param string $key 查询条件中需要where in的字段
	 * @param array $value 查询条件中需要where in的值的范围
	 * @return object
	 */
	public function where_in($key, $value){
        if(!empty($key)) {
            $this->_where( $key ." in ('" . implode($value,"','") . "')" );
        }
		return $this;
	}

	/**
	 * 数据库查询条件(not in)
	 *
	 * @param string $key 查询条件中需要where not in的字段
	 * @param array $value 查询条件中需要where not in的值的范围
	 * @return object
	 */
	public function where_not_in($key, $value){
        if(!empty($key)) {
            $key = $this->_clearing_params($key);
            //$value = $this->_clearing_params($value);
            $this->_where( $key ." not in ('" . implode($value,"','") . "')" );
        }
		return $this;
	}

	/**
	 * 排组数据库查询条件
	 *
	 * @param string/array $where 数据库查询条件
	 */
	public function _where($where) {
		if( !is_array($where) )
			$where = array($where);
		foreach( $where as $param ){
			if(!empty($param)){
				if(is_array($param)){
					foreach($param as $k=>$v){
						if(is_null($v)){
							continue;
						}
						if(!is_numeric($k)){
							$this->_where[] = $this->_clearing_params( "`".$k."`='".$v."'" );
						}else{
							$this->_where[] = $this->_clearing_params($v);
						}
					}
				}else{
					$this->_where[] = $this->_clearing_params($param);
				}
			}
		}
	}

	/**
	 * 数据库查询条件
	 *
	 * @param string $key 查询条件中需要like的字段
	 * @param array $value 查询条件中需要like的值
	 * @return object
	 */
	public function like($key, $value){
        if(!empty($key)) {
            $this->_where( $key." like '".$value."' " );
        }
		return $this;
	}

	/**
	 * 数据库查询条件
	 *
	 * @param string $key 查询条件中需要not like的字段
	 * @param array $value 查询条件中需要not like的值
	 * @return object
	 */
	public function not_like($key, $value){
        if(!empty($key)) {
            $this->_where( $this->_clearing_params($key)." not like '".$this->_clearing_params($value)."' " );
        }
		return $this;
	}

	/**
	 * 数据库查询条件
	 *
	 * @param string $field 查询条件为空的字段
	 * @return object
	 */
	public function is_null($field){
		$this->_where( $this->_clearing_params($field).' is null ' );
		return $this;
	}

	/**
	 * 数据库查询条件
	 *
	 * @param string $field 查询条件不为空的字段
	 * @return object
	 */
	public function is_not_null($field){
		$this->_where( $this->_clearing_params($field).' is not null ' );
		return $this;
	}

	/**
	 * 数据库查询结果分组
	 *
	 * @param string $field 查询条件需要分组的字段
	 * @return object
	 */
	public function group_by($field = null) {
		if( is_string($field) && !empty($field)) {
			$this->_groupby = $this->_clearing_params($field);
		}
		return $this;
	}

	/**
	 * 数据库查询结果排序
	 *
	 * @param string $field 查询结果排序参照字段
	 * @return object
	 */
	public function order_by($field, $type = '') {
		if(!empty($field)){
			$field = $this->_clearing_params($field);
			$this->_orderby[] .= in_array( $type,array('asc','desc') ) ? $field.' '.$type : $field.' asc';
		}
		return $this;
	}

	/**
	 * 数据库查询结果分割
	 *
	 * @param int $limit 查询结果排序参照字段
	 * @param int $offset 查询结果排序参照字段
	 * @return object
	 */
	public function limit($limit = null, $offset = null) {
		if(is_numeric($limit) && ($limit!=0 && $offset!=0))
			$this->_limit[] = abs($limit);
		if(is_numeric($offset) && $offset!=0)
			$this->_limit[] = abs($offset);
		return $this;
	}

##########################################################################################################################
	/**
	 * 排组sql
	 *
	 * @return string
	 */
	public function _compile_select() {
		$sql = 'select ';

		if( $this->_distinct !== false ){
			$sql .= 'distinct ';
		}

		$select = '';
		if( count($this->_max_min_avg_sum)>0 ){
			$select .= implode($this->_max_min_avg_sum,',').' ';
		}

		if( count($this->_field)>0 ){
			if( $select !== '' ){
				$select = trim($select).',';
			}
			$select .= implode($this->_field,',').' ';
		}

		if( $select ==='' ){
			$sql .= '* ';
		}else{
			$sql .= $select.' ';
		}

		if( $this->_from !== null ){
			$sql .= 'from '.$this->_from.' ';
		}else{
			return null;
		}

		if( count($this->_join)>0 ){
			$sql .= implode($this->_join,' ').' ';
		}

		if( count($this->_where)>0 ){
			$sql .= 'where '.implode($this->_where, ' and ').' ';

			if( count($this->_or)>0 ){
				$sql .= 'or '.implode($this->_or, ' or ').' ';
			}
		}

		if( $this->_groupby !== null ){
			$sql .= 'group by '.$this->_groupby.' ';
		}

		if( count($this->_orderby)>0 ){
			$sql .= 'order by '.implode($this->_orderby, ',').' ';
		}

		if( $this->_limit !== null ){
			$sql .= 'limit '.implode($this->_limit,',').' ';
		}
		$this->_reset_params();
		return $sql;
	}

	/**
	 * 重置属性
	 *
	 */
	public function _reset_params() {
		$this->_num_row			= null;
		$this->_result			= false;
		$this->_id				= null;
		$this->_from			= null;
		$this->_distinct		= false;
		$this->_field			= array();
		$this->_where			= array();
		$this->_max_min_avg_sum	= array();
		$this->_limit			= null;
		$this->_orderby			= array();
		$this->_groupby			= null;
		$this->_join			= array();
		$this->_or				= array();
	}
    
}

