<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BkmkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Office
        $offices = [
            ['name' => 'DP3AP2 DIY KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMK N 4 KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMK N 4 CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'DKP KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 4 KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BAPPEDA DIY KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BLPT TAMAN', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BLPT KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BLPT ASRAMA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BLPT CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BPTPB CANGKRINGAN CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PKMS IMOGIRI', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'DPD PDI PERJUANGAN', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'DP3AP2 CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BPPA CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BPPA KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BKD DIY', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PELABUHAN TANJUNG ADIKARTO', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'DPMPTSP DIY', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 11 CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 11 KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMK N 2 CS', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMK N 2 KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 2', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 9', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PDIN KA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PPN SEDAYU', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BPKA DIY', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'CITRAWEB', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PKMS GODEAN II', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PKMS TURI', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'PUSKESMAS GODEAN 1', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'UPT LOGAM', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'KEMANTREN UMBULHARJO', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 6', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'DISKOMINFO DIY', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'MESS BPKH', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BIRO PIWPP DIY', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'SMA N 3', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'KEMANTREN TEGALREJO', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'KOMINFO YOGYA', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
            ['name' => 'BALAI BATIK', 'lat' => '-7.79055', 'long' => '110.37294', 'radius' => 6000],
        ];
        
        foreach ($offices as $office) {
            Office::create($office);
        }
    }
}
