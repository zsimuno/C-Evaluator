<?php

// Manualno inicijaliziramo bazu ako već nije.
require_once '../../model/db.class.php';

$db = DB::getConnection();

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Admini (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'username varchar(20) NOT NULL,' .
		'password varchar(255) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

echo "Napravio tablicu Admini.<br />";

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Zadaci (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'naslovZadatka varchar(50) NOT NULL,' .
		'tekstZadatka varchar(10000) NOT NULL,'.
		'output varchar(1000) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }

echo "Napravio tablicu Zadaci.<br />";




try
{
	$st = $db->prepare( 'INSERT INTO Admini(username, password) VALUES (:username, :password)' );

	$st->execute( array( 'username' => 'Pero', 'password' => password_hash( 'perinasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'username' => 'Mirko', 'password' => password_hash( 'mirkovasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'username' => 'Slavko', 'password' => password_hash( 'slavkovasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'username' => 'Ana', 'password' => password_hash( 'aninasifra', PASSWORD_DEFAULT ) ) );
	$st->execute( array( 'username' => 'Maja', 'password' => password_hash( 'majinasifra', PASSWORD_DEFAULT ) ) );
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

echo "Ubacio admine u tablicu Admini.<br />";


try
{
	$st = $db->prepare( 'INSERT INTO Zadaci(naslovZadatka, tekstZadatka, output ) '.
																		'VALUES (:naslovZadatka, :tekstZadatka, :output)' );

	$st->execute( array( 'naslovZadatka' => 'Obilni brojevi',
											 'tekstZadatka'  => 'Za broj n ∈ N kažemo da je obilan ako je strogo manji od zbroja svojih djelitelja (izuzevši njega samog).
											 										 Na primjer, 12 < 1 + 2 + 3 + 4 + 6 = 16, pa je 12 obilan broj, dok broj 16 > 1 + 2 + 4 + 8 = 15, nije obilan broj.
																					 Napišite funkciju void obilan(int n) koja prima k ∈ N,i ispisuje sve obilne brojeve manje ili jednake k.
																					 Neka između ispisanih brojeva bude jedan razmak(space).' ,
											 'output'        => '12 18 20 24 30 36 40 42 48 54 56 60 66 70 72 78 80 84 88 90 96 100'
										 ); //testni primjer obilan(100)

	$st->execute( array( 'naslovZadatka' => 'Eulerova funkcija' ,
											 'tekstZadatka'  => 'Za broj n ∈ N, Eulerova funkcija φ(n) definira se kao broj prirodnih brojeva u skupu {1, . . . , n} koji su relativno prosti s n.
											 										 Napisati funkciju int Euler(int n) koja će prima prirodni broj n i vraća vrijednost Eulerove funkcije od n.' ,
											 'output'        => '42 243 818'
										 ); //testni primjeri Euler(100) Euler(900) Euler(1233)

	$st->execute( array( 'naslovZadatka' => 'Potpun kvadrat' ,
											 'tekstZadatka'  => 'Napišite funkciju int potpun_kvadrat(int n) gram koja prima prirodni broj n i vraća najveći prirodni broj m, manji ili jednak n, sa sljedećim svojstvima:
											 										 broj m je potpun kvadrat nekog prirodnog broja, zadnja znamenka (jedinica) u dekadskom zapisu broja m je 9, pri dijeljenju
																					 broja m s 3 dobivamo ostatak 1. Ako takvog broja m nema, treba vratiti -1.
																					 Na primjer, za n = 200, rezultat je m = 169 = 13^2 . Najmanji broj s traženim svojstvima je 49 = 7^2 = 16 · 3 + 1.' ,
											 'output'        => '-1 289 529'
										 ); //testni primjeri potpun_kvadrat(10) potpun_kvadrat(500) potpun_kvadrat(1000)

}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }

echo "Ubacio zadatke u tablicu Zadaci.<br />";

?>
