<?php
//- ProvjeriZadatak() - Prima kod rješenja zadatka i šalje ga modelu
//i vraća korisniku uspješnost (vrati na index) ili neuspješnost zadatka (ispiši otuput ili greške ispod koda)

class ZadatakController extends BaseController
{

	//preko get dobiva id zadatka kojeg treba otvorit i uzima ga iz baze
	public function index()
	{
		//new EvaluatorService

    if (isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id'])) //provjerit da je dobrog oblika
    {
      $id = $_GET['id'];
    }
    else {
      header('Location: ' . __SITE_URL . '/index.php?rt=sviZadaci' );
			exit();
    }

		// Popuni template potrebnim podacima
    $this->registry->template->zadatak = $ls->UzmiZadatakPrekoId($id);
    $this->registry->template->show( 'zadatak_index' );
	}

	//Preko $_POST uzmi rješenje a preko $_GET uzmi id
	function ProvjeriZadatak()
  {
    if (isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id'])) //provjerit da je dobrog oblika
    {
      $id = $_GET['id'];
    }
    else {
			//ovo vraćanje služi samo ako neki korisnik pokušava pristupiti nekom zadatku koji ne postoji
      header('location: '.__SITE_URL.'/index.php');
			exit();
    }

    //posalji kod modelu i vrati output i ispisi ga

  }

};

?>
