<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Goutte\Client;
use League\Csv\Writer;

class CarParser
{
    private const CONDITION = 'Used';
    private const GOOGLE_PRODUCT_CATEGORY = '123';
    private const STORE_CODE = 'xpremium';
    private const VEHICLE_FULFILLMENT = 'in_store:premium';
    private Writer $csvWriter;

    public function __construct()
    {
        $this->csvWriter = Writer::createFromPath('cars.csv', 'w+');
        $this->csvWriter->insertOne([
            'Condition',
            'google_product_category',
            'store_code',
            'vehicle_fulfillment(option:store_code)',
            'Brand',
            'Model',
            'Year',
            'Color',
            'Mileage',
            'Price',
            'VIN',
            'image_link',
            'link_template',
        ]);
    }

    public function parseCars(): void
    {
        $client = new Client();

        $crawler = $client->request('POST', 'https://premiumcarsfl.com/listing-list-full/');

        $cars = $crawler->filter('.car-item')->each(function ($node) {
            $carData = [
                'Condition' => self::CONDITION,
                'google_product_category' => self::GOOGLE_PRODUCT_CATEGORY,
                'store_code' => self::STORE_CODE,
                'vehicle_fulfillment(option:store_code)' => self::VEHICLE_FULFILLMENT,
                'Brand' => $node->filter('.car-make')->text(),
                'Model' => $node->filter('.car-model')->text(),
                'Year' => $node->filter('.car-year')->text(),
                'Color' => $node->filter('.car-color')->text(),
                'Mileage' => $node->filter('.car-mileage')->text() . ' miles',
                'Price' => (int)preg_replace('/\D/', '', $node->filter('.car-price-balance')->text()),
                'VIN' => $node->filter('.car-vin')->text(),
                'image_link' => $node->filter('.car-gallery img')->eq(1)->attr('fetchpriority'),
                'link_template' => $node->filter('.car-title a')->attr('href') . '?store=' . self::STORE_CODE,
            ];

            $this->csvWriter->insertOne($carData);

            return $carData;
        });

        $this->csvWriter->output('cars.csv');
    }
}

$parser = new CarParser();
$parser->parseCars();