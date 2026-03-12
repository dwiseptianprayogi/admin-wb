<?php

namespace Database\Seeders;

use App\Models\Transporter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransporterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transporters = [
            [
                'code' => 'TP0100701',
                'name' => 'ANUGERAH JAYA BERSAMA CV',
                'address' => 'RY DEANDELS KM62 DS SIDOKELAR KEC PACIRAN KAB LAMONGAN JATIM'
            ],
            [
                'code' => 'TP0100901',
                'name' => 'ANGKASA KARYA SEJAHTERA PT',
                'address' => ''
            ],
            [
                'code' => 'TP0101001',
                'name' => 'AIRMAS TRANSPORT PT',
                'address' => ''
            ],
            [
                'code' => 'TP0400501',
                'name' => 'DINAMIKA UTAMA CEMERLANG PT.',
                'address' => 'JL.RY CILINCING NO.36 RUKO PLZ CILINCING D1/2 JAKUT'
            ],
            [
                'code' => 'TP0400701',
                'name' => 'DINAMIKA SUMBER UTAMA PT',
                'address' => ''
            ],
            [
                'code' => 'TP0800401',
                'name' => 'HIDUP BARU TRANS CV',
                'address' => 'VILA REGENCI TNG II AA2 NO.28 RT.003 RW.005 KEL.GELAM JAYA PASAR KEMIS TNG'
            ],
            [
                'code' => 'TP0900201',
                'name' => 'INDOSUKSES SAMUDRA P.E. PT',
                'address' => 'LIVDEVETES TRADE CENTER BUILD ING,LT.UGBL B20 NO.1 JL.HAYAM WURUK 127 JKT 11180'
            ],
            [
                'code' => 'TP0900301',
                'name' => 'INDOTRUCK JAYA PANGS PT',
                'address' => 'JL.KIAGUS ANANG NO.17 KETAPANG BANDARLAMPUNG LAMPUNG'
            ],
            [
                'code' => 'TP0900401',
                'name' => 'INDO SARI BUMI TRANSPORT PR P',
                'address' => ''
            ],
            [
                'code' => 'TP1000401',
                'name' => 'JATI KAWI CV',
                'address' => ''
            ],
            [
                'code' => 'TP1100101',
                'name' => 'KARYA SEMESTA LOGISTIK PT',
                'address' => 'JL.KUNIR 37 BLOK INO.4 TAMANSARI JAKARTA BARAT'
            ],
            [
                'code' => 'TP1100201',
                'name' => 'KMP',
                'address' => ''
            ],
            [
                'code' => 'TP1100901',
                'name' => 'KARYA JASA TRANSINDO PT',
                'address' => ''
            ],
            [
                'code' => 'TP1101001',
                'name' => 'KARYA USAHA TRANSPORT PT',
                'address' => 'JL.DR.WAHIDIN SUDIROHUSODO 59B RT.01/01 KEDUNG WARU TULUNG AGUNG'
            ],
            [
                'code' => 'TP1101101',
                'name' => 'KUSALA SIRI JAYA CV',
                'address' => 'DUSUN SUKOMULYO RT.002 RW.002 BLIMBING GUDO KAB.JOMBANG JAWA TIMUR'
            ],
            [
                'code' => 'TP1901901',
                'name' => 'SANJAYA PUTRA PRATAMA PT',
                'address' => ''
            ],
            [
                'code' => 'TP1902001',
                'name' => 'SAMUDRA DWI TRANSPORINDO PT',
                'address' => ''
            ],
            [
                'code' => 'TP1902101',
                'name' => 'SYUHADA HARAPAN DJAJA PT',
                'address' => ''
            ],
            [
                'code' => 'TP1902201',
                'name' => 'SUMBER AYEM ASMORO PT',
                'address' => ''
            ],
            [
                'code' => 'TP1902301',
                'name' => 'SOLUSI ALAT BERAT INDONESIA PT',
                'address' => 'T GUDANG KAPUR JL.RYLEGOK KAMP ANGRIS RT.04/09 BOJONGNANGKA KLP DUA TANGERANG'
            ],
            [
                'code' => 'TP1902401',
                'name' => 'SIBA SURYA PT',
                'address' => 'JL.TERBOYO NO.07 TERBOYO WETAN GENUK SEMARANG'
            ],
            [
                'code' => 'TP1902501',
                'name' => 'SINAR MAS TRANSPORT PT',
                'address' => 'JL.PAHLAWAN GG X NO.11A RT.003 RW.004 KEL.TANJUNGPURWOKERTO BANYUMAS JATENG'
            ],
            [
                'code' => 'TP1902601',
                'name' => 'SERAYU',
                'address' => 'JL.SERAYU NO.61 RT.03 RW.07 MINTARAGENTEGAL TIMUR KOTA TEGAL'
            ],
            [
                'code' => 'TP1902901',
                'name' => 'SAUDARA JAYA TRANSINDO PT',
                'address' => 'JL.MAJAPAHIT NO.127 RT.004/005 KEL.GAYAMSARI KEC.GAYAMSARI SEMARANG'
            ],
            [
                'code' => 'TP1903001',
                'name' => 'SAMPURNA JAYA CV',
                'address' => 'KOMPLEK DHI (DUTA HARAPAN INDAH) BLOK PNO.45 TELUK GONG KAPUK MUARA JAKUT'
            ],
            [
                'code' => 'TP2001001',
                'name' => 'TRANS MITRA CEMERLANG PT',
                'address' => ''
            ],
            [
                'code' => 'TP2300201',
                'name' => 'WIRA NARAPUTRA KURNIA PT',
                'address' => ''
            ],
        ];


        foreach ($transporters as $transporter) {
            $isExist = Transporter::where('ShortChar01', $transporter['code'])->exists();

            if (!$isExist) {
                $transporter['created_by'] = 'System';
                $transporter['updated_by'] = 'System';
                Transporter::create($transporter);
            }
        }
    }
}
