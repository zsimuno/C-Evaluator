<?php
/**
 *
 */

/*
-Klasa koja prima podatke iz controlera (kod od rješenja) i kompajlira i pokreće program i uspoređuje output
 ili vraća greške ako nije dobar kod (NE ZABORAVIT STAVIT TIMEOUT zbog beskonačnih petlji) (ZVONIMIR NIDŽA)

-(za ubacivanje novih zadataka) prima tekst_zadatka, main_datoteka, output od controllera
 i sprema u bazu i stvara novu main datoteku i sprema lokalno (id foldera = id zadatka u bazi)

-prima username i password od administratora i uspoređuje sa podacima u bazi i vraća uspješan il neuspješan login
  */
class EvaluatorService
{

  //vraca sve admine u svrhu provjere login-a
  function UzmiSveAdmine()
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT * FROM Admini ORDER BY username' );
      $st->execute();
    }
    catch( PDOException $e ) { exit( 'PDO error #1' . $e->getMessage() ); }

    $arr = array();
    while( $row = $st->fetch() )
    {
      $arr[] = new Admin( $row['id'], $row['username'], $row['password'] );
    }

    return $arr;
  }

  //Svi naslovi zadataka i njihov id (Zasad bez tekstova jer je ovo samo za index stranicu)
  function UzmiSveZadatke()
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT * FROM Zadaci' );
      $st->execute();
    }
    catch( PDOException $e ) { exit( 'PDO error #2' . $e->getMessage() ); }

    $arr = array();
    while( $row = $st->fetch() )
    {
      $arr[] = new Zadatak( $row['id'], $row['naslovZadatka'], $row['tekstZadatka'], $row['output'])
    }

    return $arr;
  }


  // Dohvati samo tekst zadatka koji treba ispisat bilo na stranici samog zadatka
  function UzmiZadatakPrekoId($id)
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT tekstZadatka FROM Zadaci WHERE id=:id' );
      $st->execute( array( 'id' => $id ) );
    }
    catch( PDOException $e ) { exit( 'PDO error #3' . $e->getMessage() ); }

      $tekst = $st->fetch();
      if($st->rowCount() !== 1) return 0; //ne postoji taj zadatak

      return $tekst;
    }
  }



 //(za ubacivanje novih zadataka) prima tekst_zadatka, main_datoteka, output od controllera
//	 i sprema u bazu i stvara novu main datoteku i sprema lokalno (ime maina = id_zadatka_u_bazi.c)
  function UbaciZadatak($main, $naslovZadatka, $tekstZadatka, $output)
  {
    //ubacujemo u bazu novi zadatak
    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO Zadaci(naslovZadatka, tekstZadatka, output) VALUES (:naslovZadatka, :tekstZadatka, :output)' );
			$st->execute( array( 'naslovZadatka' => $naslovZadatka, 'tekstZadatka' => $tekstZadatka, 'output' => $output ) );
		}
		catch( PDOException $e ) { exit( 'PDO error #4 ' . $e->getMessage() ); }


  //uzimamo id novoubačenog zadatka iz baze
  try
  {
    $db = DB::getConnection();
    $st = $db->prepare( 'SELECT id FROM Zadaci WHERE naslovZadatka=:naslovZadatka' );
    $st->execute( array( 'naslovZadatka' => $naslovZadatka ) );
  }
  catch( PDOException $e ) { exit( 'PDO error #5' . $e->getMessage() ); }

    $id = $st->fetch();
  //radimo main datoteku
    file_put_contents( $id + ".c", $main);

  }
  
  function ProvjeriRjesenje($idZadatka, $kod) // mozda u controlleru exec a ovdje samo output
  {

  }
};


 ?>
