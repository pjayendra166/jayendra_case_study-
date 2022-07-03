<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_product()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->get('/api/products');

        $response->assertStatus(200);
    }

    public function test_required_field_for_new_product()
    {
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->post('/api/products');

        $response->assertStatus(422);
    }

    public function test_create_new_product()
    {
        $faker = \Faker\Factory::create();
        $data = [
            'name' => $faker->unique()->name(),
            'price' => '10.250',
            'quantity' => '2',
            'categry_id' => '1',
            'description' => 'Test Description',
            'avatar' => 'Test avatar'
        ];
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->post('/api/products',$data);

        $response->assertStatus(200);
    }

    public function test_product_details()
    {
        $id = 2;
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->get('/api/products/'.$id);

        $response->assertStatus(200);
    }


    public function test_product_delete()
    {
        $id = 5;
        $response = $this->withHeaders(['Accept' => 'application/json','Authorization'=>'Bearer '.'23|HyIwWm2SNtYPrUmoXTc3rvOKhDzYGBrrRvbZrvym'])
                    ->delete('/api/products/'.$id);

        $response->assertStatus(200);
    }


}
