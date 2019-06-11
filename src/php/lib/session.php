<?php
//
// Tablet
// Copyright (c) 2019 Jovan Lanik
//

class session {
	public $year;
	public $month;
	public $showAll;

	public function __construct()
	{
		if(isset($_SESSION['s_session']))
		{
			$this->year = $_SESSION['s_session']->year;
			$this->month = $_SESSION['s_session']->month;
			$this->showAll = $_SESSION['s_session']->showAll;
		}
		else
		{
			$this->year = date('Y');
			$this->month = date('m');
			$this->showAll = false;
			$_SESSION['s_session'] = $this;
		}
	}

}

?>
