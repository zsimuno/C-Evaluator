<?php

class adminController extends BaseController
{

  public function index()
	{
    $this->registry->template->show( 'login_index' );
  }

  function postaviZadatak()
  {
    $this->registry->template->show( 'postaviZadatak_index' );
  }

  /*login za administratore*/
  function login()
  {
    if( ! ( isset($_POST['username']) && isset($_POST['password'])
            && !empty($_POST['username']) && !empty($_POST['password']) ) )
       {
         $this->registry->template->show( 'login_index' );
         return;
       }

    /*user i pass uneseni, provjera ispravnosti; podaci o svim adminima
    se dohvaćaju iz klase EvaluatorService; ukoliko nađe match sprema podatke u session*/

    $es = new EvaluatorService();
    $users = $es -> UzmiSveAdmine();

    foreach ($users as $user) {
      if($user->username === $_POST['username'] && password_verify( $_POST['password' ], $user->password ))
      {
        $_SESSION['user'] = array('id' => $user->id,'username' => $user->username);
        break;
      }
    }

    /*Ako session nije postavljen preko templatea ispisuje poruku i vraća ponovno na login str*/
    if(!isset($_SESSION['user']))
    {
      $this->registry->template->poruka = "Neuspješan login";
      $this->registry->template->show( 'login_index' );
      return;
    }
    /*Inače (session postavljen) ispisuje poruku i vodi na str za postavljanje zadatka*/
    $this->registry->template->poruka = "Uspješan login";
    $this->registry->template->zadatakList = $es->UzmiSveZadatke();
    $this->registry->template->show( 'sviZadaci_index' );
  }

  /*Unisti session i vrati na pocetnu stranicu*/
  function logout()
  {
    session_unset();
    session_destroy();
    header( 'Location: ' . __SITE_URL . '/index.php' );
  }


/*prima podatke o novom zadatku, pomoću template-a šalje modelu na obradu (spremanje u bazu)*/
  function NoviZadatak()
  {
    /*sva polja forme moraju biti ispunjena*/
    if( ! ( isset($_POST['mainUnos']) && isset($_POST['tekstZadatka'])
            && isset($_POST['output']) && isset($_POST['naslov'])
            && !empty($_POST['mainUnos']) && !empty($_POST['tekstZadatka'])
            && !empty($_POST['output']) && !empty($_POST['naslov'])  ) )
       {
         $this->registry->template->poruka = "Za postavljanje zadatka potrebno je popuniti sva polja"
                .$_POST['tekstZadatka'].$_POST['mainUnos'].$_POST['output'].$_POST['naslov'];
         $this->registry->template->show( 'postaviZadatak_index' );
         return;
       }
    /*preko EvaluatorService klase spremamo novo uneseni zad u bazu, preko templeta
    ažuriramo listu svih zadataka za prikaz preko sviZadaci_index.php str (view)
    te vraćamo administratora na tu str*/
    $main = ($_POST['mainUnos']);
    $naslov =( $_POST['naslov']);
    $tekst_zadatka = ($_POST['tekstZadatka']);
    $output = ($_POST['output']);
    $es = new EvaluatorService();
    $es->UbaciZadatak($main, $naslov, $tekst_zadatka, $output);

    $this->registry->template->zadatakList = $es->UzmiSveZadatke();
    $this->registry->template->poruka = "Uspješno ubacivanje zadatka";
    $this->registry->template->show( 'sviZadaci_index' );

  }

};
