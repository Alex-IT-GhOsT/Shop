<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laptop;
use App\Models\Brand;
use App\Models\Brands;

class LinkBrandsToLaptops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands-laptops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Присваивание товаров их брендам на основе названия товаров';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $laptops = Laptop::all();
        $brands = Brands::all();

        foreach($laptops as $laptop) {
            foreach($brands as $brand) {
                if (strpos($laptop->product_name, $brand->name) !== false) {
                    $laptop->brand_id = $brand->id;
                    $laptop->save();
                    break;
                } 
            }
        }
    }
}
