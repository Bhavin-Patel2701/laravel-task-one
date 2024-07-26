<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Maatwebsite\Excel\Concerns\FromCollection;

// class ProductExport implements FromCollection
class ProductExport implements FromQuery, WithHeadings, WithMapping, ShouldQueue
{
    /* public function collection()
    {
        return Product::all();
    } */

    use Exportable;

    protected $user_id;
    protected $user_role;

    // Constructor to accept user ID and role
    public function __construct($user_id, $user_role)
    {
        $this->user_id = $user_id;
        $this->user_role = $user_role;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        $query = Product::select('product.*','category.title as category_title','child_category.title as child_category_title', 'users.firstname as product_username', 'users.role as product_username_role')
        ->leftJoin('users','users.id','=','product.user_id')
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id');

        if ($this->user_role !== "admin") {
            $query = $query->where('product.user_id', $this->user_id);

            /* $query = $query->where('product.status', 'active')
            ->orWhere(function($allproduct_entries) {
                $allproduct_entries->where('product.status', 'inactive')
                      ->where('product.user_id', $this->user_id);
            }); */
        }

        return $query;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        $status = $this->user_role !== "admin" ? ($row->status === 'active' ? 'Approved' : 'Not Approved') : $row->status;
        $formate_price = number_format($row->price, 2, '.', '');
        $formate_quantity = ((string) $row->quantity === "0") ? "0" : (string) $row->quantity;

        $data = [
            $row->category_title,
            $row->child_category_title ?? '',
            $row->title,
            $row->description ?? '',
            $status,
            $formate_quantity,
            $formate_price,
            $row->sku
        ];

        if ($this->user_role === "admin") {
            $data[] = $row->product_username;
            $data[] = $row->product_username_role;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'Product Category',
            'Child Category',
            'Product Name',
            'Description',
            'Status',
            'Product Quantity',
            'Product Price',
            'SKU'
        ];

        if ($this->user_role === "admin") {
            $headings[] = 'Seller Name';
            $headings[] = 'Seller Role';
        }

        return $headings;
    }
}
