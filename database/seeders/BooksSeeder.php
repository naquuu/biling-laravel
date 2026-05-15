<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['code' => 'BK01', 'name' => 'Bk. Penjualan Telur Partai', 'created_by' => 1],
            ['code' => 'BK02', 'name' => 'Bk. Penjualan Telur Ecer', 'created_by' => 1],
            ['code' => 'BK03', 'name' => 'Bk. Ternak (Pakan/Telur)', 'created_by' => 1],
            ['code' => 'BK04', 'name' => 'Bk. Supplier Pakan', 'created_by' => 1],
            ['code' => 'BK05', 'name' => 'Bk. Karyawan', 'created_by' => 1],
            ['code' => 'BK06', 'name' => 'Bk. Sopir', 'created_by' => 1],
        ];

        foreach ($books as $book) {
            DB::table('books')->insert($book);
        }
    }
}