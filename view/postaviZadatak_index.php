<?php require_once __SITE_PATH . '/view/_header.php'; ?>
<!-- (3xtextarea - 1. za main, 2. text zadatka, 3. output, submit button) -->
<form action="<?php __SITE_URL; ?>/<!!!!!!!!>.php?rt=zadatak/NoviZadatak" method="post">

	<textarea value="mainUnos" class="mainUnos">
		Ovdje unesite svoj kôd ( main() funkciju )
	</textarea>

	<textarea value="tekstZadatka" class="tekstZadatka">
		Ovdje unesite tekst zadatka
	</textarea>

	<textarea value="output" class="output">
		Ovdje unesite traženi output
	</textarea>

	<button type="submit">Pošalji!</button>

</form>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
