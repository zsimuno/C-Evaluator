<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<!-- (3xtextarea - 1. za main, 2. text zadatka, 3. output, submit button) -->
<?php
if(isset($poruka)){
	echo '<p class="poruka">' . $poruka . '</p>';
}
?>
<form action="<?php echo __SITE_URL; ?>/index.php?rt=admin/NoviZadatak" method="post">
	<h2>Naslov</h2>
	<textarea name="naslov" class="naslov"
			placeholder="Ovdje unesite svoj naslov zadatka (neka bude kao kratki opis zadatka)"></textarea>

	<h2>main()</h2>
	<textarea name="mainUnos" class="mainUnos" placeholder="Ovdje unesite svoj kôd ( main() funkciju )"></textarea>

	<h2>Tekst zadatka</h2>
	<textarea name="tekstZadatka" class="tekstZadatka" placeholder="Ovdje unesite tekst zadatka"></textarea>

	<h2>Traženi output</h2>
	<textarea name="output" class="output" placeholder="Ovdje unesite traženi output"></textarea>
	<br>
	<button type="submit">Pošalji!</button>
	<br><br>

</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
