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
      $st = $db->prepare( 'SELECT id, username, password FROM users ORDER BY username' );
      $st->execute();
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

    $arr = array();
    while( $row = $st->fetch() )
    {
      $arr[] = new User( $row['id'], $row['username'], $row['password'] );
    }

    return $arr;
  }

  //Svi naslovi zadataka i njihov id (Zasad bez tekstova jer je ovo samo za index stranicu)
  function UzmiSveZadatke()
  {

  }


  // Dohvati samo tekst zadatka koji treba ispisat bilo na stranici samog zadatka
  function UzmiZadatakPrekoId($id)
  {

  }

  function ProvjeriRjesenje($idZadatka, $kod) // mozda u controlleru exec a ovdje samo output
  {

  }

 //(za ubacivanje novih zadataka) prima tekst_zadatka, main_datoteka, output od controllera
//	 i sprema u bazu i stvara novu main datoteku i sprema lokalno (ime maina = id_zadatka_u_bazi.c)
  function UbaciZadatak($main, $tekstZadatka, $output)
  {

  }

};


 ?>
