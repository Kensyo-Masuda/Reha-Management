# Reha Management

理学療法士向けのリハビリ管理アプリケーションです。患者情報、リハビリ実施記録、身体機能評価を管理します。

個人利用を想定した試用版です。

## アプリケーション情報

- アプリケーション名：Reha Management
- フレームワーク：Laravel 13
- PHP：8.5（Laravel Sail 実行環境）
- データベース：MySQL 8.4
- フロントエンド：Blade、Tailwind CSS 4、Vite
- 開発環境：Windows、WSL2、Ubuntu 24.04、Docker Desktop、Visual Studio Code

## 主な機能

### 患者管理

- 患者の登録、一覧表示、検索、詳細表示、編集、削除
- 管理項目：氏名、生年月日、診断名、既往歴　（診断名、既往歴は手入力です）

### リハビリ実施記録

- 患者ごとの実施記録の登録、一覧表示、編集、削除
- 管理項目：実施日、担当者、実施内容、実施時間

### 身体機能評価

#### BBS（Berg Balance Scale）

- 14項目を各0～4点で評価（合計56点）
- 患者ごとの評価履歴、詳細表示、編集、削除

#### MMT（徒手筋力検査）

- 評価項目を選択し、左右別に0～5で記録
- 患者ごとの評価履歴、詳細表示、編集、削除

#### ROM（関節可動域）

- 評価項目を選択し、左右別のAROM・PROMを記録
- 測定角度の記録
- 患者ごとの評価履歴、詳細表示、編集、削除

評価には評価日と評価者を記録します。評価結果は評価データと関連付けて管理し、評価削除時には関連結果も削除します。更新・削除時は患者に紐づく評価のみを操作できます。

## 開発環境の準備と起動

以下はUbuntu（WSL2）のターミナルで、プロジェクトルートから実行します。

```bash
cd ~/reha-management

# 初回のみ：環境設定ファイルを作成
cp .env.example .env

# Dockerコンテナを起動
./vendor/bin/sail up -d

# 初回のみ：アプリケーションキーを生成
./vendor/bin/sail artisan key:generate

# データベースを作成・更新
./vendor/bin/sail artisan migrate
```

`.env.example` の主な初期値は次のとおりです。

| 設定 | 初期値 | 用途 |
| --- | --- | --- |
| `APP_URL` | `http://localhost` | アプリケーションのURL |
| `DB_CONNECTION` | `mysql` | データベースドライバー |
| `DB_HOST` | `mysql` | Dockerネットワーク上のMySQLサービス名 |
| `DB_PORT` | `3306` | MySQLポート |
| `DB_DATABASE` | `laravel` | データベース名 |
| `DB_USERNAME` | `sail` | データベースユーザー |
| `DB_PASSWORD` | `password` | ローカル開発用パスワード |
| `SESSION_DRIVER` | `database` | セッション保存先 |
| `CACHE_STORE` | `database` | キャッシュ保存先 |
| `QUEUE_CONNECTION` | `database` | キュー接続 |
| `MAIL_MAILER` | `log` | メールをログに出力 |

`APP_KEY` は空のままにせず、上記の `key:generate` で生成してください。`.env` は環境ごとに異なる設定を記述するファイルです。認証情報を含むため、公開・共有しないでください。

`.env.example` ではRedisの接続先が `127.0.0.1` です。LaravelコンテナからComposeのRedisサービスを利用する場合は、`.env` の `REDIS_HOST` を `redis` に変更してください。キューとキャッシュの既定値はデータベースを使用するため、Redisへの接続が不要な場合は変更不要です。

Viteを使う場合は、別のターミナルを開いて起動します。Viteのターミナルは開いたままにしてください。

```bash
cd ~/reha-management
./vendor/bin/sail npm run dev
```

ブラウザで [http://localhost](http://localhost) を開くと、患者一覧へ移動します。Laravelのポートは既定で80です（ポート8000ではありません）。

コンテナの状態は次のコマンドで確認できます。

```bash
./vendor/bin/sail ps
```

### 主なポート

| サービス | 既定ポート |
| --- | ---: |
| Laravel | 80 |
| Vite | 5173 |
| MySQL | 3306 |
| Redis | 6379 |
| Mailpit | 8025（Web UI）、1025（SMTP。`.env.example` のメール設定は `log`） |
| Meilisearch | 7700 |

ポートは `.env` の設定で変更できます。

## 主な開発コマンド

### Sail / Docker

```bash
# 起動
./vendor/bin/sail up -d

# 停止
./vendor/bin/sail down

# 状態確認
./vendor/bin/sail ps
```

### Laravel

```bash
# マイグレーション
./vendor/bin/sail artisan migrate

# ルート一覧
./vendor/bin/sail artisan route:list

# アプリケーションキャッシュのクリア
./vendor/bin/sail artisan optimize:clear

# Bladeキャッシュのクリア
./vendor/bin/sail artisan view:clear
```

### フロントエンド

```bash
# 開発用Viteサーバー
./vendor/bin/sail npm run dev

# 本番用アセットのビルド
./vendor/bin/sail npm run build
```

### テスト・構文チェック

```bash
# 全テスト
./vendor/bin/sail artisan test

# 特定のテストファイル
./vendor/bin/sail artisan test tests/Feature/EvaluationCrudTest.php

# PHP構文チェックの例
./vendor/bin/sail php -l app/Http/Controllers/PatientController.php
```

## 開発時のトラブルシューティング

### `./vendor/bin/sail: No such file or directory`

プロジェクトルート以外で実行している可能性があります。`cd ~/reha-management` でプロジェクトフォルダへ移動してから実行してください。

### ブラウザで接続できない

コンテナの起動状態と公開ポートを確認します。

```bash
./vendor/bin/sail ps
```

Laravelが `0.0.0.0:80->80/tcp` で公開されている場合は、[http://localhost](http://localhost) にアクセスしてください。

### Viteが起動しない、またはCSSが適用されない

依存パッケージが未導入の場合は、次のコマンドを実行し、その後Viteを起動してください。

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### Bladeキャッシュに起因する表示エラー

```bash
./vendor/bin/sail artisan view:clear
```

## 既知の注意点

### テスト用データベース

テスト環境で指定される専用のMySQL `testing` データベースは、現状のマイグレーション状態によって必要なテーブルが揃わず、テストを実行できない場合があります。過去の評価機能テストは、既存のローカル `laravel` データベースに接続し、`DatabaseTransactions` によりテストデータをロールバックして実行しました。

通常のアプリケーションデータとテストデータを明確に分離できるよう、専用テストデータベースとマイグレーションの整備が今後の課題です。接続先を変更する場合は、対象データベースを確認してから実行してください。

### 認証・アクセス制御

認証・アクセス権限は未実装です。実患者の情報を扱う場合は、認証、アクセス制御、データ保護を実装してください。試用版を外部公開しないでください。
