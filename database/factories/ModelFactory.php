<?php

use App\Models\UserRankEnum;

/**
 * Adds mock record revision info to a record.
 *
 * @param array $record
 * @param \Faker\Generator $faker
 * @return array
 */
$appendRevisionMock = function (array $record, Faker\Generator $faker): array
{
    $revision = [
        'status' => $faker->randomElement([
            'Submitted',
            'RejectedInfo',
            'RejectedDuplicate',
            'RejectedSource',
            'Accepted',
            'Superseded'
        ]),
        'user_id' => $faker->randomNumber(3),
        'previous_id' => $faker->randomElement([null, $faker->randomNumber(3)])
    ];

    return array_merge($record, $revision);
};


/*
 * User
 */

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {

    return [
        'username' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'rank' => $faker->randomElement([UserRankEnum::User, UserRankEnum::Editor]),
        'approved_organisations' => $faker->randomNumber(2),
        'approved_breaches' => $faker->randomNumber(2),
        'remember_token' => str_random(10),
    ];

});

/*
 * Tag
 */

$factory->define(App\Models\Tag::class, function (Faker\Generator $faker) {

    $name = ucwords($faker->word);

    return [
        'id' => str_slug($name),
        'name' => $name,
        'organisation_count' => $faker->randomNumber(3),
    ];

});

/*
 * Organisation
 */

$factory->define(App\Models\Organisation::class, function (Faker\Generator $faker) use ($appendRevisionMock) {

    $name = ucwords($faker->word);

    return $appendRevisionMock([
        'slug' => str_slug($name),
        'name' => $name . ' ' . $faker->randomElement(['LTD', 'PLC']),
        'styled_name' => $name,
        'registration_number' => $faker->randomNumber(8),
        'incorporated_on' => $faker->dateTimeThisCentury,
        'score' => $faker->randomFloat(4, 0, 10),
        'breach_count' => $faker->randomNumber(1),
    ], $faker);

});

/*
 * Breach
 */

$factory->define(App\Models\Breach::class, function (Faker\Generator $faker) use ($appendRevisionMock) {

    return $appendRevisionMock([
        'organisation_id' => $faker->randomNumber(3),
        'method' => $faker->randomElement(array_keys(\App\BreachData::$methods)),
        'summary' => $faker->text(500),
        'data_leaked' => $faker->randomElements(array_keys(\App\BreachData::$dataTypes), 6),
        'date_occurred' => $faker->dateTimeThisDecade,
        'people_affected' => $faker->randomElement([null, $faker->randomNumber(3)]),
        'previously_known' => $faker->randomElement([true, false]),
        'response_stance' => $faker->randomElement(array_keys(\App\BreachData::$responseStances)),
        'response_patched' => $faker->randomElement(array_keys(\App\BreachData::$responsePatched)),
        'response_customers_informed' => $faker->randomElement(array_keys(\App\BreachData::$responseCustomersInformed)),
        'source_url' => $faker->url,
        'source_name' => $faker->company,
        'more_url' => $faker->randomElement([null, $faker->url])
    ], $faker);

});