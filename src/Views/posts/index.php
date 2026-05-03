<?php
/** @var array $posts */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Блог</title>
</head>
<body>
    <header>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Привет, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
            <a href="/logout">Выйти</a>
            <a href="/post/create">Написать статью</a>
        <?php else: ?>
            <a href="/login">Войти</a>
            <a href="/register">Регистрация</a>
        <?php endif; ?>
    </header>

    <h1>Блог</h1>

    <?php foreach ($posts as $post): ?>
        <div>
            <h2>
                <a href="/post/<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
            </h2>
            <p><?= htmlspecialchars($post['content']) ?></p>
            <small><?= $post['created_at'] ?></small>
        </div>
        <hr>
    <?php endforeach; ?>

</body>
</html>