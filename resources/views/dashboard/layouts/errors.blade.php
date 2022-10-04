{{-- @if(!empty(@$errors))
    @if(@$errors->any())
        <div class="padding p-b-0" id="alert_success">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger m-b-0">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif --}}

@if(Session::has('doneMessage') || Session::has('success'))
    <div class="padding p-b-0" id="alert_message">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('doneMessage') }}
                    {{ Session::get('success') }}
                </div>
            </div>
        </div>
    </div>
@endif




@if(Session::has('errorMessage') || Session::has('error'))
    <div class="padding p-b-0" id="alert_error">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" >
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('errorMessage') }}
                    {{ Session::get('error') }}
                </div>
            </div>
        </div>
    </div>
@endif


@if(Session::has('error2') && Session::get('error2') != '') 
    <div class="padding p-b-0" id="alert_error2">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> 
                    {{ Session::get('error2') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if(Session::has('error1') && Session::get('error1') != '') 
    <div class="padding p-b-0" id="alert_error1">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button> 
                    {{ Session::get('error1') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if(Session::has('infoMessage'))
    <div class="padding p-b-0" id="alert_info_messsage">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-info m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('infoMessage') }}
                </div>
            </div>
        </div>
    </div>
@endif


@if(Session::has('warningMessage'))
    <div class="padding p-b-0" id="alert_warning_message">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-warning m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    {{ Session::get('warningMessage') }}
                </div>
            </div>
        </div>
    </div>
@endif
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
    

    $( document ).ready(function() {
    setTimeout(function() {
                            $('#alert_success').empty();
                        }, 3000);

    setTimeout(function() {
                            $('#alert_message').empty();
                        }, 3000);

    setTimeout(function() {
                            $('#alert_error').empty();
                        }, 3000);

    // setTimeout(function() {
    //                         $('#alert_error1').empty();
    //                     }, 3000);

    // setTimeout(function() {
    //                         $('#alert_error2').empty();
    //                     }, 3000);

    setTimeout(function() {
                            $('#alert_info_messsage').empty();
                        }, 3000);

    setTimeout(function() {
                            $('#alert_warning_message').empty();
                        }, 3000);
});
</script>

