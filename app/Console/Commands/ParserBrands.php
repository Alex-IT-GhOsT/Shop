<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Brands;

class ParserBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:brands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение всех брэндов';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $domain = 'https://gate.21vek.by/product-listings/api/v1/producers';

        $client = new Client([
            'verify' => false,
        ]);

        $response = $client->request('POST', $domain, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
            ],
            'json' => [
                'templateId' => 51,
                'attributes' => [],
                'discountTypes' => [],
                'deliveryType' => null
            ]
        ]);

        $data = json_decode($response->getBody(),true);

        foreach($data as $elem) {
            foreach($elem as $item) {
                Brands::create([
                    'name' => $item['name'],
                ]);
            };
        };

    }
}
