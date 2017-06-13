<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<!-- (3xtextarea - 1. za main, 2. text zadatka, 3. output, submit button) -->
<form action="<?php __SITE_URL; ?>/<!!!!!!!!>.php?rt=zadatak/NoviZadatak" method="post">

	<h2>main()</h2>
	<textarea value="mainUnos" class="mainUnos" placeholder="Ovdje unesite svoj kôd ( main() funkciju )"></textarea>

	<h2>Tekst zadatka</h2>
	<textarea value="tekstZadatka" class="tekstZadatka" placeholder="Ovdje unesite tekst zadatka"></textarea>

	<h2>Traženi output</h2>
	<textarea value="output" class="output" placeholder="Ovdje unesite traženi output"></textarea>

	<button type="submit">Pošalji!</button>

</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>

