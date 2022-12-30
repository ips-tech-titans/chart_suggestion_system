<?php

namespace App\Http\Controllers;

use App\Helper\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class CSVController extends Controller
{

    public $fileHelper;
    public function __construct()
    {
        $this->fileHelper = new FileHelper();
    }

    public function index()
    {
        return view('csv.index');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'file' => ['required', 'mimes:csv'],
        ]);

        $file = $request->file('file');
        $path = $this->fileHelper->save($file);

        $name = pathinfo($path, PATHINFO_FILENAME);
        return redirect()->route('csv.show', $name);
    }

    public function show($fileName)
    {

        $path = public_path('csv/') . $fileName . ".csv";
        $data = $this->fileHelper->csvtojson($path);

        dd($data);
        // $columns = 
        // return $fileName;
    }
}
