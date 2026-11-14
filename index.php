<?php
session_start();
require 'db.php';

$savedName = $_COOKIE['author_name'] ?? '';
$_SESSION['visits'] = ($_SESSION['visits'] ?? 0) + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
    } elseif (isset($_POST['clear_all'])) {
        $pdo->exec("DELETE FROM messages");
    } else {
        $author = $_POST['author'];
        $body = $_POST['body'];

        $stmt = $pdo->prepare("INSERT INTO messages (author, body) VALUES (?, ?)");
        $stmt->execute([$author, $body]);

        if (isset($_POST['remember'])) {
            setcookie('author_name', $author, time() + 60 * 60 * 24 * 30, '/');
        } else {
            setcookie('author_name', '', time() - 3600, '/');
        }
    }
    header('Location: index.php');
    exit;
}

$search = $_GET['search'] ?? '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE body ILIKE ? ORDER BY created_at DESC");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
}
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Class Wall</title>
</head>

<body>
    <h1>Class Wall</h1>
    <p>You've visited <?= (int)$_SESSION['visits'] ?> times.</p>

    <form method="POST" action="index.php">
        <label>Name:</label><br>
        <input type="text" name="author" value="<?= htmlspecialchars($savedName) ?>"><br><br>

        <label>Message:</label><br>
        <textarea name="body"></textarea><br><br>

        <input type="checkbox" name="remember" id="remember" <?= $savedName !== '' ? 'checked' : '' ?>>
        <label for="remember">Remember my name</label><br><br>

        <button type="submit">Post</button>
    </form>

    <form method="GET">
        <input type="text" name="search" placeholder="Search messages" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <a href="index.php">Show all</a>
    </form>

    <h2>Messages</h2>
    <form method="POST" onsubmit="return confirm('Delete ALL messages?')">
        <button type="submit" name="clear_all">Clear all</button>
    </form>

    <?php if (empty($messages)): ?>
        <?php if ($search !== ''): ?>
            <p>No messages found for "<?= htmlspecialchars($search) ?>".</p>
        <?php else: ?>
            <p>No messages yet. Be the first to post!</p>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($messages as $row): ?>
            <div>
                <strong><?= htmlspecialchars($row['author']) ?></strong>
                <small><?= htmlspecialchars($row['created_at']) ?></small>
                <p><?= htmlspecialchars($row['body']) ?></p>
            </div>
            <form method="POST" style="display:inline">
                <input type="hidden" name="delete_id" value="<?= (int)$row['id'] ?>">
                <button type="submit">Delete</button>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>