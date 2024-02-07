<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaontrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $counteris = array(
			array('code' => 'DH', 'name' => 'دهوك'),
			array('code' => 'AR', 'name' => 'اربيل'),
			array('code' => 'SL', 'name' => 'سليمانيه'),
			array('code' => 'KR', 'name' => 'كركوك'),
			array('code' => 'SA', 'name' => 'صلاح الدين'),
			array('code' => 'SY', 'name' => 'ديالى'),
			array('code' => 'BG', 'name' => 'بغداد'),
			array('code' => 'RM', 'name' => 'الرمادي'),
			array('code' => 'KA', 'name' => 'كربلاء'),
			array('code' => 'NJ', 'name' => 'النجف'),
			array('code' => 'BA', 'name' => 'بابل'),
			array('code' => 'WA', 'name' => 'واسط'),
			array('code' => 'QA', 'name' => 'القادسيه'),
			array('code' => 'MT', 'name' => 'المثنى'),
			array('code' => 'ME', 'name' => 'ميسان'),
            array('code' => 'DH', 'name' => 'ذي قار'),
            array('code' => 'BS', 'name' => 'البصره'),
            array('code' => 'NY', 'name' => 'نينوى'),
		);

		DB::table('counteris')->insert($counteris);
    }
}
