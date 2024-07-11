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
        $query = Product::select('product.*','category.title as category_title','child_category.title as child_category_title')
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id');

        if ($this->user_role !== "admin") {
            $query = $query->where('product.status', 'active')
            ->orWhere(function($allproduct_entries) {
                $allproduct_entries->where('product.status', 'inactive')
                      ->where('product.user_id', $this->user_id);
            });
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

        return [
            $row->category_title,
            $row->child_category_title ?? 'No Child Category',
            $row->title,
            $row->description ?? 'No Description',
            $status,
            $row->quantity,
            $row->price
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Product Category',
            'Child Category',
            'Product Name',
            'Description',
            'Status',
            'Product Quantity',
            'Product Price'
        ];
    }
}
