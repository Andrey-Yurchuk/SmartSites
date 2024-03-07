### Project Name: Car Parser for premiumcarsfl.com

### Description:

This parser is designed to collect data about cars from all pages of the catalog on the website 
https://premiumcarsfl.com/listing-list-full/. The collected data is saved to a CSV file.

### Requirements:

PHP 8.2+

### Installation:

1. git clone <this repository> 
2. composer install
3. ~ php ../src/CarParser.php

The cars.csv file must contain information about the cars.

#### Libraries:

The parser uses the following libraries:
- Goutte (for parsing HTML)
- League\Csv (for creating the CSV file)




