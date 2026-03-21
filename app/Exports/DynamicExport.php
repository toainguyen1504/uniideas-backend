<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class DynamicExport implements FromCollection
{
    protected $headings;
    protected $data;
    
    public function __construct($data, array $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

   /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

   /**
    * @return array
    */
    public function headings(): array
    {
        return $this->headings;
    }

   /**
    * Map data for each row
    *
    * @param mixed $item
    * @return array
    */
    public function map($item): array
    {
        return is_array($item) ? $item : $item->toArray();
    }
}
