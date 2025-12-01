<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Section;
use App\Models\State;
use Illuminate\Database\Seeder;

class PostSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create European Countries
        $germany = Country::create([
            'name' => 'Germany',
            'post_type' => 'paid',
            'currency_symbol' => '€',
        ]);

        $france = Country::create([
            'name' => 'France',
            'post_type' => 'free',
            'currency_symbol' => '€',
        ]);

        $uk = Country::create([
            'name' => 'United Kingdom',
            'post_type' => 'paid',
            'currency_symbol' => '£',
        ]);

        $italy = Country::create([
            'name' => 'Italy',
            'post_type' => 'free',
            'currency_symbol' => '€',
        ]);

        $spain = Country::create([
            'name' => 'Spain',
            'post_type' => 'free',
            'currency_symbol' => '€',
        ]);

        // Create States for Germany
        $bavaria = State::create(['country_id' => $germany->id, 'name' => 'Bavaria']);
        $berlin = State::create(['country_id' => $germany->id, 'name' => 'Berlin']);
        $hamburg = State::create(['country_id' => $germany->id, 'name' => 'Hamburg']);

        // Create Cities for Germany
        City::create(['state_id' => $bavaria->id, 'name' => 'Munich']);
        City::create(['state_id' => $bavaria->id, 'name' => 'Nuremberg']);
        City::create(['state_id' => $berlin->id, 'name' => 'Berlin']);
        City::create(['state_id' => $hamburg->id, 'name' => 'Hamburg']);

        // Create States for France
        $iledefrance = State::create(['country_id' => $france->id, 'name' => 'Île-de-France']);
        $provence = State::create(['country_id' => $france->id, 'name' => 'Provence-Alpes-Côte d\'Azur']);

        // Create Cities for France
        City::create(['state_id' => $iledefrance->id, 'name' => 'Paris']);
        City::create(['state_id' => $iledefrance->id, 'name' => 'Versailles']);
        City::create(['state_id' => $provence->id, 'name' => 'Marseille']);
        City::create(['state_id' => $provence->id, 'name' => 'Nice']);

        // Create States for UK
        $england = State::create(['country_id' => $uk->id, 'name' => 'England']);
        $scotland = State::create(['country_id' => $uk->id, 'name' => 'Scotland']);

        // Create Cities for UK
        City::create(['state_id' => $england->id, 'name' => 'London']);
        City::create(['state_id' => $england->id, 'name' => 'Manchester']);
        City::create(['state_id' => $scotland->id, 'name' => 'Edinburgh']);
        City::create(['state_id' => $scotland->id, 'name' => 'Glasgow']);

        // Create States for Italy
        $lazio = State::create(['country_id' => $italy->id, 'name' => 'Lazio']);
        $lombardy = State::create(['country_id' => $italy->id, 'name' => 'Lombardy']);

        // Create Cities for Italy
        City::create(['state_id' => $lazio->id, 'name' => 'Rome']);
        City::create(['state_id' => $lombardy->id, 'name' => 'Milan']);

        // Create States for Spain
        $madrid = State::create(['country_id' => $spain->id, 'name' => 'Madrid']);
        $catalonia = State::create(['country_id' => $spain->id, 'name' => 'Catalonia']);

        // Create Cities for Spain
        City::create(['state_id' => $madrid->id, 'name' => 'Madrid']);
        City::create(['state_id' => $catalonia->id, 'name' => 'Barcelona']);

        // Create Sections
        $news = Section::create(['name' => 'News']);
        $events = Section::create(['name' => 'Events']);
        $jobs = Section::create(['name' => 'Jobs']);
        $marketplace = Section::create(['name' => 'Marketplace']);
        $services = Section::create(['name' => 'Services']);

        // Create Categories for News
        Category::create(['section_id' => $news->id, 'name' => 'Local News']);
        Category::create(['section_id' => $news->id, 'name' => 'International News']);
        Category::create(['section_id' => $news->id, 'name' => 'Sports']);
        Category::create(['section_id' => $news->id, 'name' => 'Technology']);

        // Create Categories for Events
        Category::create(['section_id' => $events->id, 'name' => 'Concerts']);
        Category::create(['section_id' => $events->id, 'name' => 'Conferences']);
        Category::create(['section_id' => $events->id, 'name' => 'Workshops']);
        Category::create(['section_id' => $events->id, 'name' => 'Festivals']);

        // Create Categories for Jobs
        Category::create(['section_id' => $jobs->id, 'name' => 'Full-time']);
        Category::create(['section_id' => $jobs->id, 'name' => 'Part-time']);
        Category::create(['section_id' => $jobs->id, 'name' => 'Freelance']);
        Category::create(['section_id' => $jobs->id, 'name' => 'Internship']);

        // Create Categories for Marketplace
        Category::create(['section_id' => $marketplace->id, 'name' => 'Electronics']);
        Category::create(['section_id' => $marketplace->id, 'name' => 'Furniture']);
        Category::create(['section_id' => $marketplace->id, 'name' => 'Vehicles']);
        Category::create(['section_id' => $marketplace->id, 'name' => 'Real Estate']);

        // Create Categories for Services
        Category::create(['section_id' => $services->id, 'name' => 'Home Services']);
        Category::create(['section_id' => $services->id, 'name' => 'Professional Services']);
        Category::create(['section_id' => $services->id, 'name' => 'Education']);
        Category::create(['section_id' => $services->id, 'name' => 'Health & Wellness']);
    }
}
