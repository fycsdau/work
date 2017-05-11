<?php

namespace Home\Model;
use Think\Model;

class UsersModel extends Model{
    protected $_validate = array(
        array('username','','名称已经存在！',0,'unique',1),
    );
}