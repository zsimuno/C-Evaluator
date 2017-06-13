<?php
/*
- NoviZadatak()
    - Prima tekst zadatka, main, output i šalje modelu
      i vraća "Uspješno postavljeno" preko template-a (vraća na index)
- AdminLogin()
    - prima login podatke i šalje modelu
      i vraća poruku jel uspješan (šalji na postavljanje zadatka)
      il neuspješan login (vrati na login-page)
*/

class adminController extends BaseController
{

  public function index()
	{
    $this->registry->template->show( 'login_index' );
  }

  function login()
  {

    if( ! ( isset($_POST['username']) && isset($_POST['password'])
            && !empty($_POST['username']) && !empty($_POST['password']) ) )
       {
         $this->registry->template->message = "Neuspješan login";
         $this->registry->template->show( 'login_index' );
         return;
       }

    $fs = new EvaluatorService();
    $users = $fs -> UzmiSveAdmine();

    foreach ($users as $user) {
      if($user->username === $_POST['username'] && password_verify( $_POST['password' ], $user->password ))
      {
        $_SESSION['user'] = array('id' => $user->id,'username' => $user->username);
        break;
      }
    }

    // Ako nije uspjesan login onda vrati na login sa porukom da je neuspjesan
    if(!isset($_SESSION['user']))
    {
      $this->registry->template->poruka = "Neuspješan login";
      $this->registry->template->show( 'login_index' );
      return;
    }
    header( 'Location: ' . __SITE_URL . '/index.php' );
  }

  // Unisti session i vrati na pocetnu stranicu
  function logout()
  {
    session_unset();
    session_destroy();
    header( 'Location: ' . __SITE_URL . '/index.php' );
  }

//  Prima tekst zadatka, main, output i šalje modelu
//    i vraća "Uspješno postavljeno" preko template-a (vraća na index)

  function NoviZadatak()
  {
    //primi preko post tekst_zadatka i output i posalji to modelu preko

    $fs = new EvaluatorService();
    $fs->UbaciZadatak($main, $tekst_zadatka, $output)

    // kad završi ubacivanje treba UzmiSveZadatke i to spremit u $zadatakList
    // i u $poruka spremit "Uspješno ubacivanje zadatka"
  }

};
