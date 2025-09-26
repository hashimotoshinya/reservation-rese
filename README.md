# 飲食店予約アプリ

飲食店の検索・予約・レビュー投稿ができる Web アプリケーションです。  
ユーザーは会員登録・メール認証後にログインし、店舗の検索や予約が可能です。  
管理者やオーナーは店舗や予約情報を管理できます。

---

## 作成した目的
- 学習の課題としての取り組み
  - 飲食店の予約管理を効率化する
  - 店舗ごとにオーナーが予約状況を確認できる仕組みを提供する
  - 管理者が通知やオーナー管理を行えるようにする

---

## アプリケーション URL
- 開発環境（local）: http://localhost  
- ステージング環境（AWS EC2）: http://3.113.30.77  
- MailHog（メール認証確認用）
  - local: http://localhost:8025
  - staging: http://3.113.30.77:8025  

※ 本番環境では AWS SES を利用予定です。

---

## 他のリポジトリ
なし（本リポジトリのみ）

---

## 機能一覧
- 会員登録 / ログイン / ログアウト（メール認証必須）
- 店舗一覧 / 詳細表示
- 店舗検索（エリア / ジャンル / 店名）
- お気に入り登録 / 解除
- 予約登録 / 予約キャンセル / 予約確認（QR コード付き）
- レビュー投稿（星評価・コメント）
- 管理者機能
  - オーナー作成
  - 通知送信（全体 / 常連ユーザー / オーナー）
- オーナー機能
  - オーナーダッシュボード
  - 店舗の作成 / 編集
  - 予約状況確認
- 勤怠管理機能（学習用拡張機能）

---

## 使用技術（実行環境）
- フレームワーク: Laravel 8.x (PHP 7.4)
- フロントエンド: Blade, Tailwind CSS
- データベース: MySQL (Amazon RDS)
- 認証: Laravel Breeze, メール認証 (MailHog / AWS SES)
- インフラ: AWS (EC2, RDS, S3, SES)
- Web サーバー: nginx + php-fpm
- その他: Docker (ローカル開発環境)

---

## テーブル設計
- users (会員情報、オーナー/管理者含む)
- shops (店舗情報)
- reservations (予約情報)
- favorites (お気に入り)
- reviews (レビュー)
- areas (エリア)
- genres (ジャンル)
- notifications (通知ログ)

---

## ER 図
＜ ER 図の画像をここに挿入 ＞

---

### セットアップ手順

#### ビルド＆起動
```
docker compose up -d --build
```

#### Laravelコンテナに入る
```
docker-compose exec php bash
```

#### 依存関係をインストール
```
composer install
```

#### 環境ファイルをコピー
```
cp .env.example .env
```
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
#### アプリケーションキーを生成
```
php artisan key:generate
```
#### 画像表示用のシンボリックリンク作成
```
php artisan storage:link
```
#### データベースをマイグレート & シーディング
```
php artisan migrate --seed
```
