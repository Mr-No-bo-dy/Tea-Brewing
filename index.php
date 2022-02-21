<?php
    session_start();
    // session_destroy();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заварювання Чаю - Головна Сторінка</title>
</head>
<body>
    <form action="index.php" method="post">
        <p>Кількість чаю:</p><input type="range" name="waterOrder" value="0" min="50" max="1000" step="50" oninput="this.nextElementSibling.value = this.value">
        <input type="text" value="0" oninput="this.previousElementSibling.value = this.value">
        <p>Кількість цукру:</p><input type="range" name="sugarOrder" value="0" min="0" max="10" step="0.5" oninput="this.nextElementSibling.value = this.value">
        <input type="text" value="0" oninput="this.previousElementSibling.value = this.value">
        <p>Міцність чаю:</p>
            <label><input type="radio" name="timeNorm" value="12">Слабкий</label>
            <label><input type="radio" name="timeNorm" value="24" checked>Середній</label>
            <label><input type="radio" name="timeNorm" value="36">Міцний</label>
        <p>Тара:</p>
        <select name="cup">
            <option value="150">Долоні</option>
            <option value="250">Пластиковий стаканчик</option>
            <option value="350" selected>Чашка</option>
            <option value="500">Термос</option>
        </select>
        <p><input type="submit" value="Замовити"></p>
        <?php
            if (!isset ($_SESSION["costOrder"])) {
                $costOrder = $_SESSION["costOrder"] = 0;
            }
            if (isset ($_POST["waterOrder"])) {
                // var_dump($_POST);
                // echo ("<br>");
                $waterCup = 0;
                $sugarCup = 0;
                $timeCup = 0;
                $waterOrder = $_POST["waterOrder"];
                $sugarOrder = $_POST["sugarOrder"];
                $sugarNorm = $sugarOrder / ($waterOrder / 50);
                $timeNorm = $_POST["timeNorm"];
                $cup = $_POST["cup"];

                // Розрахунок вартості замовлення
                $costWater = 10 * 2;    // Ціна за 50 мл Води (одне наливання): 2 грн / 1л Води     x2
                $costSugar = 13 * 2;    // Ціна за 0.5 ч.л. Цукру: 26 грн / 1 кг Цукру              x2
                $costWaterTotal = $waterOrder / 50 * $costWater;    // Сума за всю замовлену Воду
                $costSugarTotal = $sugarOrder / 0.5 * $costSugar;    // Сума за весь замовленний Цукор
                $costTimeTotal = $waterOrder * 2 / 50 * $timeNorm / 2.4;    // Сума за все Листя чаю (Міцність чаю)     x2

                // + Податок на Пластик
                if ($waterOrder <= 250) {
                    $taxPlastic = 25;
                } else if ($waterOrder <= 500) {
                    $taxPlastic = 50;
                } else if ($waterOrder <= 750) {
                    $taxPlastic = 75;
                } else {
                    $taxPlastic = 100;
                }

                // = Вартість всього Замовлення
                if ($cup != 250) {
                    $costOrder = ($costWaterTotal + $costSugarTotal + $costTimeTotal) / 100;
                } else {
                    $costOrder = ($costWaterTotal + $costSugarTotal + $costTimeTotal + $taxPlastic) / 100;
                }

                // Основна частина коду
                if ($cup == 150 && $waterOrder > $cup) {
                    echo ("<p>Лише ваших долонь не достатньо. Позвіть друзів!</p>");
                } else {
                    while ($waterOrder > 0) {
                        while ($waterOrder > 0 && $waterCup < $cup) {
                            $waterOrder -= 50;
                            $waterCup += 50;
                            $sugarCup += $sugarNorm;
                            $timeCup += $timeNorm;
                            echo ("<p>Води в чашці ".$waterCup." мл.</p>");
                        }
                        echo ("<p>Додати ".round($sugarCup, 1)." ложки цукру.</p>");
                        echo ("<p>Заварювати чай ".$timeCup." секунд.</p>");
                        $waterCup = 0;
                        $sugarCup = 0;
                        $timeCup = 0;
                    }
                }
                $_SESSION["costOrder"] = $costOrder;
                echo ("<p><b>Вартість вашого замовлення: ".$costOrder." грн.</b></p>");
            } else {
                echo ("<p>Зробіть замовлення.</p>");
            }
            $waterOrder = $_POST["waterOrder"] = 0;
        ?>
    </form>
    <form action="pay.php" method="post">
        <p><b><input type="submit" value="Перейти до оплати"></b></p>
    </form>
</body>
</html>