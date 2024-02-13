
@extends('layout.main')

@section('content')
<!-- Sign In Start -->
<div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="index.html" class="">
                                <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>{{config('app.name')}}</h3>
                            </a>
                            <h3>Sign In</h3>
                        </div>

                        @if ($message = Session::get('success'))
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif


                        @if ($message = Session::get('danger'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif


                        @if ($message = Session::get('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif


                        @if ($message = Session::get('info'))
                        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>{{$message}}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif


                        @if ($errors->any())
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <div class="alert-text">Oops! ada kesalahan
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <form class="form" novalidate="novalidate" id="form_signin" method="POST" action="{{ route('signin.post') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <!-- <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <a href="">Forgot Password</a>
                            </div> -->
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                            <p class="text-center mb-0">Belum punya akun? <a href="{{route('signup')}}">Sign Up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
@endsection

@section('scripts')

@endsection


