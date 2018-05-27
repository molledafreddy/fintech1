<?php

use Illuminate\Database\Seeder;
use App\Bank;
use App\Account;
class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank = factory(Bank::class)->create(
            [
                'name' => 'Banco Santander'
                ,'number'=>'40014', 
                'transfer_key'=>'BANME'
            ]);

        factory(Account::class,1)->create([
            'bank_id' => $bank->id,
            'status' => 'available',
        ]);
        DB::table('banks')->insert([
                    [
                    'name' => 'BANXICO',
                    'number' => '2001',
                    'transfer_key' => 'BANCO',
                ],
                [
                    'name' => 'BANCOMEXT',
                    'number' => '37006',
                    'transfer_key' => 'BCEXT',
                ],
                [
                    'name' => 'BANOBRAS',
                    'number' => '37009',
                    'transfer_key' => 'BOBRA',
                ],
                [
                    'name' => 'BANJERCITO',
                    'number' => '37019',
                    'transfer_key' => 'BEJER',
                ],
                [
                    'name' => 'NACIONAL FINANCIERA',
                    'number' => '37135',
                    'transfer_key' => 'NAFIN',
                ],
                [
                    'name' => 'BANSEFI',
                    'number' => '37166',
                    'transfer_key' => 'BANSE',
                ],
                [
                    'name' => 'HIPOTECARIA FEDERAL',
                    'number' => '37168',
                    'transfer_key' => 'HIFED',
                ],
                [
                    'name' => 'BANAMEX',
                    'number' => '40002',
                    'transfer_key' => 'BANAM',
                ],
                [
                    'name' => 'BBVA BANCOMER',
                    'number' => '40012',
                    'transfer_key' => 'BACOM',
                ],
                [
                    'name' => 'HSBC',
                    'number' => '40021',
                    'transfer_key' => 'BITAL',
                ],
                [
                    'name' => 'BAJIO',
                    'number' => '40030',
                    'transfer_key' => 'BAJIO',
                ],
                [
                    'name' => 'IXE',
                    'number' => '40032',
                    'transfer_key' => 'BAIXE',
                ],
                [
                    'name' => 'INBURSA',
                    'number' => '40036',
                    'transfer_key' => 'BINBU',
                ],
                [
                    'name' => 'INTERACCIONES',
                    'number' => '40037',
                    'transfer_key' => 'BINTE',
                ],
                [
                    'name' => 'MIFEL',
                    'number' => '40042',
                    'transfer_key' => 'MIFEL',
                ],
                [
                    'name' => 'SCOTIA BANK INVERLAT',
                    'number' => '40044',
                    'transfer_key' => 'COMER',
                ],
                [
                    'name' => 'BANREGIO',
                    'number' => '40058',
                    'transfer_key' => 'BANRE',
                ],
                [
                    'name' => 'INVEX',
                    'number' => '40059',
                    'transfer_key' => 'BINVE',
                ],
                [
                    'name' => 'BANSI',
                    'number' => '40060  ',
                    'transfer_key' => 'BANSI',
                ],
                [
                    'name' => 'AFIRME',
                    'number' => '40062',
                    'transfer_key' => 'BAFIR',
                ],
                [
                    'name' => 'BANORTE',
                    'number' => '40072',
                    'transfer_key' => 'BBANO',
                ],
                [
                    'name' => 'ROYAL BANK OF SCOTLAND',
                    'number' => '40102',
                    'transfer_key' => 'ABNBA',
                ],
                [
                    'name' => 'AMERICAN EXPRESS',
                    'number' => '40103',
                    'transfer_key' => 'AMEX',
                ],
                [
                    'name' => 'BANK OF AMERICA',
                    'number' => '40106',
                    'transfer_key' => 'BAMSA',
                ],
                [
                    'name' => 'TOKYO',
                    'number' => '40108',
                    'transfer_key' => 'TOKYO',
                ],
                [
                    'name' => 'JP MORGAN',
                    'number' => '40110',
                    'transfer_key' => 'CHASE',
                ],
                [
                    'name' => 'BANCO MONEX',
                    'number' => '40112',
                    'transfer_key' => 'CMCA',
                ],
                [
                    'name' => 'VE POR MAS',
                    'number' => '40113',
                    'transfer_key' => 'DRESD',
                ],
                [
                    'name' => 'ING',
                    'number' => '40116',
                    'transfer_key' => 'INGBA',
                ],
                [
                    'name' => 'DEUTCHE',
                    'number' => '40124',
                    'transfer_key' => 'DEUTB',
                ],
                [
                    'name' => 'CREDIT SUISSE',
                    'number' => '40126',
                    'transfer_key' => 'CRESU',
                ],
                [
                    'name' => 'AZTECA',
                    'number' => '40127',
                    'transfer_key' => 'BAZTE',
                ],
                [
                    'name' => 'BANCO AUTOFIN',
                    'number' => '40128',
                    'transfer_key' => 'BAUTO',
                ],
                [
                    'name' => 'BARCLAYS BANK',
                    'number' => '40129',
                    'transfer_key' => 'BARCL',
                ],
                [
                    'name' => 'BANCO COMPARTAMOS',
                    'number' => '40130',
                    'transfer_key' => 'BCOMP',
                ],
                [
                    'name' => 'BANCO DE AHORRO FAMSA',
                    'number' => '40131',
                    'transfer_key' => 'FAMSA',
                ],
                [
                    'name' => 'BANCO MULTIVA',
                    'number' => '40132',
                    'transfer_key' => 'MULTI',
                ],
                [
                    'name' => 'PRUDENTIAL BANK',
                    'number' => '40133',
                    'transfer_key' => 'PRUDE',
                ],
                [
                    'name' => 'BANCO WAL MART',
                    'number' => '40134',
                    'transfer_key' => 'BWALL',
                ],
                [
                    'name' => 'BANCO REGIONAL SA',
                    'number' => '40136',
                    'transfer_key' => 'REGIO',
                ],
                [
                    'name' => 'BANCOPPEL',
                    'number' => '40137',
                    'transfer_key' => 'COPEL',
                ],
                [
                    'name' => 'BANCO AMIGO',
                    'number' => '40138',
                    'transfer_key' => 'AMIGO',
                ],
                [
                    'name' => 'UBS BANK MEXICO',
                    'number' => '40139',
                    'transfer_key' => 'UBSBA',
                ],
                [
                    'name' => 'BANCO FACIL',
                    'number' => '40140',
                    'transfer_key' => 'FACIL',
                ],
                [
                    'name' => 'VOLKSWAGEN BANK',
                    'number' => '40141',
                    'transfer_key' => 'VOLKS',
                ],
                [
                    'name' => 'BANCO CONSULTORIA',
                    'number' => '40143',
                    'transfer_key' => 'CONSU',
                ],
            ]);
        // factory(Bank::class,15)->create();
    }
}
