<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\DB;

use App\Models\Attributes;
use App\Models\Categories;


class ParseInfoLaptop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parseinfolaptop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсить характеристики';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client([
            'verify' => false
        ]);

        $urls = DB::table('laptops')->select('id','href')->get();
        $total = [];

        foreach($urls as $url) {
        try{
            $response = $client->request('GET',$url->href,[
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36'
                ]
            ]);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);
            $section = [];
            
            $crawler->filter('#attributesBlock')->each(function($node) use (&$section) {
                $node->filter('div')->each(function ($categoryNode) use (&$section) {
                    $categoryNode->filter('h2')->each(function($h2Node) use (&$section, $categoryNode) {
                        $category = $h2Node->text();
                        $attributes = [];

                        $categoryNode->filter('dl')->each(function ($node) use (&$attributes) {
                            $key = $node->filter('dt span')->text();
                            $value = $node->filter('dd span')->text();
                            $attributes[$key] = $value;
        
                        });

                        $section[$category] = $attributes;     
                    });
                });
            });

            $total[] = $section;

            foreach($total as $sections) {
                foreach ($sections as $sectionName => $attributes) {
                   $category = Categories::firstOrCreate(['name' => $sectionName]);
                    foreach($attributes as $key => $value) {
                        Attributes::create([
                            'laptop_id' => $url->id,
                            'category_id' => $category->id,
                            'name' => $key,
                            'value' => $value,
                        ]);
                    }
                }
            } 
        }catch(Exception $e) {
            echo "Ошибка при получении данных для $url: " . $e->getMessage() . "\n";
        }
    }

    }
}
