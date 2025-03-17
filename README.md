# coachtechフリマ

## プロジェクト概要
coachtechフリマは、ユーザーが商品を出品し、購入できるフリーマーケットアプリです。
会員登録機能、ログイン機能、商品検索、マイリスト、おすすめ商品の表示、コメント機能などを実装しています。

##　機能一覧
- ユーザー登録・ログイン機能(メール認証あり)
- 商品の出品
- 商品の検索・絞り込み
- おすすめ商品表示
- マイリスト機能
- コメント機能(ユーザーのプロフィール画像付き)
- 商品購入(購入確定後のステータス管理)

## 環境構築
### Dockerビルド
1. git clone git@github.com:nami49s/coachtech_fleamarket.git
2. docker-compose up -d -build

* MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

### Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed

## 使用技術
- PHP 8.4.3
- Laravel 8.83.29
- MySQL 9.1.0

## 使用ライブラリ
- Laravel Fortify(認証機能)

## URL
- 開発環境:http://localhost
- phpMyAdmin:http://localhost:8080

## ER図
![ER図](/images/flea-market.png)

## テストユーザー情報
一般ユーザー:
- メール: test@example
- パスワード: password