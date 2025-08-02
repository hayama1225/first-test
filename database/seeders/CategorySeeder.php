<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $rows = collect(['配送', '交換', 'トラブル', 'ショップ', 'その他'])
            ->map(fn($v) => ['content' => $v, 'created_at' => $now, 'updated_at' => $now])
            ->all();

        // 重複があっても OK にしたい場合は upsert でも可
        DB::table('categories')->insert($rows);
    }
}
