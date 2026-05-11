<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая статья</title>
</head>
<body>
    <a href="/">← Назад</a>
    
    <?php if (!empty($errors)): ?>
    <ul style="color: red">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    
    <h1>Новая статья</h1>

    <form method="POST" action="/post/create">
        <input type="text" name="title" placeholder="Заголовок" required><br><br>
        <textarea name="content" rows="10" placeholder="Содержимое" required></textarea><br><br>
        <button type="submit">Опубликовать</button>
    </form>
</body>
</html>