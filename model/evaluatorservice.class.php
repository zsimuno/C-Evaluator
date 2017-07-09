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
    //Otvaramo .h datoteku
    $f = fopen( __DIR__ . '/main/'. $idZadatka . ".h", 'w' );

    // Pokušaj dobiti ekskluzivni lokot.
    if( flock( $f, LOCK_EX ) )
    {
      // Dobili smo lokot. Sada smijemo zapisati nešto u datoteku.
      file_put_contents(__DIR__ . '/main/'. $idZadatka . ".h", $kod);

      // Kompajliranje
      exec('gcc '.__DIR__.'/main/'.$idZadatka.'.c -o '.__DIR__.'/main/'.$idZadatka. ' 2>&1', $output);

      if( !empty($output) ) // Vraca li kompajler output?
      {
        if(file_exists(__DIR__ . '/main/'.$idZadatka.'.exe'))
          unlink(__DIR__ . '/main/'.$idZadatka.'.exe');

        // Prije otključavanja, treba napraviti fflush.
        fflush( $f );
        // Otključamo lokot.
        flock( $f, LOCK_UN );
        // Zatvorimo datoteku.
        fclose( $f );

        return implode("<br>", $output);
      }
      else
      {
        // Pokretanje programa (Timeout od 3 sekunde)
        $output = shell_exec('timeout 3s '.__DIR__ . '/main/'.$idZadatka);

        if($output === NULL) // Program nema outputa
        {
          if(file_exists(__DIR__ . '/main/'.$idZadatka.'.exe'))
            unlink(__DIR__ . '/main/'.$idZadatka.'.exe');

          // Prije otključavanja, treba napraviti fflush.
          fflush( $f );
          // Otključamo lokot.
          flock( $f, LOCK_UN );
          // Zatvorimo datoteku.
          fclose( $f );

          return "Greška u pokretanju programa probajte ponovno".
                 "<br>(Do ovoga može doći ako imate beskonačnih petlji u programu).<br> "
                 .__DIR__ . '/main/'.$idZadatka.'.exe';
        }

        try
        {
          $db = DB::getConnection();
          $st = $db->prepare( 'SELECT output FROM Zadaci WHERE id=:id' );
          $st->execute( array( 'id' => $idZadatka ) );
        }
        catch( PDOException $e ) { exit( 'PDO error #5' . $e->getMessage() ); }

        $rjesenje_zad = $st->fetch()['output'];

        if(file_exists(__DIR__ . '/main/'.$idZadatka.'.exe'))
          unlink(__DIR__ . '/main/'.$idZadatka.'.exe');

        // Prije otključavanja, treba napraviti fflush.
        fflush( $f );
        // Otključamo lokot.
        flock( $f, LOCK_UN );
        // Zatvorimo datoteku.
        fclose( $f );

        if($output === $rjesenje_zad) return "Rješenje je točno!";
        else return "Pogrešno rješenje! <br>Output je: <br>".$output;
      }
    }
    else
     return "Problem sa datotekama!" ;

  }

};


 ?>
