<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Band;
use App\BandMember;
use App\User;

class BandTest extends TestCase
{
    use DatabaseTransactions;

    public function testBandMembersExample() {
        
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
        
        $this->be($user);
        $this->visit('/band')
            ->see('Jorge Smith\'s Band');
            
        $this->post('/band/members/add', ['email' => 'paulo@smith.com'])
            ->see('success');
        $this->visit('/band')
            ->see('paulo@smith.com');
        $response = $this->call('POST', 'band/members/add', ['email' => 'jorge@smith.com']);
        $this->assertEquals($response->getStatusCode(), 406);
    }
}
