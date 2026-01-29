<?php

namespace API\V1;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_and_update_product_successfully()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $data = [
            "name" => 'laboriosam',
            "description" => "Sint assumenda ut fuga distinctio tenetur accusamus velit voluptas. Eveniet odio aut sed aut odio. Excepturi facilis expedita similique sequi commodi consectetur.",
            "price" => 324,
            "category_id" => 6
        ];
        $response = $this->withToken($user->createToken('token')
            ->plainTextToken)
            ->post(route('api.v1.products.store'), $data);
        $response->assertOk();
        $product = Product::first();

        $this->assertEquals($data, [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'category_id' => $product->category_id,
        ]);

        $data['name'] = '11111111111';

        $response = $this->withToken($user->createToken('token')
            ->plainTextToken)
            ->patch(route('api.v1.products.update', $product->id), ['name' => $data['name']]);
        $product->refresh();
        $response->assertOk();

        $this->assertEquals($data, [
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'category_id' => $product->category_id,
        ]);
    }
    public function test_set_most_big_price()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $data = [
            "name" => 'laboriosam',
            "description" => "Sint assumenda ut fuga distinctio tenetur accusamus velit voluptas. Eveniet odio aut sed aut odio. Excepturi facilis expedita similique sequi commodi consectetur.",
            "price" => 102399934942395348753994239424325645,
            "category_id" => 6
        ];
        $response = $this->withToken($user->createToken('token')
            ->plainTextToken)
            ->post(route('api.v1.products.store'), $data, [
                'Accept' => 'application/json'
            ]);

        $response->assertUnprocessable();
    }
}
