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

    public function testBandMembers() {
        
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
        
        $jorge = User::create_from_facebook_login($args);
        
        $this->be($jorge);
        $this->visit('/band')
            ->see('Jorge Smith\'s Band');
            
        $band = Band::where('name', 'Jorge Smith\'s Band')->first();
        
        $this->visit('/auth/me')
            ->seeJson([
                'name' => 'Jorge Smith'
            ]);
            
        $this->post('/band/members/add', ['email' => 'paulo@smith.com'])
            ->see('success');
            
        $this->visit('/band/members/paulo@smith.com')
            ->see('paulo@smith.com');
            
        $this->visit('/band')
            ->see('paulo@smith.com');
            
            
        //Make sure a duplicate email is kicked out
        $response = $this->call('POST', 'band/members/add', ['email' => 'jorge@smith.com']);
        $this->assertEquals($response->getStatusCode(), 406);
        
        //Can edit a member's email address
        $this->post('/band/members/edit', ['oldemail' => 'paulo@smith.com', 'newemail' => 'paulo@jones.com'])
            ->see('success');
            
        //If the user has linked their facebook account, don't allow any edits
        $response = $this->call('POST', 'band/members/edit', ['oldemail' => 'jorge@smith.com', 'newemail' => 'jorge@jones.com']);
        $this->assertEquals($response->getStatusCode(), 406);
        
        $this->post('/band/edit', ['name' => 'The Trogdorlites'])
            ->seeJson(['name' => 'The Trogdorlites']);

        $paulo = User::where('email', 'paulo@jones.com')->first();
        
        $this->assertTrue($jorge->hasPermission('manage-band-users', $band));
        $this->assertTrue(!$paulo->hasPermission('manage-band-users', $band));
        
        $this->be($paulo);
        //Paulo should not be able to add a band member
        $response = $this->call('POST', 'band/members/add', ['email' => 'ringo@miller.com']);
        $this->assertEquals($response->getStatusCode(), 403);
        
        $paulos_name = 'Paulo Jones';
        
        $this->be($jorge);
        
        $this->post('/band/members/edit', ['oldemail' => 'paulo@jones.com', 'newemail' => 'paulo@jones.com', 'name' => $paulos_name])
            ->see('success');
        
        $paulo = User::where('email', 'paulo@jones.com')->first();
        
        $this->assertEquals($paulo->name, $paulos_name);
    }
}