<div class="sidebar-menu toggle-others collapsed"> {{--fixed--}}
    <div class="sidebar-menu-inner">
        <header class="logo-env">
            <div class="logo">
                <a href="{{route('dashboard')}}" class="logo-expanded">
                    <img src="{{asset('public/assets/images/Logo.jpg')}}" width="80" alt=""/>
                </a>
                <a href="{{route('dashboard')}}" class="logo-collapsed">
                    <img src="{{asset('public/assets/images/Logo.jpg')}}" width="40" alt=""/>
                </a>
            </div>
            <div class="mobile-menu-toggle visible-xs">
                <a href="#" data-toggle="user-info-menu">
                    <i class="fa-bell-o"></i> <span class="badge badge-success">7</span>
                </a>
                <a href="#" data-toggle="mobile-menu">
                    <i class="fa-bars"></i>
                </a>
            </div>
        </header>
        <ul id="main-menu" class="main-menu">
            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                <li class="<?php $page = ""; if ($page == "dashboard") {
                    echo "active";
                } ?>">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="<?php if ($page == "users") {
                    echo "active";
                } ?>">
                    <a href="{{route('users')}}" data-toggle="tooltip" title="Users">
                        <i class="fas fa-user"></i> <span class="title">Users</span>
                    </a>
                </li>
                <li class="<?php if ($page == "classes") {
                    echo "active";
                } ?>">
                    <a href="{{route('classes')}}" data-toggle="tooltip" title="Classes">
                        <i class="fas fa-soccer-ball-o"></i>
                        <span class="title">Classes</span>
                    </a>
                </li>
                <li class="<?php if ($page == "location") {
                    echo "active";
                } ?>">
                    <a href="{{route('locations')}}" data-toggle="tooltip" title="Locations">
                        <i class="fas fa-map-marker"></i> <span class="title">Locations</span>
                    </a>
                </li>
                <li class="<?php if ($page == "leads") {
                    echo "active";
                } ?>">
                    <a href="{{route('leads')}}" data-toggle="tooltip" title="Leads">
                        <i class="fa fa-bullhorn" aria-hidden="true"></i> <span class="title">Leads</span>
                    </a>
                </li>
                <li class="<?php if ($page == "billing") {
                    echo "active";
                } ?>">
                    <a href="{{route('billing')}}" data-toggle="tooltip" title="Billing">
                        <i class="fas fa-dollar"></i>
                        <span class="title">Billing</span>
                    </a>
                </li>
                <li class="<?php if ($page == "configuration") {
                    echo "active";
                } ?>">
                    <a href="{{route('configuration')}}" data-toggle="tooltip" title="Configuration">
                        <i class="linecons-cog"></i>
                        <span class="title">Configuration</span>
                    </a>
                </li>
                <li class="<?php if ($page == "training_room") {
                    echo "active";
                } ?>">
                    <a href="{{route('trainingRoom')}}" data-toggle="tooltip" title="Training Room">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <span class="title">Training Room</span>
                    </a>
                </li>
                <li class="<?php if ($page == "view-faq") {
                    echo "active";
                } ?>">
                    <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                        <span class="title">Knowledge Zone</span>
                    </a>
                </li>
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 2)
                <li class="active">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('users')}}" data-toggle="tooltip" title="Users">
                        <i class="fas fa-user"></i> <span class="title">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('classes')}}" data-toggle="tooltip" title="Classes">
                        <i class="fas fa-soccer-ball-o"></i>
                        <span class="title">Classes</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('locations')}}" data-toggle="tooltip" title="Locations">
                        <i class="fas fa-map-marker"></i> <span class="title">Locations</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('billing')}}" data-toggle="tooltip" title="Billing">
                        <i class="fas fa-dollar"></i>
                        <span class="title">Billing</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('training')}}" data-toggle="tooltip" title="Training Room">
                        <i class="fas fa-book"></i>
                        <span class="title">Training Room</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                        <i class="fas fa-question-circle"></i>
                        <span class="title">Knowledge Zone</span>
                    </a>
                </li>
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 3)
                <li class="active">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('users')}}" data-toggle="tooltip" title="Users">
                        <i class="fas fa-user"></i> <span class="title">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('classes')}}" data-toggle="tooltip" title="Classes">
                        <i class="fas fa-soccer-ball-o"></i>
                        <span class="title">Classes</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('locations')}}" data-toggle="tooltip" title="Locations">
                        <i class="fas fa-map-marker"></i> <span class="title">Locations</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('billing')}}" data-toggle="tooltip" title="Billing">
                        <i class="fas fa-dollar"></i>
                        <span class="title">Billing</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('training')}}" data-toggle="tooltip" title="Training Room">
                        <i class="fas fa-book"></i>
                        <span class="title">Training Room</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                        <i class="fas fa-question-circle"></i>
                        <span class="title">Knowledge Zone</span>
                    </a>
                </li>
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 4)
                <li class="active">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('classes')}}" data-toggle="tooltip" title="Classes">
                        <i class="fas fa-soccer-ball-o"></i>
                        <span class="title">Classes</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('training')}}" data-toggle="tooltip" title="Training Room">
                        <i class="fas fa-book"></i>
                        <span class="title">Training Room</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                        <i class="fas fa-question-circle"></i>
                        <span class="title">Knowledge Zone</span>
                    </a>
                </li>
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                <?php
                $LeadConversionCheck = \Illuminate\Support\Facades\DB::table('lead_conversions')
                    ->where('parent_id', '=', \Illuminate\Support\Facades\Auth::id())
                    ->get();
                $CheckForDashboardData = true;
                if (sizeof($LeadConversionCheck) > 0) {
                    if ($LeadConversionCheck[0]->conversion_type == 2) {
                        $CheckForDashboardData = false;
                    }
                }
                ?>
                <li class="<?php if ($page == "dashboard") {
                    echo "active";
                } ?>">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                @if($CheckForDashboardData)
                    <li class="<?php if ($page == "users") {
                        echo "active";
                    } ?>">
                        <a href="{{route('users')}}" data-toggle="tooltip" title="My Players">
                            <i class="fas fa-users"></i> <span class="title">My Players</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == "billing") {
                        echo "active";
                    } ?>">
                        <a href="{{route('dashboard.memberships')}}" data-toggle="tooltip" title="Memberships">
                            <i class="fas fa-repeat"></i> <span class="title">Memberships</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == "parent-expenses") {
                        echo "active";
                    } ?>">
                        <a href="{{route('parent.expenses')}}" data-toggle="tooltip" title="Payment History">
                            <i class="fas fa-dollar-sign"></i> <span class="title">Payment History</span>
                        </a>
                    </li>
                    <li class="<?php if ($page == "parent-reports") {
                        echo "active";
                    } ?>">
                        <a href="{{route('parent.reports')}}" data-toggle="tooltip" title="Evaluation">
                            <i class="fa fa-bar-chart"></i> <span class="title">Evaluation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('training')}}" data-toggle="tooltip" title="Training Room">
                            <i class="fas fa-book"></i>
                            <span class="title">Training Room</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                            <i class="fas fa-question-circle"></i>
                            <span class="title">Knowledge Zone</span>
                        </a>
                    </li>
                @endif
            @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 6)
                <li class="<?php if ($page == "dashboard") {
                    echo "active";
                } ?>">
                    <a href="{{route('dashboard')}}" data-toggle="tooltip" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="<?php if ($page == "parent-reports") {
                    echo "active";
                } ?>">
                    <a href="{{route('parent.reports')}}" data-toggle="tooltip" title="Evaluation">
                        <i class="fa fa-bar-chart"></i> <span class="title">Evaluation</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('training')}}" data-toggle="tooltip" title="Training Room">
                        <i class="fas fa-book"></i>
                        <span class="title">Training Room</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('view.faq')}}" data-toggle="tooltip" title="Knowledge Zone">
                        <i class="fas fa-question-circle"></i>
                        <span class="title">Knowledge Zone</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
