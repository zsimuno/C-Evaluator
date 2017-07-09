<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<!-- (veliki textarea, button "Pokreni",
 log (tablica sa outputom ili greškama u kodu),
 text zadatka, ispisati "Uspješno rješeno" ako je uspješno rješeno) -->
<?php
//output ce biti spremljen u varijablu $output al uvijek prvo provjerit isset(output)
//tekst zadatka se šalje preko varijable $zadatak

// Ako je uspješno rješeno
if(isset($uspjeh)){
	echo '<p class="uspjeh">' . $uspjeh . '</p>';
}

// Tekst zadatka
if(isset($zadatak)){
	echo '<p class="opisZadatka">' . $zadatak->tekstZadatka . '</p>';
}

// Modificirati action forme
?>
<form action="<?php echo __SITE_URL; ?>/index.php?rt=zadatak/ProvjeriZadatak
														&id=<?php echo $zadatak->id; ?>" method="post">

	<h2>Kôd</h2>

	<textarea name="kod" class="kod" placeholder="Ovdje unesite svoj kôd"></textarea>
	<br/>
	<button class="posaljiZadatak" type="button">Pokreni!</button>

</form>
<br>


<!-- log (output zadatka / greške), na početku prazno -->
<p class="log"></p><br>

<?php
 require_once __SITE_PATH . '/view/_footer.php'; ?>
