<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DynamicImport implements ToModel, WithHeadingRow
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new $this->model($row);
    }
}