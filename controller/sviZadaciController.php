
<?php

class sviZadaciController extends BaseController
{
	public function index()
	{
    $es = new EvaluatorService();
    $this->registry->template->zadatakList = $es->UzmiSveZadatke();
    $this->registry->template->show( 'sviZadaci_index' );
	}
};

?>
