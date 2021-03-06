<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function testBasicExample() {
        
        $args = (object)array(
            'token' => "Bunk-Token",
            'refreshToken' => null,
            'expiresIn' => "5110395",
            'id' => "12345678912345678",
            'nickname' => null,
            'name' => 'Jorge Smith',
            'email' => 'jorge@smith.com',
            'avatar' => 'asdf',
        );
        
        $user = User::create_from_facebook_login($args);
        $this->visit('/')
             ->see('material');
    }
}
