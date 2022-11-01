<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('dist/img/happySeasonsLogo.jpeg') }}" alt="{{__('Happy Seasons')}}" class="brand-image img-circle elevation-3" style="opacity: 1;">
        <span class="brand-text">{{__('Happy Seasons')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-header">{{__('sidebar.ADMIN DASHBOARD')}}</li>
                <li class="nav-item">
                    <a href="@if(Auth::check() && Auth::guard('admin')->check()) {{route('admin.dashboard')}} @else {{route('home')}} @endif" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{__('sidebar.Dashboard')}}
                        </p>
                    </a>
                </li>
                    <li class="nav-header">{{__('sidebar.Members')}}</li>
                 @if(Auth::user()->checkAdminAccess('access'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/access/*') || Request::is('admin/access')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/access/*') || Request::is('admin/access')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                {{__('sidebar.Access')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('access', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.access.index')}}" class="nav-link {{ (Request::url() == route('admin.access.index')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.List Access')}}</p>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->checkAdminAccess('access', null, 'add'))
                                <li class="nav-item">
                                    <a href="{{route('admin.access.create')}}" class="nav-link {{ (Request::url() == route('admin.access.create')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.Add New Access')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->checkAdminAccess('customer'))
                        <li class="nav-item has-treeview {{ (Request::is('admin/customer/*') || Request::is('admin/customer')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (Request::is('admin/customer/*') || Request::is('admin/customer')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>
                                    {{__('sidebar.Customers')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->checkAdminAccess('customer', null, 'view'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.customer.index')}}" class="nav-link {{ (Request::url() == route('admin.customer.index')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.List Customers')}}</p>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->checkAdminAccess('customer', null, 'add'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.customer.create')}}" class="nav-link {{ (Request::url() == route('admin.customer.create')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.Add New Customer')}}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->checkAdminAccess('category') || Auth::user()->checkAdminAccess('country') || Auth::user()->checkAdminAccess('city') )
                    <li class="nav-header">{{__('sidebar.Managements')}}</li>
                    @if(Auth::user()->checkAdminAccess('category'))
                        <li class="nav-item has-treeview {{ (Request::is('admin/category/*') || Request::is('admin/category')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (Request::is('admin/category/*') || Request::is('admin/category')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    {{__('sidebar.Category')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->checkAdminAccess('category', null, 'view'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.category.index')}}" class="nav-link {{ (Request::url() == route('admin.category.index')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.List Category')}}</p>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->checkAdminAccess('category', null, 'add'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.category.create')}}" class="nav-link {{ (Request::url() == route('admin.category.create')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.Add New Category')}}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @if(Auth::user()->checkAdminAccess('subcategory'))
                        <li class="nav-item has-treeview {{ (Request::is('admin/sub-category/*') || Request::is('admin/sub-category')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (Request::is('admin/sub-category/*') || Request::is('admin/sub-category')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    {{__('SubCategories')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->checkAdminAccess('subcategory', null, 'view'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.sub-category.index')}}" class="nav-link {{ (Request::url() == route('admin.sub-category.index')) ? 'active' : '' }}">
                                            <p>{{__('List SubCategories')}}</p>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->checkAdminAccess('subcategory', null, 'add'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.sub-category.create')}}" class="nav-link {{ (Request::url() == route('admin.sub-category.create')) ? 'active' : '' }}">
                                            <p>{{__('Add New SubCategory')}}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                @if(Auth::user()->checkAdminAccess('city'))
                        <li class="nav-item has-treeview {{ (Request::is('admin/city/*') || Request::is('admin/city')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (Request::is('admin/city/*') || Request::is('admin/city')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    {{__('sidebar.Cities')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->checkAdminAccess('city', null, 'view'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.city.index')}}" class="nav-link {{ (Request::url() == route('admin.city.index')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.List Cities')}}</p>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->checkAdminAccess('city', null, 'add'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.city.create')}}" class="nav-link {{ (Request::url() == route('admin.city.create')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.Add New City')}}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(Auth::user()->checkAdminAccess('country'))
                        <li class="nav-item has-treeview {{ (Request::is('admin/country/*') || Request::is('admin/country')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (Request::is('admin/country/*') || Request::is('admin/country')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-flag"></i>
                                <p>
                                    {{__('sidebar.Countries')}}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @if(Auth::user()->checkAdminAccess('country', null, 'view'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.country.index')}}" class="nav-link {{ (Request::url() == route('admin.country.index')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.List Countries')}}</p>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->checkAdminAccess('country', null, 'add'))
                                    <li class="nav-item">
                                        <a href="{{route('admin.country.create')}}" class="nav-link {{ (Request::url() == route('admin.country.create')) ? 'active' : '' }}">
                                            <p>{{__('sidebar.Add New Country')}}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endif
                @if(Auth::user()->checkAdminAccess('item'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/item/*') || Request::is('admin/item')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/item/*') || Request::is('admin/item')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cart-arrow-down"></i>
                            <p>
                                {{__('Items')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('item', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.item.index')}}" class="nav-link {{ (Request::url() == route('admin.item.index')) ? 'active' : '' }}" class="nav-link">
                                        <p>{{__('List Items')}}</p>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->checkAdminAccess('item', null, 'add'))
                                <li class="nav-item">
                                    <a href="{{route('admin.item.create')}}" class="nav-link {{ (Request::url() == route('admin.item.create')) ? 'active' : '' }}" class="nav-link">
                                        <p>{{__('sidebar.Add New Item')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            @if(Auth::user()->systemAdmin() || Auth::user()->checkAdminAccess('ads'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/ads/*') || Request::is('admin/ads')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/ads/*') || Request::is('admin/ads')) ? 'active' : '' }}">
                            <i class="nav-icon fab fa-adversal"></i>
                            <p>
                                {{__('sidebar.Ads')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('admin.ads.index')}}" class="nav-link {{ (Request::url() == route('admin.ads.index')) ? 'active' : '' }}" class="nav-link">
                                    <p>{{__('sidebar.List Ads')}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.ads.create')}}" class="nav-link {{ (Request::url() == route('admin.ads.create')) ? 'active' : '' }}" class="nav-link">
                                    <p>{{__('sidebar.Add New Ads')}}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->systemAdmin() || Auth::user()->checkAdminAccess('image'))
                    <li class="nav-header">{{__('sidebar.Invitations')}}</li>
                    <li class="nav-item has-treeview {{ (Request::is('admin/image-category/*') || Request::is('admin/image-category')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/image-category/*') || Request::is('admin/image-category')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                {{__('sidebar.Image Category')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('admin.image-category.index')}}" class="nav-link {{ (Request::url() == route('admin.image-category.index')) ? 'active' : '' }}" class="nav-link">
                                    <p>{{__('sidebar.List Image Category')}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.image-category.create')}}" class="nav-link {{ (Request::url() == route('admin.image-category.create')) ? 'active' : '' }}" class="nav-link">
                                    <p>{{__('sidebar.Add New Image Category')}}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->checkAdminAccess('card'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/card/*') || Request::is('admin/card')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/card/*') || Request::is('admin/card')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-photo-video"></i>
                            <p>
                                {{__('sidebar.Cards')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('card', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.card.index')}}" class="nav-link {{ (Request::url() == route('admin.card.index')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.List Cards')}}</p>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->checkAdminAccess('card', null, 'add'))
                                <li class="nav-item">
                                    <a href="{{route('admin.card.create')}}" class="nav-link {{ (Request::url() == route('admin.card.create')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.Add New Card')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->checkAdminAccess('text'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/text/*') || Request::is('admin/text')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/text/*') || Request::is('admin/text')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope-open-text"></i>
                            <p>
                                {{__('sidebar.Texts')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('text', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.text.index')}}" class="nav-link {{ (Request::url() == route('admin.text.index')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.List Texts')}}</p>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->checkAdminAccess('text', null, 'add'))
                                <li class="nav-item">
                                    <a href="{{route('admin.text.create')}}" class="nav-link {{ (Request::url() == route('admin.text.create')) ? 'active' : '' }}">
                                        <p>{{__('sidebar.Add New Text')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            @if(Auth::user()->checkAdminAccess('invitation'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/invitation/*') || Request::is('admin/invitation')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/invitation/*') || Request::is('admin/invitation')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mail-bulk"></i>
                            <p>
                                {{__('sidebar.Invitations')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('invitation', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.invitation.index')}}" class="nav-link {{ (Request::url() == route('admin.invitation.index')) ? 'active' : '' }}" class="nav-link">
                                        <p>{{__('sidebar.List Invitations')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
            @endif
            @if(Auth::user()->checkAdminAccess('contact-history'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/contact-history/*') || Request::is('admin/contact-history')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/contact-history/*') || Request::is('admin/contact-history')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                {{__('Contact History')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('contact-history', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.contact-history.index')}}" class="nav-link {{ (Request::url() == route('admin.contact-history.index')) ? 'active' : '' }}" class="nav-link">
                                        <p>{{__('List Contact History')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
            @endif

                @if(Auth::user())
                    <li class="nav-item">
                        <a href="{{route('admin.review.index')}}" class="nav-link {{ (Request::url() == route('admin.review.index')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-reply"></i>
                            <p>
                                {{__('Reviews')}}
                            </p>
                        </a>
                    </li>
                @endif
            @if(Auth::user()->checkAdminAccess('page'))
                    <li class="nav-header">{{__('sidebar.CMS')}}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.page.index')}}" class="nav-link {{ (Request::url() == route('admin.page.index')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-pen-square"></i>
                            <p>
                                {{__('sidebar.CMS Page')}}
                            </p>
                        </a>
                    </li>
            @endif
            @if(Auth::user()->checkAdminAccess('group-notification'))
                    <li class="nav-item has-treeview {{ (Request::is('admin/group-notify/*') || Request::is('admin/group-notify')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/group-notify/*') || Request::is('admin/group-notify')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>
                                {{__('List App Notifications')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('group-notification', null, 'view'))
                                <li class="nav-item">
                                    <a href="{{route('admin.group-notification.index')}}" class="nav-link {{ (Request::url() == route('admin.group-notification.index')) ? 'active' : '' }}">
                                        <p>{{__('List Group Notifications')}}</p>
                                    </a>
                                </li>
                            @endif
                            @if(Auth::user()->checkAdminAccess('group-notification', null, 'add'))
                                <li class="nav-item">
                                    <a href="{{route('admin.group-notification.create')}}" class="nav-link {{ (Request::url() == route('admin.group-notification.create')) ? 'active' : '' }}">
                                        <p>{{__('Add New Group Notifications')}}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            @if(Auth::user()->checkAdminAccess('setting'))
                <li class="nav-item has-treeview {{ (Request::is('admin/setting/*') || Request::is('admin/setting')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ (Request::is('admin/setting/*') || Request::is('admin/setting')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                {{__('sidebar.Settings')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(Auth::user()->checkAdminAccess('setting', null, 'view'))
                            <li class="nav-item">
                                <a href="{{route('admin.setting.index')}}" class="nav-link {{ (Request::url() == route('admin.setting.index')) ? 'active' : '' }}" class="nav-link">
                                    <p>{{__('sidebar.List Settings')}}</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="nav-header">{{__('sidebar.Contact Us')}}</li>
                    <li class="nav-item">
                        <a href="{{route('admin.contactUs.index')}}" class="nav-link {{ (Request::url() == route('admin.contactUs.index')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>
                                {{__('sidebar.Contact Us List')}}
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-header">{{__('sidebar.Settings')}}</li>
                <li class="nav-item">
                    <a href="{{route('admin.account.settings')}}" class="nav-link {{ (Request::url() == route('admin.account.settings')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            {{__('sidebar.Account Setting')}}
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
