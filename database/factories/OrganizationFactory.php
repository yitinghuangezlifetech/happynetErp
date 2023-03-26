<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => uniqid(),
            'system_no' => fake()->word.fake()->randomNumber,
            'name' => fake()->company,
            'id_no' => fake()->randomNumber,
            'manager' => fake()->name,
            'manager_id_no' => fake()->randomNumber,
            'mobile' => fake()->phoneNumber,
            'tel' => fake()->e164PhoneNumber     ,
            'tel_1' => '#'.rand(1111, 9999),
            'county' => fake()->city,
            'district' => fake()->city,
            'zipcode' => fake()->postcode,
            'address' => fake()->address,
            'bill_county' => fake()->city,
            'bill_district' => fake()->state,
            'bill_zipcode' => fake()->postcode,
            'bill_address' => fake()->address,
            'business_county' => fake()->city,
            'business_district' => fake()->state,
            'business_zipcode' => fake()->postcode,
            'business_address' => fake()->address,
            'email' => fake()->email,
            'expiry_day' => date('Y-m-d', strtotime(date('Y-m-d'.' +'.rand(1,5).' year'))),
            'bill_day' => 5,
            'bill_contact' => fake()->name,
            'bill_contact_mobile' => fake()->phoneNumber,
            'bill_contact_tel' => fake()->e164PhoneNumber     ,
            'bill_contact_tel_1' => '#'.rand(1111, 9999),
            'bill_contact_mail' => fake()->email,
            'bill_contact_mail_1' => fake()->email,
            'setup_contact' => fake()->name,
            'setup_contact_mobile' => fake()->phoneNumber,
            'setup_contact_tel' => fake()->e164PhoneNumber     ,
            'setup_contact_tel_1' => '#'.rand(1111, 9999),
            'setup_contact_mail' => fake()->email,
            'setup_contact_mail_1' => fake()->email,
            'status' => 1
        ];
    }
}
