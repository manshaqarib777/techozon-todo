<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Todo;
use App\Models\User;

class TodoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApplication()
    {

        $user = factory(User::class)->create();
        //dd($user);
        $this->actingAs($user);
    }

}
