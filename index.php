<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="radio"], select, input[type="number"] {
            margin-bottom: 10px;
        }
    </style>
    <title>Automat Pepsi</title>
</head>
<body>

    <h2>Automat Pepsi</h2>
    <form action="" method="post">
        Pepsi (3,5zł): <input type="radio" name="opcja" value="pepsi"><br>
        Mirinda (5zł): <input type="radio" name="opcja" value="mirinda"><br>
        Pepsi Max (6zł): <input type="radio" name="opcja" value="pepsi_max"><br>

        <label for="platnosci">Dostępne monety:</label><br>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ceny napojów w groszach
    $ceny = array(
        "pepsi" => 350,    // Cena w groszach (3.50 zł * 100)
        "mirinda" => 500,  // Cena w groszach (5.00 zł * 100)
        "pepsi_max" => 600 // Cena w groszach (6.00 zł * 100)
    );

    // Wybrany napój
    $wybrany_napoj = isset($_POST["opcja"]) ? $_POST["opcja"] : "";
    $cena_napoju = isset($ceny[$wybrany_napoj]) ? $ceny[$wybrany_napoj] : 0;

    // Obliczenia dla wybranej opcji
    $platnosci = isset($_POST["platnosci"]) ? $_POST["platnosci"] : array();

    // Wartość wprowadzonych monet w groszach
    $wartosc_wprowadzonych_monet = 0;

    foreach ($platnosci as $wartosc => $ilosc) {
        $wartosc_wprowadzonych_monet += $wartosc * 100 * $ilosc; // Zamiana zł na grosze
    }

    // Sprawdzenie czy użytkownik zapłacił odpowiednią kwotę
    if ($wartosc_wprowadzonych_monet >= $cena_napoju) {
        // Reszta do wydania
        $reszta = $wartosc_wprowadzonych_monet - $cena_napoju;

        // Tablica dostępnych nominałów w automacie
        $dostepne_nominaly = array(500, 200, 100, 50, 20, 10, 5, 2, 1);

        // Wydawanie reszty
        $wydane_monety = wydajReszte($reszta, $dostepne_nominaly);

        // Tutaj możesz dodać kod obsługujący bazę danych, np. zapisujący dane o monetach

        // Wyświetlanie wyników
        echo "<p>Wybrany napój: $wybrany_napoj</p>";
        echo "<p>Cena napoju: " . number_format($cena_napoju / 100, 2) . " zł</p>";
        echo "<p>Wprowadzone monety: " . number_format($wartosc_wprowadzonych_monet / 100, 2) . " zł</p>";

        if ($reszta > 0) {
            echo "<p>Reszta: " . number_format($reszta / 100, 2) . " zł</p>";
            echo "<p>Wydane monety:</p>";
            echo "<ul>";

            foreach ($wydane_monety as $nominal => $ilosc) {
                echo "<li>$ilosc x " . number_format($nominal / 100, 2) . " zł</li>";
            }

            echo "</ul>";
        } else {
            echo "<p>Reszta nie została wydana.</p>";
        }
    } else {
        echo "<p>Niewystarczająca ilość środków na zakup napoju. Proszę wprowadzić odpowiednią kwotę.</p>";
    }
}

function wydajReszte($reszta, $dostepne_nominaly)
{
    $wydane_monety = array();

    foreach ($dostepne_nominaly as $nominal) {
        $ilosc_nominalow_do_wydania = floor($reszta / $nominal);

        if ($ilosc_nominalow_do_wydania > 0) {
            $wydane_monety[$nominal] = $ilosc_nominalow_do_wydania;
            $reszta -= $ilosc_nominalow_do_wydania * $nominal;
        }
    }

    return $wydane_monety;
}
?>












</body>
</html>
