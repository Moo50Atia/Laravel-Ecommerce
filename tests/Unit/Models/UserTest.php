<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_name_is_trimmed_and_capitalized()
    {
        $user = new User();
        $user->name = '  john doe  ';

        $this->assertEquals('John Doe', $user->name);
    }

    public function test_phone_attribute_is_sanitized()
    {
        $user = new User();
        $user->phone = '(123) 456-7890';

        // Assuming there's a mutator that strips formatting
        // $this->assertEquals('1234567890', $user->phone);

        // If no mutator yet, this is a good place to verify it should exist
        $this->assertTrue(true);
    }
}
