<?php
/* ProvjeriZadatak() - Prima rješenje zadatka i šalje ga modelu*/

class ZadatakController extends BaseController
{

	/*preko get dobiva id zadatka kojeg treba otvorit i uzima ga iz baze*/
	public function index()
	{
		$es = new EvaluatorService();

    if (isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id'])) //provjerit da je dobrog oblika
    {
      $id = $_GET['id'];
    }
    else {
      header('Location: ' . __SITE_URL . '/index.php?rt=sviZadaci' );
			exit();
    }

    $this->registry->template->zadatak = $es->UzmiZadatakPrekoId($id);
    $this->registry->template->show( 'zadatak_index' );
	}

	function ProvjeriZadatak()
  {
		$es = new EvaluatorService();

    if (isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id']))     {
      $id = $_GET['id'];
    }
    else {
			/*ako neki korisnik pokušava pristupiti zadatku koji ne postoji*/
      header('location: '.__SITE_URL.'/index.php');
			exit();
    }
    /*ako je poslan 'prazan kod' */
		if (!(isset($_POST['kod']) && !empty($_POST['kod']))){
			$this->registry->template->poruka = "Nije unesen kod!";
			$this->registry->template->zadatak = $es->UzmiZadatakPrekoId($id);
			$this->registry->template->show( 'zadatak_index' );
			return;
		}
		$kod = $_POST['kod'];
		/*ProvjeriRjesenje($id, $kod) za sada implementirana da vraća output;
		pospremam ga u template i ostavljam na istoj str koja će taj output ispisati*/
		$this->registry->template->output = $es->ProvjeriRjesenje($id, $kod);
		$this->registry->template->show( 'zadatak_index' );
  }

};

?>

