<?php
 class Mysql{
	function __construct(){
		$this->conn=$this->connectDB();
	}
	function connectDB(){
        $conn =  mysqli_connect('localhost','root','',"dbm");
		mysqli_query($conn,"set names gbk");
        return $conn;
    }
	function fetch($result){
        $row = mysqli_fetch_array($result);
        return $row;
    }
	function query($sql){
        $res = mysqli_query($this->conn,$sql);
		return $res;
	}
}
$mysql = new Mysql();

?>