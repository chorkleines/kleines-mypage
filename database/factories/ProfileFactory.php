<?php

namespace Database\Factories;

use App\Enums\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $yearDiff = random_int(0, 5);
        $birthYear = date('Y') - $yearDiff - 19;
        $grade = (date('Y') - $yearDiff) % 100;
        $birthdayMin = strtotime(date($birthYear.'-04-02'));
        $birthdayMax = strtotime(date($birthYear + 1 .'-04-01'));
        $birthday = date('Y-m-d', rand($birthdayMin, $birthdayMax));

        $isFemale = (bool) random_int(0, 1);
        $partIndex = random_int(0, 1) + ($isFemale ? 0 : 2);

        return [
            'grade' => $grade,
            'part' => Part::getValues()[$partIndex],
            'last_name' => fake()->lastName(),
            'first_name' => $isFemale
                ? fake()->firstNameFemale()
                : fake()->firstNameMale(),
            'birthday' => $birthday,
        ];
    }
}
