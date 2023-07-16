# Contribution Guide

本レポジトリへのコントリビュート方法についてのガイドです。

## はじめに

本レポジトリで使う技術の入門教材をリストアップしました。
経験のない方はご活用ください。

### Command Line 入門

- [Command Line | Progate](https://prog-8.com/courses/commandline)

### Git 入門

- [サル先生の Git 入門 | Backlog](https://backlog.com/ja/git-tutorial/)
- [Git | Progate](https://prog-8.com/courses/git)

### GitHub 入門

- [GitHub の概要 - Learn | Microsoft Docs](https://docs.microsoft.com/ja-jp/training/modules/introduction-to-github/)
- [Introduction to GitHub | GitHub Skills](https://github.com/skills/introduction-to-github)

### HTML / CSS 入門

- [HTML & CSS | Progate](https://prog-8.com/courses/html)

### PHP 入門

- [PHP | Progate](https://prog-8.com/courses/php)

### MySQL 入門

- [MySQL の開発環境を用意しよう (mac) | Progate](https://prog-8.com/docs/mysql-env)
- [MySQL の開発環境を用意しよう (Windows) | Progate](https://prog-8.com/docs/mysql-env-win)
- [MySQL でデータベースを作成しよう | Progate](https://prog-8.com/docs/mysql-database-setup)
- [MySQL 入門 | Qiita](https://qiita.com/okamuuu/items/c4efb7dc606d9efe4282)

[MAMP](https://www.mamp.info/en/mamp) を使うと楽に環境構築ができます。

### Laravel 入門

- [Laravel 入門 | Qiita](https://qiita.com/sano1202/items/6021856b70e4f8d3dc3d)

### Vue.js 入門

- [Vue.js チュートリアル](https://ja.vuejs.org/tutorial/#step-1)

### Nuxt.js 入門

- [Get Started with Nuxt](https://nuxt.com/docs/getting-started/introduction)
- [Nuxt 3を使いこなすために基礎から徹底解説](https://reffect.co.jp/vue/nuxt3/)

## 開発環境構築

### レポジトリのフォーク

https://github.com/chorkleines/kleines-mypage をフォークしてください。

### レポジトリのクローン

フォークしたレポジトリをクローンします。

```sh
git clone git@github.com:<your-user-name>/kleines-mypage.git
```

リモートに大元のレポジトリを追加します。

```sh
git remote add upstream git@github.com:chorkleines/kleines-mypage.git
```

### API (Laravel)

API は Laravel で書かれています。
ソースコードは `api/` ディレクトリにあるので移動しましょう。

```sh
cd api
```

#### PHP のインストール

PHP8 (>=8.0.21) をインストールしてください。

#### 依存ライブラリのインストール

```sh
composer install
composer install --working-dir=./tools/php-cs-fixer
```

#### データベース作成

MySQL データベースを作成してください。
データベース名は `kleines_mypage` とします。（任意の名前に変更可能です）

#### .env ファイルの変更

`.env` ファイルを作成してください。

```sh
cp .env.example .env
```

`APP_NAME`を以下のように変更してください。

```
APP_NAME="Kleines Mypage"
```

テータベースの接続設定を行ってください。（自分の環境に合わせて設定してください）

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kleines_mypage
DB_USERNAME=root
DB_PASSWORD=root
```

#### Laravel のセットアップ

アプリケーションキーを生成して下さい。

```sh
php artisan key:generate
```

JWT の秘密鍵を生成します。

```sh
php artisan jwt:secret
```

テーブルを作成します。

```sh
php artisan migrate
```

初期ユーザーを作成します。

```sh
php artisan db:seed
```

以下のユーザーが作成されます。

> Email: admin@chorkleines.com  
> Password: password

サーバーを立ち上げてみましょう。

```sh
php artisan serve
```

続いて、クライアント側のサーバーを起動してログインできるか確認してみます。

### クライアント (Nuxt.js)

クライアントは Nuxt.js で書かれています。
ソースコードは `client/` ディレクトリにあるので移動しましょう。

```sh
cd ../client
```

#### 依存ライブラリのインストール

```sh
npm install
```

#### サーバーの起動

```sh
npm run dev
```

無事ログインができたら開発環境構築は完了です！

## Pull Request

コードを修正する場合は Pull Request (PR) を作成してください。

PR を作成する際には、[chorkleines/kleines-mypage](https://github.com/chorkleines/kleines-mypage) に大量のブランチが生成されることを防止するために、フォーク先のレポジトリから作成して下さい。
また原則として、PR を merge する前に他のメンバーから approve をもらってください。
そのため、Reviewer にメンバーを誰か指定してください。Assignees には自分を指定し、Labels は適切なものを選択してください。

細かい PR は Issue を立てずに提出しても問題ありません。
修正・改善内容に疑問がある場合は、Issue を立てて相談するようにしてください。

## コーディング規約

コードの差分をできる限り減らすため、フォーマッターを指定しています。
コードを編集したら以下のコマンドを実行してコードを整形してください。

```sh
./format.sh
```

PR を作成すると GitHub Actions が起動し、フォーマットのチェックが行われます。
チェックを通るまで PR を merge することはできません。
チェックに通らなかったらエラーを確認して再度コードの整形を行ってください。
