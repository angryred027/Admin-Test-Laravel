<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevelopmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * admins table
         */
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('管理者名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable()->comment('メールアドレス確認日時');
            $table->string('password')->comment('パスワード');
            $table->rememberToken();
            $table->dateTime('created_at')->useCurrent()->comment('登録日時');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->comment('更新日時');
            $table->dateTime('deleted_at')->nullable()->default(null)->comment('削除日時');
            // $table->timestamps();
            // $table->softDeletes();

            $table->comment('administrators table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
