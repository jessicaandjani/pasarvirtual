<?php

use Illuminate\Database\Seeder;

class DictionarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('pgsql')->table('dictionaries')->insert([
            ['abbreviation' => 'aym', 'word' => 'ayam'],
            ['abbreviation' => 'wrtl', 'word' => 'wortel'],
            ['abbreviation' => 'wortl','word' => 'wortel'],
            ['abbreviation' => 'bncis', 'word' => 'buncis'],
            ['abbreviation' => 'buncs', 'word' => 'buncis'],
            ['abbreviation' => 'kangkng', 'word' => 'kangkung'],
            ['abbreviation' => 'kngkung', 'word' => 'kangkung'],
            ['abbreviation' => 'tmt', 'word' => 'tomat'],
            ['abbreviation' => 'tomt', 'word' => 'tomat'],
            ['abbreviation' => 'tmat', 'word' => 'tomat'],
            ['abbreviation' => 'bym', 'word' => 'bayam'],
            ['abbreviation' => 'byam', 'word' => 'bayam'],
            ['abbreviation' => 'kcng', 'word' => 'kacang'],
            ['abbreviation' => 'kacng', 'word' => 'kacang'],
            ['abbreviation' => 'kcang', 'word' => 'kacang'],
            ['abbreviation' => 'pjg', 'word' => 'panjang'],
            ['abbreviation' => 'panjng', 'word' => 'panjang'],
            ['abbreviation' => 'pnjng', 'word' => 'panjang'],
            ['abbreviation' => 'pnjang', 'word' => 'panjang'],
            ['abbreviation' => 'kcngpjg', 'word' => 'kacang panjang'],
        ]);

    }
}
