<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Analytics;
use Spatie\Analytics\Period;

class VerifyAnalytic implements Rule
{
    private $helper;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($helper)
    {
        $this->helper = $helper;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $check = false;
        $analytics = $this->helper->getView($value);

        try { // check if GA analytics ID is valid
            $eventData = $analytics->performQuery(Period::days(365),
            'ga:totalEvents', [
                'dimensions' => 'ga:eventCategory,ga:eventLabel'
            ]);
            $check = true;

        } catch (\Throwable $th) {
            $check = false;
        }

        return $check;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Provided Analytics Configurations are not valid';
    }
}
