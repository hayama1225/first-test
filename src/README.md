# お問い合わせフォーム (COACHTECH 課題)

FashionablyLate テーマのシンプルなお問い合わせアプリです。
Fortify による認証、問い合わせ入力→確認→サンキュー、管理画面（検索・フィルタ・CSVエクスポート・モーダル詳細・削除・7件/頁）を実装しています。
※おおよそ課題条件に寄せているつもりですが、完全ではありません。
ログイン: /login・ユーザー登録: /registerが消えてしまったようです。

1. https://github.com/hayama1225/first-test.git
2 .docker-compose up -d --build

---

## 環境構成

- **Docker**（nginx / php-fpm / mysql）
- **PHP**: 8.1+（Laravel 10 を想定）
- **Laravel**: 10.x
- **MySQL**: 8.0
※ PHP8.0 だと Laravel10 は動作要件を満たしません。PHP 8.1 以上をご利用ください。

---

## セットアップ

プロジェクトルート（`test-development/` など）で以下を実行します。

```bash
# 1) コンテナ起動
docker compose up -d

# 2) 依存パッケージ
docker compose exec php composer install

# 3) アプリキー発行
docker compose exec php php artisan key:generate

# 5) マイグレーション + 初期データ（カテゴリ5件・コンタクト35件）
docker compose exec php php artisan migrate --seed


```

## ER図
![ER図](docs/erd.drawio.svg)

## URL

ログイン: /login

ユーザー登録: /register

お問い合わせ（入力）: /

確認画面: /confirm（POST）

サンクス: /thanks（POST）

管理画面（一覧）: /admin（要ログイン）

