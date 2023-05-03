
        @php
        $all_menu = session('all_menu');
        $user_menu = session('menu_id');
        @endphp
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>{{config('app.name')}}</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{asset('template/img/default.jpg')}}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{session('login_user')}}</h6>
                        <span>{{session('nama_akses')}}</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="{{route('klinik')}}" @if(substr(Route::current()->getName(),0,6) == 'klinik')  class="nav-item nav-link active" @else  class="nav-item nav-link" @endif><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <div class="nav-item dropdown">
                        <a href="#" @if(substr(Route::current()->getName(),0,4) == 'mon.') class="nav-link dropdown-toggle active" @else class="nav-link dropdown-toggle" @endif data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Monitoring</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            @for($i=0;$i<count($all_menu);$i++)
                                @if(substr($all_menu[$i]->menu_id,0,3) == 'MON')
                                    <!-- Begin Validate -->
                                    @for($j=0;$j<count($user_menu);$j++)
                                        @if($user_menu[$j] == $all_menu[$i]->menu_id)
                                            <a href="{{route($all_menu[$i]->route)}}" class="dropdown-item {{ Route::is($all_menu[$i]->route) ? 'active' : '' }}">{{$all_menu[$i]->deskripsi}}</a>
                                        @endif
                                    @endfor
                                    <!-- End Validate -->
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" @if(substr(Route::current()->getName(),0,3) == 'tr.') class="nav-link dropdown-toggle active" @else class="nav-link dropdown-toggle" @endif data-bs-toggle="dropdown"><i class="far fa-keyboard me-2"></i>Transaksi</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            @for($i=0;$i<count($all_menu);$i++)
                                @if(substr($all_menu[$i]->menu_id,0,2) == 'TR')
                                    <!-- Begin Validate -->
                                    @for($j=0;$j<count($user_menu);$j++)
                                        @if($user_menu[$j] == $all_menu[$i]->menu_id)
                                            <a href="{{route($all_menu[$i]->route)}}" class="dropdown-item {{ Route::is($all_menu[$i]->route) ? 'active' : '' }}">{{$all_menu[$i]->deskripsi}}</a>
                                        @endif
                                    @endfor
                                    <!-- End Validate -->
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" @if(substr(Route::current()->getName(),0,3) == 'kl.') class="nav-link dropdown-toggle active" @else class="nav-link dropdown-toggle" @endif data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Kelola</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            @for($i=0;$i<count($all_menu);$i++)
                                @if(substr($all_menu[$i]->menu_id,0,2) == 'KL')
                                    <!-- Begin Validate -->
                                    @for($j=0;$j<count($user_menu);$j++)
                                        @if($user_menu[$j] == $all_menu[$i]->menu_id)
                                            <a href="{{route($all_menu[$i]->route)}}" class="dropdown-item {{ Route::is($all_menu[$i]->route) ? 'active' : '' }}">{{$all_menu[$i]->deskripsi}}</a>
                                        @endif
                                    @endfor
                                    <!-- End Validate -->
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
