<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    protected $user_id;
    protected $user_role;

    private $skippedRowNumbers = [];
    private $getDuplicateRowNumbers = [];
    private $currentRow = 1;

    // Constructor to accept user ID and role
    public function __construct($user_id, $user_role)
    {
        $this->user_id = $user_id;
        $this->user_role = $user_role;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $this->currentRow++;

        $row['user_id'] = $this->user_id;
        $row['user_role'] = $this->user_role;

        $status = $this->user_role === "admin" ? 'active' : 'inactive';

        if ($this->user_role === "admin") {

            if (!empty($row['product_category'])) {

                // $check_category = Category::where('title', $row['product_category'])->first();

                $main_category = Category::firstOrCreate([
                    'title' => $row['product_category']
                ],[
                    'status' => $status,
                    'user_id' => $row['user_id']
                ]);

                if (!empty($row['child_category'])) {
                    $child_category = Category::firstOrCreate([
                        'title' => $row['child_category']
                    ],[
                        'parent_id' => $main_category->id,
                        'status' => $status,
                        'user_id' => $row['user_id']
                    ]);

                    $check_product = Product::where('title', $row['product_name'])
                    ->where('category_id', $main_category->id)
                    ->where('child_category_id', $child_category->id)->first();
                    if (empty($check_product)) {
                        return new Product([
                            'category_id' => $main_category->id,
                            'child_category_id' => $child_category->id,
                            'title' => $row['product_name'],
                            'description' => $row['description'],
                            'status' => $row['status'],
                            'quantity' => $row['product_quantity'],
                            'price' => $row['product_price'],
                            'user_id' => $row['user_id']
                        ]);
                    } else {
                        $this->getDuplicateRowNumbers[] = $this->currentRow;
                        return null;
                    }

                } else {

                    $check_product = Product::where('title', $row['product_name'])
                    ->where('category_id', $main_category->id)->first();
                    if (empty($check_product)) {
                        return new Product([
                            'category_id' => $main_category->id,
                            'child_category_id' => null,
                            'title' => $row['product_name'],
                            'description' => $row['description'],
                            'status' => $row['status'],
                            'quantity' => $row['product_quantity'],
                            'price' => $row['product_price'],
                            'user_id' => $row['user_id']
                        ]);
                    } else {
                        $this->getDuplicateRowNumbers[] = $this->currentRow;
                        return null;
                    }
                }
            } else {
                $this->skippedRowNumbers[] = $this->currentRow;
                return null;
            }
        } else {
            if (!empty($row['product_category'])) {

                $check_category = Category::where('status', "active")
                ->where('title', $row['product_category'])->first();
                if (!empty($check_category)) {

                    if (!empty($row['child_category'])) {

                        $check_child_category = Category::where('status', "active")
                        ->where('parent_id', $check_category->id)
                        ->where('title', $row['child_category'])->first();
                        if (!empty($check_child_category)) {

                            $check_product = Product::where('title', $row['product_name'])
                            ->where('category_id', $check_category->id)
                            ->where('child_category_id', $check_child_category->id)->first();
                            if (empty($check_product)) {

                                return new Product([
                                    'category_id' => $check_category->id,
                                    'child_category_id' => $check_child_category->id,
                                    'title' => $row['product_name'],
                                    'description' => $row['description'],
                                    'status' => $status,
                                    'quantity' => $row['product_quantity'],
                                    'price' => $row['product_price'],
                                    'user_id' => $row['user_id']
                                ]);

                            } else {
                                $this->getDuplicateRowNumbers[] = $this->currentRow;
                                return null;
                            }

                        } else {
                            $this->skippedRowNumbers[] = $this->currentRow;
                            return null;
                        }

                    } else {
                        
                        $check_product = Product::where('title', $row['product_name'])
                        ->where('category_id', $check_category->id)->first();
                        if (empty($check_product)) {

                            return new Product([
                                'category_id' => $check_category->id,
                                'child_category_id' => null,
                                'title' => $row['product_name'],
                                'description' => $row['description'],
                                'status' => $status,
                                'quantity' => $row['product_quantity'],
                                'price' => $row['product_price'],
                                'user_id' => $row['user_id']
                            ]);

                        } else {
                            $this->getDuplicateRowNumbers[] = $this->currentRow;
                            return null;
                        }

                    }

                } else {
                    $this->skippedRowNumbers[] = $this->currentRow;
                    return null;
                }

            } else {
                $this->skippedRowNumbers[] = $this->currentRow;
                return null;
            }            
        }
    }

    public function getSkippedRowNumbers()
    {
        return $this->skippedRowNumbers;
    }

    public function getDuplicateRowNumbers()
    {
        return $this->getDuplicateRowNumbers;
    }

}
