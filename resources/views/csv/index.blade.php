@extends('csv.layout')

@section('content')
    <section class="database_select upload_csv">
        <div class="container">
            <div class="phase2">
                <div class="card">
                    <h3>Upload File</h3>
                    <form action="{{ route('csv-store') }}" method="POST" enctype="multipart/form-data" class="file_form">
                        @csrf                    
                        <div class="drop_box">
                            <p>Files Supported: CSV</p>                            
                            <input type="file" name="file" class="form-control" accept=".csv" >
                            <button class="btn">Upload</button>
                        </div>                       
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection
