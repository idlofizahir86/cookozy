@extends('layouts.app')

@section('content')
<body class="bg-surface-secondary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-md-9 col-xl-6 mx-auto ms-xl-0">
        <div class="mt-10 mt-lg-5 mb-6 d-flex align-items-center d-lg-block">
        <h1 class="ls-tight font-bolder h2">
                                {{ __('Reset Password') }}
                            </h1>
</div>

            @if(Session::has('message'))
              <div class="alert alert-info alert-dismissible fade show">
                {{ Session::get('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            @endif

            @if(Session::has('error'))
              <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            @endif

            @if ($errors->any())
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show">
                  {{ $error }}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              @endforeach
            @endif

            {!! Form::open(['method'=>'POST', 'action'=> 'App\Http\Controllers\Auth\ResetController@store']) !!}

            <div class="form-group">
              {!! Form::label('email', 'Email:') !!}
              {!! Form::email('email', null, ['class'=>'form-control'])!!}
            </div>


            <div class="form-group">
              {!! Form::submit('Sent Email', ['class'=>'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

          </div>

        </div>
      </div>
    </div>
  </div>
</body>
@endsection
