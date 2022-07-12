<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="HandheldFriendly" content="True">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.6, user-scalable=yes">
    <title>Сократитель ссылок</title>
    <meta name="description" content="Сократитель ссылок">
    <link rel="stylesheet" href="/public/css/main.css">
</head>
<body>
    <div class="wrapper">
        <div class="main">

            <form method="post" action="javascript:getURL()">
                <div>
                    <label class="label">Введите вашу ссылку:</label>
                    <input type="text" name="url" required placeholder="Введите новую ссылку">
                </div>
                <button name="submit" type="submit">Получить ссылку</button>
            </form>

            <div id="error"></div>
            <div id="show">

                <div class="showOriginal">Для вашей ссылки:</div>
                <div id="showOriginal"></div>
                <div class="showNew">создан короткий адрес:</div>
                <div id="showNew"></div>

            </div>
        </div>
    </div>

    <script src='/public/js/main.js'></script>

</body>
</html>