<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'Advanced Post System', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'A comprehensive classified ads platform', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'general'],
            
            // SEO Settings
            ['key' => 'seo_meta_title', 'value' => 'Advanced Post System - Classified Ads', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_meta_description', 'value' => 'Find and post classified ads in your area. Jobs, real estate, services, and more.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_meta_keywords', 'value' => 'classified ads, jobs, real estate, services, marketplace', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_og_image', 'value' => null, 'type' => 'image', 'group' => 'seo'],
            
            // Social Media
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => '', 'type' => 'text', 'group' => 'social'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'info@example.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+1234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '', 'type' => 'textarea', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Site settings created successfully!');
    }
}
