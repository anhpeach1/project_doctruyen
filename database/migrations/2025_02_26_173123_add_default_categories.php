<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = [
            ['name' => 'Tình cảm', 'description' => 'Truyện tình cảm lãng mạn'],
            ['name' => 'Phiêu lưu', 'description' => 'Truyện phiêu lưu mạo hiểm'],
            ['name' => 'Khoa học viễn tưởng', 'description' => 'Truyện khoa học viễn tưởng'],
            ['name' => 'Kinh dị', 'description' => 'Truyện kinh dị'],
            ['name' => 'Hài hước', 'description' => 'Truyện hài hước'],
            ['name' => 'Giả tưởng', 'description' => 'Truyện giả tưởng'],
            ['name' => 'Trinh thám', 'description' => 'Truyện trinh thám'],
            ['name' => 'Học đường', 'description' => 'Truyện học đường'],
            ['name' => 'Tiên hiệp', 'description' => 'Truyện tiên hiệp'],
            ['name' => 'Kiếm hiệp', 'description' => 'Truyện kiếm hiệp'],
            ['name' => 'Xuyên không', 'description' => 'Truyện xuyên không'],
            ['name' => 'Truyện ngắn', 'description' => 'Truyện ngắn'],
            ['name' => 'Light Novel', 'description' => 'Light Novel Nhật Bản']
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')->whereIn('name', [
            'Tình cảm',
            'Phiêu lưu',
            'Khoa học viễn tưởng',
            'Kinh dị',
            'Hài hước',
            'Giả tưởng',
            'Trinh thám',
            'Học đường',
            'Tiên hiệp',
            'Kiếm hiệp',
            'Xuyên không',
            'Truyện ngắn',
            'Light Novel'
        ])->delete();
    }
};