# PHP 掲示板アプリ

このリポジトリは、PHP と MySQL を使用したシンプルな掲示板アプリです。  
画像アップロード機能、削除パスワード機能、ページネーション、レスアンカー機能を備えています。

---

## 📌 機能一覧

- 投稿機能
  - 本文、画像、削除用パスワードを投稿可能
- 画像アップロード
  - 画像を `/var/www/upload/image/` に保存
  - 投稿時に 5MB を超える画像は自動的に縮小
- 投稿削除機能
  - 投稿時に設定したパスワードで削除可能
- ページネーション
  - 1 ページ 10 件表示
- レスアンカー機能
  - `>>ID` 形式で他の投稿にリンク可能

---

## 🛠️ 必要環境

- PHP 8.0 以上
- MySQL 5.7 以上
- Web サーバー（Apache または Nginx 推奨）

---

## 📂 ディレクトリ構成

project-root/
├── kadai.php # 掲示板本体
├── upload/
│ └── image/ # アップロード画像の保存先
└── README.md

---

## ⚙️ データベース構成

### bbs_entries（投稿データ）

| カラム名          | 型           | 説明           |
| ----------------- | ------------ | -------------- |
| id                | INT(11) PK   | 投稿 ID        |
| body              | TEXT         | 投稿本文       |
| image_filename    | VARCHAR(255) | 画像ファイル名 |
| delete_password   | VARCHAR(255) | 削除用パスワード（ハッシュ化） |
| created_at        | DATETIME     | 投稿日時       |

### deleted_entries（削除済み投稿）

| カラム名   | 型         | 説明         |
| ---------- | ---------- | ------------ |
| id         | INT(11)    | 削除した投稿 ID |
| deleted_at | DATETIME   | 削除日時     |

---

## 🚀 セットアップ手順

1. **リポジトリをクローン**
   ```bash
   git clone https://github.com/ユーザー名/リポジトリ名.git
   cd リポジトリ名
データベースを作成

CREATE DATABASE example_db DEFAULT CHARACTER SET utf8mb4;


テーブルを作成

CREATE TABLE bbs_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    body TEXT NOT NULL,
    image_filename VARCHAR(255),
    delete_password VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE deleted_entries (
    id INT NOT NULL,
    deleted_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


画像アップロード用ディレクトリを作成

mkdir -p /var/www/upload/image
chmod 777 /var/www/upload/image


Web サーバーを起動

php -S localhost:8000


ブラウザでアクセス

http://localhost:8000/bbstest.php

💡 今後の改善ポイント

投稿に対する返信機能の強化

画像サムネイル表示

CSRF 対策の実装

フロントエンドのデザイン強化

📜 ライセンス

このプロジェクトは MIT ライセンスで公開されています。


---
