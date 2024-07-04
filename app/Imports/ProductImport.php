<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $main_category = Category::firstOrCreate(
            ['title' => $row['product_category']],
            ['status' => 'active']
        );

        $child_category = Category::where('title', $row['child_category'])->first();

        if (!$child_category) {
            $child_category = Category::create([
                'parent_id' => $main_category->id,
                'title' => $row['child_category'],
                'status' => 'active'
            ]);
        } else {
            $child_category->parent_id = $main_category->id;
            $child_category->save();
        }

        return new Product([
            'category_id' => $main_category->id,
            'child_category_id' => $child_category->id,
            'title' => $row['product_name'],
            'description' => $row['description'],
            'status' => $row['status'],
            'quantity' => $row['product_quantity'],
            'price' => $row['product_price']
        ]);
    }
}
