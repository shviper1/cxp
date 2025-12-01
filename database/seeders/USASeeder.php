<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class USASeeder extends Seeder
{
    public function run(): void
    {
        // Create USA Country
        $usa = Country::create([
            'name' => 'United States',
            'post_type' => 'free',
            'currency_symbol' => '$',
            'order' => 10,
            'amount' => null,
        ]);

        $this->command->info('🇺🇸 Creating USA with 50 states and major cities...');

        // Define states with their major cities
        $statesData = [
            'Alabama' => ['Birmingham', 'Montgomery', 'Mobile', 'Huntsville'],
            'Alaska' => ['Anchorage', 'Fairbanks', 'Juneau'],
            'Arizona' => ['Phoenix', 'Tucson', 'Mesa', 'Chandler', 'Scottsdale'],
            'Arkansas' => ['Little Rock', 'Fort Smith', 'Fayetteville'],
            'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'San Jose', 'Sacramento'],
            'Colorado' => ['Denver', 'Colorado Springs', 'Aurora', 'Fort Collins'],
            'Connecticut' => ['Hartford', 'New Haven', 'Stamford', 'Bridgeport'],
            'Delaware' => ['Wilmington', 'Dover', 'Newark'],
            'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville', 'Fort Lauderdale'],
            'Georgia' => ['Atlanta', 'Augusta', 'Columbus', 'Savannah'],
            'Hawaii' => ['Honolulu', 'Hilo', 'Kailua'],
            'Idaho' => ['Boise', 'Meridian', 'Nampa'],
            'Illinois' => ['Chicago', 'Aurora', 'Naperville', 'Joliet'],
            'Indiana' => ['Indianapolis', 'Fort Wayne', 'Evansville'],
            'Iowa' => ['Des Moines', 'Cedar Rapids', 'Davenport'],
            'Kansas' => ['Wichita', 'Overland Park', 'Kansas City'],
            'Kentucky' => ['Louisville', 'Lexington', 'Bowling Green'],
            'Louisiana' => ['New Orleans', 'Baton Rouge', 'Shreveport'],
            'Maine' => ['Portland', 'Lewiston', 'Bangor'],
            'Maryland' => ['Baltimore', 'Columbia', 'Germantown'],
            'Massachusetts' => ['Boston', 'Worcester', 'Springfield', 'Cambridge'],
            'Michigan' => ['Detroit', 'Grand Rapids', 'Warren', 'Ann Arbor'],
            'Minnesota' => ['Minneapolis', 'St. Paul', 'Rochester'],
            'Mississippi' => ['Jackson', 'Gulfport', 'Southaven'],
            'Missouri' => ['Kansas City', 'St. Louis', 'Springfield'],
            'Montana' => ['Billings', 'Missoula', 'Great Falls'],
            'Nebraska' => ['Omaha', 'Lincoln', 'Bellevue'],
            'Nevada' => ['Las Vegas', 'Henderson', 'Reno'],
            'New Hampshire' => ['Manchester', 'Nashua', 'Concord'],
            'New Jersey' => ['Newark', 'Jersey City', 'Paterson'],
            'New Mexico' => ['Albuquerque', 'Las Cruces', 'Rio Rancho'],
            'New York' => ['New York City', 'Buffalo', 'Rochester', 'Syracuse'],
            'North Carolina' => ['Charlotte', 'Raleigh', 'Greensboro', 'Durham'],
            'North Dakota' => ['Fargo', 'Bismarck', 'Grand Forks'],
            'Ohio' => ['Columbus', 'Cleveland', 'Cincinnati', 'Toledo'],
            'Oklahoma' => ['Oklahoma City', 'Tulsa', 'Norman'],
            'Oregon' => ['Portland', 'Salem', 'Eugene'],
            'Pennsylvania' => ['Philadelphia', 'Pittsburgh', 'Allentown'],
            'Rhode Island' => ['Providence', 'Warwick', 'Cranston'],
            'South Carolina' => ['Charleston', 'Columbia', 'North Charleston'],
            'South Dakota' => ['Sioux Falls', 'Rapid City', 'Aberdeen'],
            'Tennessee' => ['Nashville', 'Memphis', 'Knoxville', 'Chattanooga'],
            'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth'],
            'Utah' => ['Salt Lake City', 'West Valley City', 'Provo'],
            'Vermont' => ['Burlington', 'South Burlington', 'Rutland'],
            'Virginia' => ['Virginia Beach', 'Norfolk', 'Chesapeake', 'Richmond'],
            'Washington' => ['Seattle', 'Spokane', 'Tacoma', 'Vancouver'],
            'West Virginia' => ['Charleston', 'Huntington', 'Morgantown'],
            'Wisconsin' => ['Milwaukee', 'Madison', 'Green Bay'],
            'Wyoming' => ['Cheyenne', 'Casper', 'Laramie'],
        ];

        $stateOrder = 1;
        foreach ($statesData as $stateName => $cities) {
            // Create State
            $state = State::create([
                'country_id' => $usa->id,
                'name' => $stateName,
                'post_type' => 'inherit',
                'order' => $stateOrder++,
            ]);

            // Create Cities
            $cityOrder = 1;
            foreach ($cities as $cityName) {
                City::create([
                    'state_id' => $state->id,
                    'name' => $cityName,
                    'post_type' => 'inherit',
                    'order' => $cityOrder++,
                ]);
            }

            $cityCount = count($cities);
            $this->command->info("  ✓ {$stateName} ({$cityCount} cities)");
        }

        $totalCities = array_sum(array_map('count', $statesData));
        $this->command->info("✅ USA created with 50 states and {$totalCities} cities!");
    }
}
