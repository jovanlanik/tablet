<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

class database {
	public $link = null;
	private $db_host = 'localhost';
	private $db_name = 'data';
	private $db_user = 'user';
	private $db_passwd = 'pass';
	private $table_book = 'tablet_book';
	private $table_note = 'tablet_note';

	public function __construct()
	{
		//$config = parse_ini_file('../../cfg/database.ini');
		if(isset($config['hostname'])) $db_host = $config['hostname'];
		if(isset($config['database'])) $db_name = $config['database'];
		if(isset($config['username'])) $db_user = $config['username'];
		if(isset($config['password'])) $db_passwd = $config['password'];
		if(isset($config['table_book'])) $table_book = $config['table_book'];
		if(isset($config['table_note'])) $table_book = $config['table_book'];
		$this->link = @mysqli_connect
		(
			$this->db_host, $this->db_user, $this->db_passwd, $this->db_name
		)
		or die('DB connection error');
	}

	public function getLesson($date, $num)
	{
		$query = "SELECT * FROM $this->table_book WHERE lesson = $num AND date = '$date';";
		$result = mysqli_query($this->link, $query);
		if($result == null) return null;
		$lesson = mysqli_fetch_array($result);
		return $lesson;
	}

	public function checkLesson($date, $num)
	{
		$lesson = $this->getLesson($date, $num);
		if($lesson['user'] == null) return false;
		else return true;
	}

	public function addLesson($date, $num, $length, $user, $amount)
	{
		$conf_max = 11;
		//$config = parse_ini_file('../../cfg/view.ini');
		if(isset($config['max'])) $conf_max = $config['max'];
		if($num < 0 || $num > 11) return;
		while($length + $num > 11) $length--;
		if($length < 1) return;
		if($amount < 1) return;
		$i = $num;
		while($i < $num + $length && $this->getLesson($date, $i) == null)
		{
			$i++;
		}
		$i -= $num;
		if($i < 1) return;
		$query = "INSERT INTO `lanikjo`.`tablet_book` (`date`, `lesson`, `length`, `user`, `amount`) VALUES ('$date', '$num', '$i', '$user', '$amount');";
		mysqli_query($this->link, $query);
	}

	public function removeLesson($date, $num)
	{
		$query = "DELETE FROM `lanikjo`.`tablet_book` WHERE date='$date' AND lesson=$num;";
		mysqli_query($this->link, $query);
	}

	public function C_echo($str)
	{
		echo("<script>console.log('$str');</script>");
	}

	public function addNote($date, $num, $note)
	{
		$query = "INSERT INTO `lanikjo`.`tablet_note` (`date`, `lesson`, `note`) VALUES ('$date', '$num', '$note');";
		mysqli_query($this->link, $query);
	}

	public function maxInMonth($year, $month)
	{
		$start = "$year-$month-01";
		$end = "$year-$month-".sprintf('%02d', cal_days_in_month(CAL_GREGORIAN, $month, $year));
		$query = "SELECT length, lesson FROM $this->table_book WHERE date BETWEEN '$start' AND '$end' ORDER BY lesson ASC LIMIT 1;";
		$lesson = mysqli_fetch_array(mysqli_query($this->link, $query));
		$max = $lesson['lesson'];
		if($max == null) return -1;
		$max += $lesson['length'];
		return $max;
	}

}

?>
