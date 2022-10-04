<div class="app-header white box-shadow navbar-md">
    <div class="navbar">
        <!-- Open side - Naviation on mobile -->
        <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
            <i class="material-icons">&#xe5d2;</i>
        </a>
        <!-- / -->

        <!-- Page title - Bind to $state title -->
        <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>

        <!-- navbar right -->
        <ul class="nav navbar-nav pull-right">
            {{-- <li class="nav-item p-t p-b">
                <a class="btn btn-sm info marginTop2" href="{{ route('HomePage') }}" target="_blank"
                   title="{{ __('backend.sitePreview') }}">
                    <i class="material-icons">&#xe895;</i> {{ __('backend.sitePreview') }}
                </a>
            </li> --}}
            {{-- <?php
            $alerts = count(Helper::webmailsAlerts()) + count(Helper::eventsAlerts());
            ?>
            @if ($alerts > 0)
                <li class="nav-item dropdown pos-stc-xs">
                    <a class="nav-link" href data-toggle="dropdown">
                        <i class="material-icons">&#xe7f5;</i>
                        @if ($alerts > 0)
                            <span class="label label-sm up warn">{{ $alerts }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow">
                        <div class="box dark">
                            <div class="box p-a scrollable maxHeight320">
                                <ul class="list-group list-group-gap m-a-0">
                                    @foreach (Helper::webmailsAlerts() as $webmailsAlert)
                                        <li class="list-group-item lt box-shadow-z0 b">
                                            <span class="clear block">
                                                <small>{{ $webmailsAlert->from_name }}</small><br>
                                                <a href="{{ route('webmailsEdit', ['id' => $webmailsAlert->id]) }}"
                                                    class="text-primary">{{ $webmailsAlert->title }}</a>
                                                <br>
                                                <small class="text-muted">
                                                    {{ date('d M Y  h:i A', strtotime($webmailsAlert->date)) }}
                                                </small>
                                            </span>
                                        </li>
                                    @endforeach
                                    @foreach (Helper::eventsAlerts() as $eventsAlert)
                                        <li class="list-group-item lt box-shadow-z0 b">
                                            <span class="clear block">
                                                <a href="{{ route('calendarEdit', ['id' => $eventsAlert->id]) }}"
                                                    class="text-primary">{{ $eventsAlert->title }}</a>
                                                <br>
                                                <small class="text-muted">
                                                    @if ($eventsAlert->type == 3 || $eventsAlert->type == 2)
                                                        {{ date('d M Y  h:i A', strtotime($eventsAlert->start_date)) }}
                                                    @else
                                                        {{ date('d M Y', strtotime($eventsAlert->start_date)) }}
                                                    @endif
                                                </small>
                                            </span>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            @endif --}}
            <li class="nav-item dropdown">
                <a class="nav-link clear" href data-toggle="dropdown">
                    <span class="avatar">
                        @if (Auth::user()->image != '')
                            <img src="{{ asset('uploads/users/' . Auth::user()->image) }}"
                                alt="{{ Auth::user()->name }}" style="vertical-align: middle;
    width: 45px;
    height: 38px;
    border-radius: 50%;" id="img_responsive_profile" title="{{ Auth::user()->name }}">
                        @else
                            <img src="{{ asset('public/users/profile.png') }}" style="vertical-align: middle;
    width: 45px;
    height: 38px;
    border-radius: 50%;" alt="{{ Auth::user()->name }}"
                                title="{{ Auth::user()->name }}">
                        @endif
                        <i class="on b-white bottom"></i>
                    </span>
                </a>
                <div class="dropdown-menu pull-right dropdown-menu-scale">
                    @if (@Helper::GeneralWebmasterSettings('inbox_status'))
                        @if (@Auth::user()->permissionsGroup->inbox_status)
                            {{-- <a class="dropdown-item"
                               href="{{ route('webmails') }}"><span>{{ __('backend.siteInbox') }}</span>
                                @if (Helper::webmailsNewCount() > 0)
                                    <span class="label warn m-l-xs">{{ Helper::webmailsNewCount() }}</span>
                                @endif
                            </a> --}}
                        @endif
                    @endif

                    <a class="dropdown-item"
                        href="{{ route('admin.profile', Auth::user()->id) }}"><span>{{ __('backend.profile') }}</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin-change-password') }}"><span>Change
                            Password</span></a>
                    <div class="dropdown-divider"></div>
                    <a  id="logout"
                        class="dropdown-item"  href="{{ url('/logout') }}">Logout</a>

                    <form id="logout-form" action="{{ route('main-user-logout') }}" method="POST"
                        style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>

            <li class="nav-item hidden-md-up">
                <a class="nav-link" data-toggle="collapse" data-target="#collapse">
                    <i class="material-icons">&#xe5d4;</i>
                </a>
            </li>
            <!-- <li class="header-switcher show-switcher-icon">
                <a href ui-toggle-class="active" target="#sw-theme"
                    class="box-color dark-white text-color sw-btn hidden-switcher-icon">
                    <i class="fa fa-gear"></i>
                </a>
            </li> -->
        </ul>
        <!-- / navbar right -->

        <!-- navbar collapse -->
        <div class="collapse navbar-toggleable-sm" id="collapse">
            {{ Form::open(['route' => ['adminFind'],'method' => 'POST','role' => 'search','class' => 'navbar-form form-inline pull-right pull-none-sm navbar-item v-m']) }}

            {{-- <div class="form-group l-h m-a-0">
                <div class="input-group input-group-sm"><input type="text" name="q" class="form-control p-x rounded"
                                                               placeholder="{{ __('backend.search') }}..." required>
                    <span
                        class="input-group-btn"><button type="submit" class="btn white b-a rounded no-shadow"><i
                                class="fa fa-search"></i></button></span>
                                </div>
            </div> --}}
            {{ Form::close() }}
            <!-- link and dropdown -->
            @if (@Auth::user()->permissionsGroup->add_status)
                <ul class="nav navbar-nav">
                    <li class="nav-item dropdown">
                        <!-- <a class="nav-link" href data-toggle="dropdown">
                        <i class="fa fa-fw fa-plus text-muted"></i>
                        <span>{{ __('backend.new') }} </span>
                    </a> -->
                        <div class="dropdown-menu dropdown-menu-scale">
                            <?php
                            $data_sections_arr = explode(',', Auth::user()->permissionsGroup->data_sections);
                            $clr_ary = ['info', 'danger', 'success', 'accent'];
                            $ik = 0;
                            $mnu_title_var = 'title_' . @Helper::currentLanguage()->code;
                            $mnu_title_var2 = 'title_' . env('DEFAULT_LANGUAGE');
                            ?>
                            @if (@Auth::user()->permissionsGroup->add_status)

                                <div class="dropdown-divider"></div>

                                @if (Helper::GeneralWebmasterSettings('newsletter_status'))
                                    @if (@Auth::user()->permissionsGroup->newsletter_status)
                                        <a class="dropdown-item" href="{{ route('contacts') }}"><i
                                                class="material-icons">
                                                &#xe7ef;</i>
                                            &nbsp;{{ __('backend.newContacts') }}</a>
                                    @endif
                                @endif
                            @endif
                            @if (Helper::GeneralWebmasterSettings('inbox_status'))
                                @if (@Auth::user()->permissionsGroup->inbox_status)
                                    <a class="dropdown-item"
                                        href="{{ route('webmails', ['group_id' => 'create']) }}"><i
                                            class="material-icons">&#xe0be;</i> &nbsp;{{ __('backend.compose') }}
                                    </a>
                                @endif
                            @endif

                        </div>
                    </li>
                </ul>
            @endif
            <!-- / -->
        </div>
        <!-- / navbar collapse -->
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
     $(document).on("click", "#logout", function(e) {
        // alert('hello')
            e.preventDefault();
            var link = $(this).attr("href");
            // alert(link)
            // return false;
            Swal.fire({
  title: 'Are you sure ?',
  text: "You won't be able to logout!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes'
}).then((result) => {
  if (result.isConfirmed) {
    $('#logout-form').submit();
  }
})
        });
</script>
