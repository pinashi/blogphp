<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать статью</title>
</head>
<body>
    <a href="/post/<?= $post['id'] ?>">← Назад</a>
    <h1>Редактировать статью</h1>

    <form method="POST" action="/post/<?= $post['id'] ?>/edit">
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>
        <textarea name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>
        <button type="submit">Сохранить</button>
    </form>

    <form method="POST" action="/post/<?= $post['id'] ?>/delete">
        <button type="submit" onclick="return confirm('Удалить статью?')">Удалить</button>
    </form>
</body>
</html>