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
      $arr[] = new Zadatak( $row['id'], $row['naslovZadatka'], $row['tekstZadatka'], $row['output']);
    }

    return $arr;
  }


  // Dohvati samo tekst zadatka koji treba ispisat bilo na stranici samog zadatka
  function UzmiZadatakPrekoId($id)
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT * FROM Zadaci WHERE id=:id' );
      $st->execute( array( 'id' => $id ) );
    }
    catch( PDOException $e ) { exit( 'PDO error #3' . $e->getMessage() ); }

      $row = $st->fetch();
      $tekst = new Zadatak( $row['id'], $row['naslovZadatka'], $row['tekstZadatka'], $row['output']);
      if($st->rowCount() !== 1) return 0; //ne postoji taj zadatak

      return $tekst;
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

      $id = $st->fetch()["id"];

      $include = "#include \"".$id.".h\" ";             //odgovrajući include

      file_put_contents(__DIR__ ."/main/".$id.".c", $include);           //stavljamo odgovorajući include
      file_put_contents(__DIR__ ."/main/".$id.".c", "\n", FILE_APPEND);   //na to nadodjemo novi red i main
      file_put_contents(__DIR__ ."/main/".$id.".c", $main, FILE_APPEND);

  }

  function ProvjeriRjesenje($idZadatka, $kod) // mozda u controlleru exec a ovdje samo output
  {
    $main = file_get_contents(__DIR__.'/main/'.$idZadatka . '.c');

    //random niz znakova za naziv datoteke (ako zeli vise korisnika u istom trenutku rjesavat zadatke)
    // Možda pametnije koristiti lokote (kod za lokote u komentarima na dnu file-a)
    // $ime_datoteke = '';
		// for( $i = 0; $i < 20; ++$i )
		// 	$ime_datoteke .= chr( rand(0, 25) + ord( 'a' ) );
    file_put_contents(__DIR__ . '/main/'. $idZadatka . ".h", $kod);

    $output = shell_exec('gcc '.__DIR__.'/main/'.$idZadatka.'.c -o '.__DIR__.'/main/'.$idZadatka);

    if( $output !== NULL ) //tu je bilo !empty($output) al bolje ovako
    {
      return $output;
    }
    else
    {
      // if(!chroot(getcwd())) //možda neće raditi jer nismo root korisnici
      //   return "Ne mogu postaviti dobar root. Pokušajte ponovno!";
      //postavit timeout !!! ulimit ne radi ako se ceka scanf (ulimit -t 3) preserve status mozda nije potreban?
      //mozda kotristiti proc_open i slicne
      //pitanje je hoće li ovaj chroot raditi s obzirom da nemamo root ovlasti (sudo?)
      $output = shell_exec(__DIR__ . '/main/'.$idZadatka. '.exe'); // na serveru ce vjerojatno bit ./$ime_datoteke

      if($output === NULL) return "Greška u pokretanju programa probajte ponovno";

      try
      {
        $db = DB::getConnection();
        $st = $db->prepare( 'SELECT output FROM Zadaci WHERE id=:id' );
        $st->execute( array( 'id' => $idZadatka ) );
      }
      catch( PDOException $e ) { exit( 'PDO error #5' . $e->getMessage() ); }

      $rjesenje_zad = $st->fetch()['output'];

      //briše file-ove nakon odrađenog posla
      // unlink($idZadatka.'.o');
      // unlink($idZadatka.'.exe');

      if($output === $rjesenje_zad) return "Rješenje je točno!"; //ili vratit jedinicu
      else return "Pogrešno rješenje!"; //Ili vratit nulu
    }

  }

};


 ?>
