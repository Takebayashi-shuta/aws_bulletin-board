<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

if (isset($_POST['body'])) {

    $image_filename = null;
    
    // 45行目のname属性の修正: name="image" に
    if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
        
        // mime_content_type() で正確なMIMEタイプをチェックする
        $mime_type = mime_content_type($_FILES['image']['tmp_name']);
        if (preg_match('/^image\//', $mime_type) !== 1) {
            header("HTTP/1.1 302 Found");
            header("Location: ./bbsimagetest.php");
            return;
        }

        $pathinfo = pathinfo($_FILES['image']['name']);
        $extension = $pathinfo['extension'];
        $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
        $filepath = '/var/www/upload/image/' . $image_filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
    }
    
    // このブロックを正しい位置に移動
    $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body, image_filename) VALUES (:body, :image_filename)");
    $insert_sth->execute([
        ':body' => $_POST['body'],
        ':image_filename' => $image_filename,
    ]);

    header("HTTP/1.1 302 Found");
    header("Location: ./bbsimagetest.php");
    return;
}

$select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
$select_sth->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>画像投稿できる掲示板</title>
</head>
<body>
    <form method="POST" action="./bbsimagetest.php" enctype="multipart/form-data">
        <textarea name="body" required></textarea>
        <div style="margin: 1em 0;">
            <input type="file" accept="image/*" name="image" id="imageInput">
        </div>
        <button type="submit">送信</button>
    </form>

    <hr>

    <?php foreach ($select_sth as $entry): ?>
        <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
            <dt>ID</dt>
            <dd><?= $entry['id'] ?></dd>
            <dt>日時</dt>
            <dd><?= $entry['created_at'] ?></dd>
            <dt>内容</dt>
            <dd>
                <?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?>
                <?php if (!empty($entry['image_filename'])): // 画像がある場合は img 要素を使って表示 ?>
                    <div>
                        <img src="/image/<?= htmlspecialchars($entry['image_filename']) ?>" style="max-height: 10em;">
                    </div>
                <?php endif; ?>
            </dd>
        </dl>
    <?php endforeach ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const imageInput = document.getElementById("imageInput");
            imageInput.addEventListener("change", () => {
                if (imageInput.files.length < 1) {
                    return;
                }
                // ファイルサイズチェック
                if (imageInput.files[0].size > 5 * 1024 * 1024) {
                    alert("5MB以下のファイルを選択してください。");
                    imageInput.value = "";
                }
            });
        });
    </script>
</body>
</html>
