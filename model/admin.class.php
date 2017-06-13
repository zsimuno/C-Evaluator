<?php

class Admin
{
	protected $id, $username, $password ;

	function __construct( $id, $username, $password )
	{
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>
