<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $url_province = "https://api.rajaongkir.com/starter/province?key=31b7aa3fc581f43563a58e6d44ae2fd6";
        $json_str = file_get_contents($url_province);
        $json_obj = json_decode($json_str);

        $provinces = [];
        foreach ($json_obj->rajaongkir->results as $province) {
            $provinces[] = [
                'id' => $province->province_id,
                'province' => $province->province
            ];
        }

        DB::table('provinces')->insert($provinces);
    }
}
