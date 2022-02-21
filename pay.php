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
    <title>Заварювання Чаю - Оплата Замовлення</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        // Ініціація/Оголошення Змінних
        $costOrder = $_SESSION["costOrder"];
        echo ("<p><b>Вартість вашого замовлення: ".$costOrder." грн.</b></p>");
        if (!isset ($_SESSION["balance"])) {
            $balance = $_POST["balance"] = $_SESSION["balance"] = 0;
        } else {
            $balance = $_POST["balance"] = $_SESSION["balance"];
        }
        if (!isset ($_POST["payment"])) {
            $payment = 0;
        } else {
            $payment = $_POST["payment"];
        }
        if (!isset ($_POST["payback"])) {
            $payback = 0;
        }
        if (!isset ($_POST["balancePay"])) {
            $_POST["balancePay"] = 0;
        }
        if (!isset ($_POST["balanceReset"])) {
            $_POST["balanceReset"] = 0;
        }
        if (!isset ($_POST["currency"])) {
            $_POST["currency"] = 0;
        }
        ?>
    
    <!-- Кнопки оплати -->
    <form action="pay.php" method="post">
        <button type="submit" name="balancePay" value="1" <?php
        if ($balance < $costOrder) {
            echo ("disabled");
        }
        ?>>Оплатити з рахунку</button>
        <p>
            <button type="button" name="uah" value="1" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.nextElementSibling.checked = true">1 Грн</button>
            <button type="button" name="uah" value="10" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.previousElementSibling.checked = true">10 Грн</button>
        </p>
        <p>
            <button type="button" name="rub" value="3" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.nextElementSibling.checked = true">10 Рублів</button>
            <input type="checkbox" name="isRub">
            <button type="button" name="rub" value="30" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.previousElementSibling.checked = true">100 Рублів</button>
        </p>
        <p>
            <button type="button" name="usd" value="28" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.nextElementSibling.checked = true">1 Долар</button>
            <input type="checkbox" name="isUsd">
            <button type="button" name="usd" value="56" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.previousElementSibling.checked = true">2 Долара</button>
        </p>
        <p>
            <button type="button" name="eur" value="32" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.nextElementSibling.checked = true">1 Євро</button>
            <input type="checkbox" name="isEur">
            <button type="button" name="eur" value="64" onclick="idPay.value = parseInt(idPay.value) + parseInt(this.value); this.previousElementSibling.checked = true">2 Євро</button>
        </p>
        <input type="text" name="payment" value="0" id="idPay" readonly>
        <button type="submit" name="currency" value="1" id="idCurrency">Оплатити</button>
        
        <!-- Перевірка, якою валютою здійснюється оплата. -->
        <?php
            if (!isset ($_POST["isRub"])) {
                $isRub = 0;
            } else {
                $isRub = $_POST["isRub"];
            }
            if (!isset ($_POST["isUsd"])) {
                $isUsd = 0;
            } else {
                $isUsd = $_POST["isUsd"];
            }
            if (!isset ($_POST["isEur"])) {
                $isEur = 0;
            } else {
                $isEur = $_POST["isEur"];
            }

            // echo ("<br>");
            // var_dump($_POST);
            // echo ("<br>");
            // var_dump($_SESSION);
            // echo ("<br>");

            // Комісія за іноз. валюту
            if (isset ($_POST["isEur"]) || isset ($_POST["isUsd"])) {
                $costOrder = round($costOrder * 1.2, 2);
                if ($costOrder != 0) {
                    echo ("<p>Стягнута комісія за конвертацію іноземної валюти у розмірі 20% замовлення.</p>");
                }
            }

            // Основний код оплати
            if ($_POST["balancePay"] != 0 || $_POST["currency"] != 0) {
                if (!isset ($_POST["isRub"])) {
                    if ($costOrder != 0) {
                        if ($payment > 0 && $payment >= $costOrder) {
                            $payback = $payment - $costOrder;
                            $balance += $payback;
                            $costOrder = $_SESSION["costOrder"]  = 0;
                            echo ("<p><b>Дякуємо за покупку. Смачного чаювання!</b></p>");
                        } else if ($payment == 0 && $balance >= $costOrder) {
                            $balance -= $costOrder;
                            $costOrder = $_SESSION["costOrder"]  = 0;
                            echo ("<p><b>Оплачено з рахунку. Смачного чаювання!</b></p>");
                        } else {
                            echo ("<p><b>Недостатньо коштів для оплати замовлення.</b></p>");
                        }
                    } else {
                        echo ("<p><b>Зробіть замовлення.</b></p>");
                    }
                } else {
                    echo ("<p><b>Ми не приймаємо гроші окупантів!</b></p>");
                }
            }
        ?>

        <!-- Обнулення і Відображення Балансу -->
        <p><button type="submit" name="balanceReset" value="1" <?php
            if ($balance == 0) {
                echo ("disabled");
            }
            ?>>Пожертвувати заощадження на благодійність</button></p>
        <?php
            if ($_POST["balanceReset"] != 0) {
                $balance = 0;
                echo ("<p><b>Дякуємо! Ви зробили цей світ трішки кращим!</b></p>");
            }
            echo ("<p>У вас на рахунку залишилось ".$balance." грн.</p>");
            $_SESSION["balance"] = $balance;
        ?>
    </form>
    <form action="index.php" method="post">
        <input type="submit" value="Повернутись до замовлення">
    </form>
</body>
</html>