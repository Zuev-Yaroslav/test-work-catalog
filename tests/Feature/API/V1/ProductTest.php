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
        $response->assertCreated();
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
            ->put(route('api.v1.products.update', $product->id), $data);
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
        $response = $this
            ->withToken($user->createToken('token')->plainTextToken)
            ->post(route('api.v1.products.store'), $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['price']);
    }
    public function test_store_update_destroy_force_destroy_restore_trashed_index_without_token_product()
    {
        Category::factory(10)->create();
        $product = Product::factory()->create();
        $data = [
            "name" => 'laboriosam',
            "description" => "Sint assumenda ut fuga distinctio tenetur accusamus velit voluptas. Eveniet odio aut sed aut odio. Excepturi facilis expedita similique sequi commodi consectetur.",
            "price" => 32436,
            "category_id" => 7
        ];
        $response = $this
            ->post(route('api.v1.products.store'), $data);
        $response->assertUnauthorized();

        $response = $this
            ->put(route('api.v1.products.update', $product->id));
        $response->assertUnauthorized();

        $response = $this
            ->delete(route('api.v1.products.destroy', $product->id));
        $response->assertUnauthorized();

        $response = $this
            ->post(route('api.v1.products.restore', $product->id));
        $response->assertUnauthorized();

        $response = $this
            ->get(route('api.v1.products.trashed.index', $product->id));
        $response->assertUnauthorized();

        $response = $this
            ->delete(route('api.v1.products.force-destroy', $product->id));
        $response->assertUnauthorized();
    }
    public function test_update_one_field_of_product()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $product = Product::factory()->create();

        $fields = ['name', 'description', 'price', 'category_id'];
        foreach ($fields as $field) {
            $errorFields = $fields;
            $index = array_search($field, $fields);
            unset($errorFields[$index]);
            $response = $this
                ->withToken($user->createToken('token')->plainTextToken)
                ->put(route('api.v1.products.update', $product->id), [$field => 'updated ' . $field]);
            $response->assertUnprocessable();

            $response->assertJsonValidationErrors($errorFields);
        }
    }
    public function test_delete_and_restore_product()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $product = Product::factory()->create();
        $id = $product->id;

        $token = $user->createToken('token')->plainTextToken;
        $response = $this
            ->withToken($token)
            ->delete(route('api.v1.products.destroy', $product->id));
        $response->assertNoContent();
        $product = Product::find($id);
        $this->assertNull($product);
        $product = Product::onlyTrashed()->find($id);
        $this->assertNotNull($product);

        $response = $this
            ->withToken($token)
            ->post(route('api.v1.products.restore', $id));

        $response->assertOk();

        $product = Product::onlyTrashed()->find($id);
        $this->assertNull($product);
    }

    public function test_force_destroy_product()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $product = Product::factory()->create();
        $id = $product->id;

        $token = $user->createToken('token')->plainTextToken;
        $response = $this
            ->withToken($token)
            ->delete(route('api.v1.products.force-destroy', $product->id));
        $response->assertNoContent();
        $product = Product::find($id);
        $this->assertNull($product);
        $product = Product::onlyTrashed()->find($id);
        $this->assertNull($product);

        $response = $this
            ->withToken($token)
            ->post(route('api.v1.products.restore', $id));

        $response->assertNotFound();
    }

    public function test_trashed_index_product()
    {
        $user = User::factory()->create();
        Category::factory(10)->create();
        $product = Product::factory()->create();
        $id = $product->id;

        $token = $user->createToken('token')->plainTextToken;
        $response = $this
            ->withToken($token)
            ->delete(route('api.v1.products.force-destroy', $product->id));
        $response->assertNoContent();
        $product = Product::find($id);
        $this->assertNull($product);
        $product = Product::onlyTrashed()->find($id);
        $this->assertNull($product);

        $response = $this
            ->withToken($token)
            ->post(route('api.v1.products.restore', $id));

        $response->assertNotFound();
    }
}
