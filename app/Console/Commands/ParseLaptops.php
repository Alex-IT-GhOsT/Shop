<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Laptop;

class ParseLaptops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:laptops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсинг';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client([
            'verify' => false,
        ]);

        $pageCount = 35;

        $domain = 'https://www.21vek.by/notebooks/';

        $origin = 'https://www.21vek.by/';

        $allProduct = [];

        for($page = 1; $page <= $pageCount; $page++) {

        $response = $client->request('GET', $page !==1 ? $domain . 'page:' . $page . '/' : $domain, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36'
            ]
        ]);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        $productsList = $crawler->filter('div.style_container__TFmIX')->each(function($node) use (&$listsInfo, $origin) {

            $section = [];

            $node->filter('div[data-id^="product-"]')->each(function($node) use(&$section, $origin) {
               
                $section[] = [
                    'curPrice' => $node->filter('section[data-testid="card-price"] div p[data-testid="card-current-price"]')->count() ? $node->filter('section[data-testid="card-price"] div p[data-testid="card-current-price"]')->text() : null,
                    'oldPrice' => $node->filter('section[data-testid="card-price"] div p[data-testid="card-old-price"]')->count() ? $node->filter('section[data-testid="card-price"] div p[data-testid="card-old-price"]')->text() : null ,
                    'product' => [
                        'href' =>$node->filter('a[data-testid="card-info-a"]')->count() ? $origin . ltrim($node->filter('a[data-testid="card-info-a"]')->attr('href'),'/') : null, 
                        'text' =>$node->filter('a[data-testid="card-info-a"]')->count() ?  $node->filter('a[data-testid="card-info-a"]')->text() : null,
                        'discount' => $node->filter('div[data-testid="card-media"] span')->count() ? $node->filter('div[data-testid="card-media"] span')->text() : null,
                        'superprice' => $node->filter('div.CardDiscountType_label__Z65eZ')->count() ?
                            $node->filter('div.CardDiscountType_label__Z65eZ')->text() 
                            : 
                            null,
                    ],
                    'image' => [
                        'src' =>$node->filter('div[data-testid="card-media"] img')->count() ? $node->filter('div[data-testid="card-media"] img')->attr('src') : null,
                        'alt' =>$node->filter('div[data-testid="card-media"] img')->count() ? $node->filter('div[data-testid="card-media"] img')->attr('alt') : null,
                    ],
                    'reviewsLink' => [
                        'href' => $node->filter('a')->count() ? $origin . ltrim($node->filter('a')->attr('href'),'/') : null,
                    ],
                ];

            });

            return $section;

        });

        $allProduct = array_merge($allProduct,$productsList);

        }

        foreach($allProduct as $products) {
            foreach($products as $elem) {
                Laptop::create([
                    'product_name' => $elem['product']['text'] ?? null,
                    'current_price' => $elem['curPrice'] ?? null,
                    'old_price' => $elem['oldPrice'] ?? null,
                    'href' => $elem['product']['href'] ?? null,
                    'image_src' => $elem['image']['src'] ?? null,
                    'image_alt' => $elem['image']['alt'] ?? null,
                    'reviews_link' => $elem['reviewsLink']['href']?? null,
                    'discount' => $elem['product']['discount'] ?? null,
                    'superprice' => $elem['product']['superprice'] ?? null,
                ]);
            }
        }
        
    
    }
}
