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

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Product::query()
        ->select(
            'product.title',
            'product.description',
            'product.status',
            'product.quantity',
            'product.price',
            'category.title as category_title',
            'child_category.title as childcat_title'
        )
        ->leftJoin('category','category.id','=','product.category_id')
        ->leftJoin('category as child_category','child_category.id','=','product.child_category_id');
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->category_title,
            $row->childcat_title,
            $row->title,
            $row->description,
            $row->status,
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
