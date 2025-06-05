<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Fabric;
use App\Models\Wood;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();
        $color = Color::first();
        $fabric = Fabric::first();
        $wood = Wood::first();

        Product::create([
            'name' => 'Modern Sofa',
            'price' => 499.99,
            'description' => 'A comfortable modern sofa.',
            'available_quantity' => 10,
            'category_id' => $category ? $category->id : 1,
            'color_id' => $color ? $color->id : 1,
            'model_3d' => 'sofa_model.glb',
            'fabric_id' => $fabric ? $fabric->id : 1,
            'wood_id' => $wood ? $wood->id : 1,
        ]);
    }
}
