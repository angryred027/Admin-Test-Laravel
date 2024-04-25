<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * banners table
         */
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->comment('uuid');
            $table->string('name', 255)->comment('バナー名');
            $table->text('detail')->comment('詳細');
            $table->string('location', 255)->comment('配置場所');
            $table->integer('pc_height')->unsigned()->comment('PCサイズの高さ');
            $table->integer('pc_width')->unsigned()->comment('PCサイズの幅');
            $table->integer('sp_height')->unsigned()->comment('SPサイズの高さ');
            $table->integer('sp_width')->unsigned()->comment('SPサイズの幅');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->string('url', 255)->comment('遷移先URL');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            // index設定
            $table->index('location');

            $table->comment('banners table');
        });

        /**
         * banner blocks table
         */
        Schema::create('banner_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('ブロック名');
            $table->integer('order')->unsigned()->comment('順番');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('banner blocks table');
        });

        /**
         * banner block contents table
         */
        Schema::create('banner_block_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('banner_block_id')->unsigned()->comment('バナーブロックのID');
            $table->integer('banner_id')->unsigned()->comment('バナーのID');
            $table->tinyInteger('type')->unsigned()->comment('コンテンツタイプ');
            $table->integer('order')->unsigned()->comment('順番');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            // uniqueキー設定
            $table->unique(['banner_block_id', 'banner_id']);

            $table->comment('banner block contents table');
        });

        /**
         * coins table
         */
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('コイン名');
            $table->text('detail')->comment('詳細');
            $table->integer('price')->unsigned()->comment('コインの購入価格');
            $table->integer('cost')->unsigned()->comment('アプリケーション内のコインの価格');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->string('image', 255)->comment('イメージ');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('coins table');
        });

        /**
         * contacts table
         */
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->comment('メールアドレス');
            $table->integer('user_id')->unsigned()->default(0)->comment('ユーザーID');
            $table->string('name', 255)->default('')->comment('名前');
            $table->tinyInteger('type')->unsigned()->comment('問合せ種類');
            $table->text('detail')->comment('詳細');
            $table->text('failure_detail')->nullable()->default(null)->comment('障害詳細');
            $table->dateTime('failure_at')->nullable()->default(null)->comment('障害発生日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('contacts table');
        });

        /**
         * events table
         */
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('イベント名');
            $table->tinyInteger('type')->unsigned()->comment('イベントタイプ');
            $table->text('detail')->comment('詳細');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('events table');
        });

        /**
         * home contents table
         */
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->unsigned()->comment('コンテンツタイプ');
            $table->integer('group_id')->unsigned()->comment('グループのID');
            $table->integer('contents_id')->unsigned()->comment('コンテンツのID');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('home contents table');
        });

        /**
         * home contents groups table
         */
        Schema::create('home_contents_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('コンテンツグループ名');
            $table->integer('order')->unsigned()->comment('順番');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('home contents groups table');
        });

        /**
         * images table
         */
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            // $table->uuid()->unique()->primary()->comment('uuid');
            $table->uuid()->unique()->comment('uuid');
            $table->string('name', 255)->comment('オリジナルファイル名');
            $table->string('extention', 255)->comment('拡張子');
            $table->string('mime_type', 255)->comment('mimeType');
            $table->string('s3_key', 255)->nullable()->comment('AWS S3のkey');
            $table->integer('version')->unsigned()->comment('ファイルのバージョン(更新日時のタイムスタンプ)');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            // index設定
            $table->index('s3_key');

            $table->comment('images table');
        });

        /**
         * informations table
         */
        Schema::create('informations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('お知らせ名');
            $table->tinyInteger('type')->unsigned()->comment('お知らせタイプ 1:お知らせ、2:メンテナンス、3:障害');
            $table->text('detail')->comment('詳細');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('informations table');
        });

        /**
         * products table
         */
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('商品名');
            $table->text('detail')->comment('詳細');
            $table->tinyInteger('type')->unsigned()->comment('商品の種類');
            $table->integer('price')->unsigned()->comment('価格');
            $table->string('unit', 255)->comment('単位');
            $table->string('manufacturer', 255)->comment('製造元');
            $table->dateTime('notice_start_at')->comment('予告開始日時');
            $table->dateTime('notice_end_at')->comment('予告終了日時');
            $table->dateTime('purchase_start_at')->comment('購入開始日時');
            $table->dateTime('purchase_end_at')->comment('購入終了日時');
            $table->string('image', 255)->comment('イメージ');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('products table');
        });

        /**
         * product_types table
         */
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('種類名');
            $table->text('detail')->comment('詳細');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('type of product table');
        });

        /**
         * manufacturers table
         */
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('製造元名');
            $table->text('detail')->comment('詳細');
            $table->string('address', 255)->comment('住所');
            $table->string('tel', 255)->comment('電話番号');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('manufacturers table');
        });

        /**
         * questionnaires table
         */
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('アンケート名');
            $table->text('detail')->comment('詳細');
            $table->json('questions')->comment('アンケート項目');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('expired_at')->comment('解答終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('questionnaires table');
        });

        /**
         * service_terms table
         */
        Schema::create('service_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('version')->unsigned()->comment('バージョン');
            $table->text('terms')->comment('利用規約');
            $table->text('privacy_policy')->comment('プライバシーポリシー');
            $table->string('memo', 255)->comment('メモ');
            $table->dateTime('start_at')->comment('公開開始日時');
            $table->dateTime('end_at')->comment('公開終了日時');
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');

            $table->comment('terms of service table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('banner_blocks');
        Schema::dropIfExists('banner_block_contents');
        Schema::dropIfExists('coins');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('events');
        Schema::dropIfExists('home_contents');
        Schema::dropIfExists('home_contents_groups');
        Schema::dropIfExists('images');
        Schema::dropIfExists('informations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('questionnaires');
        Schema::dropIfExists('manufactureres');
        Schema::dropIfExists('service_terms');
    }
}
