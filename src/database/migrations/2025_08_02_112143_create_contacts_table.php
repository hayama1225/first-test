<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete(); // 参照中カテゴリの削除を防止（お好みで cascade に変更可）
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->unsignedTinyInteger('gender'); // 1=男性,2=女性,3=その他
            $table->string('email', 255)->index();
            $table->string('tel', 255);
            $table->string('address', 255);
            $table->string('building', 255)->nullable();
            $table->text('detail');

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
