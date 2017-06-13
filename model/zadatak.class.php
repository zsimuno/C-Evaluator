<?php

class Zadatak
{
	protected $id, $naslovZadatka, $tekstZadatka, $output;

	function __construct( $id, $naslovZadatka, $tekst_zadatka, $output )
	{
		$this->id = $id;
		$this->naslovZadatka = $naslovZadatka;
		$this->tekstZadatka = $tekstZadatka;
    $this->output = $output;
	}

	function __get( $prop ) { return $this->$prop; }
	function __set( $prop, $val ) { $this->$prop = $val; return $this; }
}

?>
