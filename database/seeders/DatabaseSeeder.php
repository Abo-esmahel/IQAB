<?php

namespace Database\Seeders;

use App\Enums\PhoneNumberStatus;
use App\Enums\UserStatus;
use App\Models\ContactMethod;
use App\Models\MarketService;
use App\Models\Offer;
use App\Models\PhoneNumber;
use App\Models\TelegramService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@iqab.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'status' => UserStatus::Active->value,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Demo User
        User::firstOrCreate(
            ['email' => 'demo@iqab.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'status' => UserStatus::Active->value,
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Sample Phone Numbers
        $countries = [
            ['country' => 'United States', 'code' => '+1', 'numbers' => ['2025551234', '2025555678', '2025559012']],
            ['country' => 'United Kingdom', 'code' => '+44', 'numbers' => ['7911123456', '7911123457']],
            ['country' => 'Germany', 'code' => '+49', 'numbers' => ['15112345678', '15112345679']],
            ['country' => 'Saudi Arabia', 'code' => '+966', 'numbers' => ['501234567', '501234568', '501234569']],
            ['country' => 'UAE', 'code' => '+971', 'numbers' => ['501234567', '501234568']],
        ];

        foreach ($countries as $country) {
            foreach ($country['numbers'] as $i => $num) {
                PhoneNumber::firstOrCreate(
                    ['phone_number' => $country['code'] . $num],
                    [
                        'provider' => 'default',
                        'country' => $country['country'],
                        'country_code' => $country['code'],
                        'provider_number_id' => 'prov_' . $num,
                        'status' => PhoneNumberStatus::Available->value,
                        'price' => rand(50, 500) / 10,
                        'expires_at' => now()->addDays(rand(7, 30)),
                    ]
                );
            }
        }

        // Telegram Services
        TelegramService::firstOrCreate(
            ['name' => 'Account Lookup'],
            [
                'type' => 'account_lookup',
                'price' => 25.00,
                'description' => 'Look up basic information about a Telegram account.',
                'is_active' => true,
            ]
        );

        TelegramService::firstOrCreate(
            ['name' => 'Account Report'],
            [
                'type' => 'account_report',
                'price' => 50.00,
                'description' => 'Report a Telegram account for policy violations.',
                'is_active' => true,
            ]
        );

        TelegramService::firstOrCreate(
            ['name' => 'Account Information'],
            [
                'type' => 'account_information',
                'price' => 75.00,
                'description' => 'Get detailed information about a Telegram account.',
                'is_active' => true,
            ]
        );

        // Market Services
        MarketService::firstOrCreate(
            ['slug' => 'instagram-account-analysis'],
            [
                'name' => 'Instagram Account Analysis',
                'description' => 'Get a detailed analysis of any public Instagram account including follower growth, engagement rates, and content performance metrics.',
                'short_description' => 'Detailed Instagram account metrics and engagement analysis.',
                'category' => 'Social Media',
                'price' => 15.00,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        MarketService::firstOrCreate(
            ['slug' => 'twitter-profile-report'],
            [
                'name' => 'Twitter/X Profile Report',
                'description' => 'Comprehensive report on any public Twitter/X profile including tweet analysis, follower authenticity check, and engagement statistics.',
                'short_description' => 'Full Twitter profile analysis with authenticity checks.',
                'category' => 'Social Media',
                'price' => 12.00,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        MarketService::firstOrCreate(
            ['slug' => 'email-reputation-check'],
            [
                'name' => 'Email Reputation Check',
                'description' => 'Verify email reputation and check against known spam databases. Get deliverability score and risk assessment.',
                'short_description' => 'Check email reputation and deliverability score.',
                'category' => 'Verification',
                'price' => 5.00,
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        MarketService::firstOrCreate(
            ['slug' => 'phone-number-intelligence'],
            [
                'name' => 'Phone Number Intelligence',
                'description' => 'Get intelligence data about a phone number including carrier info, line type, location, and risk score.',
                'short_description' => 'Carrier, location, and risk data for any phone number.',
                'category' => 'Verification',
                'price' => 8.00,
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        MarketService::firstOrCreate(
            ['slug' => 'domain-age-check'],
            [
                'name' => 'Domain Age Check',
                'description' => 'Check domain registration date, expiration, registrar info, and historical data to assess domain trustworthiness.',
                'short_description' => 'Domain registration details and trust assessment.',
                'category' => 'Verification',
                'price' => 3.00,
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        MarketService::firstOrCreate(
            ['slug' => 'whatsapp-number-validator'],
            [
                'name' => 'WhatsApp Number Validator',
                'description' => 'Verify if a phone number is registered on WhatsApp and get profile information if available.',
                'short_description' => 'Verify WhatsApp registration and profile data.',
                'category' => 'Messaging',
                'price' => 7.00,
                'is_active' => true,
                'sort_order' => 6,
            ]
        );

        // Contact Methods
        ContactMethod::firstOrCreate(
            ['name' => 'WhatsApp Support'],
            [
                'type' => 'whatsapp',
                'value' => '+966501234567',
                'url' => 'https://wa.me/966501234567',
                'color' => '#25D366',
                'description' => 'Chat with us on WhatsApp for instant support.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        ContactMethod::firstOrCreate(
            ['name' => 'Telegram Channel'],
            [
                'type' => 'telegram',
                'value' => '@IQAB_Support',
                'url' => 'https://t.me/IQAB_Support',
                'color' => '#0088cc',
                'description' => 'Join our Telegram channel for updates and support.',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        ContactMethod::firstOrCreate(
            ['name' => 'Email Support'],
            [
                'type' => 'email',
                'value' => 'support@iqab.com',
                'url' => 'mailto:support@iqab.com',
                'color' => '#EA4335',
                'description' => 'Send us an email for detailed inquiries.',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        ContactMethod::firstOrCreate(
            ['name' => 'Twitter/X'],
            [
                'type' => 'twitter',
                'value' => '@IQABOfficial',
                'url' => 'https://twitter.com/IQABOfficial',
                'color' => '#1DA1F2',
                'description' => 'Follow us on Twitter for news and updates.',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        // Offers
        Offer::firstOrCreate(
            ['slug' => 'new-user-welcome-offer'],
            [
                'title' => 'New User Welcome Offer',
                'badge' => 'NEW',
                'description' => 'Get a special discount on your first phone number purchase. Start using virtual numbers today at a discounted rate!',
                'original_price' => 50.00,
                'offer_price' => 29.99,
                'discount_percent' => 40,
                'type' => 'service',
                'related_service_id' => null,
                'related_number_id' => null,
                'cta_text' => 'Get Started',
                'cta_url' => null,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 12,
            ]
        );

        Offer::firstOrCreate(
            ['slug' => 'bulk-number-discount'],
            [
                'title' => 'Bulk Number Discount',
                'badge' => 'HOT',
                'description' => 'Buy 3 or more phone numbers and save big. Perfect for businesses that need multiple virtual numbers.',
                'original_price' => 150.00,
                'offer_price' => 99.99,
                'discount_percent' => 33,
                'type' => 'number',
                'related_service_id' => null,
                'related_number_id' => null,
                'cta_text' => 'Buy Bundle',
                'cta_url' => null,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(14),
                'usage_limit' => 50,
                'used_count' => 8,
            ]
        );

        Offer::firstOrCreate(
            ['slug' => 'instagram-analysis-premium'],
            [
                'title' => 'Instagram Analysis Premium',
                'badge' => 'SALE',
                'description' => 'Get the full Instagram Account Analysis at a discounted price. Includes follower growth tracking and engagement metrics.',
                'original_price' => 15.00,
                'offer_price' => 9.99,
                'discount_percent' => 33,
                'type' => 'service',
                'related_service_id' => MarketService::where('slug', 'instagram-account-analysis')->first()?->id,
                'related_number_id' => null,
                'cta_text' => 'Order Now',
                'cta_url' => null,
                'image' => null,
                'is_featured' => true,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(7),
                'usage_limit' => null,
                'used_count' => 0,
            ]
        );
    }
}
