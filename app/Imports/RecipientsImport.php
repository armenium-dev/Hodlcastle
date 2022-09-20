<?php

namespace App\Imports;

use App\Models\Recipient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\toCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RecipientsImport implements ToModel{
	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row){
		return new Recipient([
			'email'      => $row[0],
			'first_name' => $row[1],
			'last_name'  => $row[2],
			'department' => $row[3],
			'location'   => $row[4],
			'mobile'     => $row[5],
			'comment'    => $row[6],
		]);
	}
	
	public function getCsvSettings():array{
		return [
			'delimiter' => ',',
			'input_encoding' => 'ISO-8859-1'
		];
	}
	
	public function headingRow(): int
	{
		return 1;
	}
}
