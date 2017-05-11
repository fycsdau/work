<?php
namespace LaneWeChat\Core;
Class DB {

    //构造函数
    public function _construct() {
        $time = self::microtime_float();
        include("config.db.php");
        $link_id = self::connect($db_host, $db_user, $db_pass, $db_name, $pconnect);
        return $link_id;
    }

    //数据库连接
    public function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0,$charset='utf8') {
        
        if( $pconnect==0 ) {
            $link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
            if(!$link_id){
                self::halt("数据库连接失败");
            }
        } else {
            $link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw);
            if(!$link_id){
                self::halt("数据库持久连接失败");
            }
        }
        
        if(!@mysql_select_db($dbname,$link_id)) {
            self::halt('数据库选择失败');
        }
        @mysql_query("set names ".$charset);
        return $link_id;
    }

    //查询 
    public function query($sql) {
        $link_id = self::_construct();
        self::write_log("查询 ".$sql);
        $query = mysql_query($sql,$link_id);
        if(!$query) self::halt('Query Error: ' . $sql);
        return $query;
    }

    //获取一条记录（MYSQL_ASSOC，MYSQL_NUM，MYSQL_BOTH）              
    public function get_one($sql,$result_type = MYSQL_ASSOC) {
        $query = self::query($sql);
        $rt =& mysql_fetch_array($query,$result_type);
        self::write_log("获取一条记录 ".$sql);
        return $rt;
    }

    //获取全部记录
    public function get_all($sql,$result_type = MYSQL_ASSOC) {
        $query = self::query($sql);
        $i = 0;
        $rt = array();
        while($row =& mysql_fetch_array($query,$result_type)) {
            $rt[$i]=$row;
            $i++;
        }
        self::write_log("获取全部记录 ".$sql);
        return $rt;
    }

    //插入
    public function insert($table,$dataArray) {
        $field = "";
        $value = "";
        if( !is_array($dataArray) || count($dataArray)<=0) {
            self::halt('没有要插入的数据');
            return false;
        }
        while(list($key,$val)=each($dataArray)) {
            $field .="$key,";
            $value .="'$val',";
        }
        $field = substr( $field,0,-1);
        $value = substr( $value,0,-1);
        $sql = "insert into $table($field) values($value)";
        self::write_log("插入 ".$sql);
        if(!self::query($sql)) return false;
        return true;
    }

    //更新
    public function update( $table,$dataArray,$condition="") {
        if( !is_array($dataArray) || count($dataArray)<=0) {
            self::halt('没有要更新的数据');
            return false;
        }
        $value = "";
        while( list($key,$val) = each($dataArray))
            $value .= "$key = '$val',";
        $value = substr( $value,0,-1);
        $sql = "update $table set $value where 1=1 and $condition";
        self::write_log("更新 ".$sql);
        if(!self::query($sql)) return false;
        return true;
    }

    //删除
    public function delete( $table,$condition="") {
        if( empty($condition) ) {
            self::halt('没有设置删除的条件');
            return false;
        }
        $sql = "delete from $table where 1=1 and $condition";
        self::write_log("删除 ".$sql);
        if(!self::query($sql)) return false;
        return true;
    }

    //返回结果集
    public function fetch_array($query, $result_type = MYSQL_ASSOC){
        self::write_log("返回结果集");
        return mysql_fetch_array($query, $result_type);
    }

    //获取记录条数
    public function num_rows($results) {
        if(!is_bool($results)) {
            $num = mysql_num_rows($results);
            self::write_log("获取的记录条数为".$num);
            return $num;
        } else {
            return 0;
        }
    }

    //释放结果集
    public function free_result() {
        $void = func_get_args();
        foreach($void as $query) {
            if(is_resource($query) && get_resource_type($query) === 'mysql result') {
                return mysql_free_result($query);
            }
        }
        self::write_log("释放结果集");
    }

    //获取最后插入的id
    public function insert_id() {
        $id = mysql_insert_id($this->link_id);
        self::write_log("最后插入的id为".$id);
        return $id;
    }

    //关闭数据库连接
    protected function close() {
        self::write_log("已关闭数据库连接");
        return @mysql_close($this->link_id);
    }

    //错误提示
    private function halt($msg='') {
        $msg .= "\r\n".mysql_error();
        self::write_log($msg);
        die($msg);
    }

    //析构函数
    public function __destruct() {
        self::free_result();
        $use_time = (self::microtime_float())-($time);
        self::write_log("完成整个查询任务,所用时间为".$use_time);
        if(self::is_log){
            fclose(self::handle);
        }
    }

    //写入日志文件
    public function write_log($msg=''){
        $handle = fopen($logfilepath."dblog.txt", "a+");
        $text = date("Y-m-d H:i:s")." ".$msg."\r\n";
        fwrite($handle,$text);
    }

    //获取毫秒数
    public function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

?>