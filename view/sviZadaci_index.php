<?php require_once __SITE_PATH . '/view/_header.php';
// (samo zadaci i ponudi se koriniku da rješava) (ako se uspješno postavio ili riješio zadatak ispiši to)

// Ispisati ako je potrebno na pocetku dokumenta poruku (tj ako je isset) preko var $poruka
if(isset($poruka)){
	echo '<p class="poruka">' . $poruka . '</p>';
}
?>

<h2>Ponuđeni zadaci</h2>
<table class="ponudaZadataka">

<?php
	// Lista ponuđenih zadataka
	echo '<form action="' . __DIR__ . 'zadatak_index.php' . '" method="post">';
?>
	<tr>
		<th>ID</th>
		<th>Naslov zadatka</th>
	</tr>
<?php
		foreach( $zadatakList as $row ){
			echo '<tr>' .
			     '<td>' . $row->id . '</td>' .
			     // naslovZadatka je kraći opis zadatka.
			     // Glavni opis, ono što se traži od korisnika, će biti ispisan u zadatak.php
			     '<td>' . $row->naslovZadatka . '</td>' .
			     '<td><button type="submit" name="'. $row->id .'">Odaberi!</button></td>' .
			     '</tr>';
		}
?>
	</form>
</table>

<?php  require_once __SITE_PATH . '/view/_footer.php'; ?>
