<?php
/** @var array $post
 *  @var array $comments
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
</head>
<body>

    <a href="/">← Назад</a>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
    <a href="/post/<?= $post['id'] ?>/edit">Редактировать</a>
    <?php endif; ?>

    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <small><?= $post['created_at'] ?></small>
    <p><?= htmlspecialchars($post['content']) ?></p>

    <hr>

    <h3>Комментарии (<?= count($comments) ?>)</h3>

    <?php if (empty($comments)): ?>
        <p>Комментариев пока нет</p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div>
                <strong><?= htmlspecialchars($comment['author']) ?></strong>
                <small><?= $comment['created_at'] ?></small>
                <p><?= htmlspecialchars($comment['text']) ?></p>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Оставить комментарий</h3>
    <form method="POST" action="/post/<?= $post['id'] ?>/comment">
        <textarea name="text" rows="4" placeholder="Ваш комментарий"></textarea>
        <br>
        <button type="submit">Отправить</button>
    </form>

</body>
</html>