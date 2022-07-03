<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_category()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->get('/api/category')->assertStatus(200);
    }

    public function test_required_field()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->post('/api/category')->assertStatus(422);
    }

    public function test_new_category_field()
    {
        $data = [
            "name" => "Test"
        ];
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->post('/api/category',$data)->assertStatus(200);
    }
}
