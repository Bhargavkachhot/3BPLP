<div class="tab-pane {{ Session::get('active_tab') == 'languageSettingsTab' || Session::get('active_tab') == '' ? 'active' : '' }}"
    id="tab-2">
    <div class="p-a-md">
        <h5>{!! __('backend.languageSettings') !!}</h5>
    </div>

    <div class="p-a-md col-md-12">
        <div class="row"> 
            <div class="col-sm-6">
                <label>{{ __('backend.dateFormat') }} : </label>
                <select name="date_format" class="form-control select2 select2-hidden-accessible" ui-jp="select2"
                    ui-options="{theme: 'bootstrap'}">
                    <option value="Y-m-d" {{ env('DATE_FORMAT', 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>Y-m-d
                    </option>
                    <option value="d-m-Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'd-m-Y' ? 'selected' : '' }}>d-m-Y
                    </option>
                    <option value="m-d-Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'm-d-Y' ? 'selected' : '' }}>m-d-Y
                    </option>
                    <option value="d/m/Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>d/m/Y
                    </option>
                    <option value="m/d/Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>m/d/Y
                    </option>
                    <option value="d.m.Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'd.m.Y' ? 'selected' : '' }}>d.m.Y
                    </option>
                    <option value="m.d.Y" {{ env('DATE_FORMAT', 'Y-m-d') == 'm.d.Y' ? 'selected' : '' }}>m.d.Y
                    </option>

                </select>
            </div>   
            <div class="col-sm-6" id="twitter_link">
                <div class="form-group">
                    <label>Address</label>
                    {!! Form::textarea('address', old('address', $setting->address ? $setting->address : ''), ['id' => 'address', 'class' => 'form-control', 'dir' => 'ltr', 'rows' => 2]) !!}
                </div>
            </div>
        </div>

     <div class="row">
            <div class="col-sm-6" id="support_name">
                <div class="form-group">
                    <label>Support Email</label>
                    {!! Form::text('email', old('email', $setting->email ? $setting->email : ''), ['id' => '   ', 'class' => 'form-control', 'dir' => 'ltr']) !!}
                </div>
            </div>
            <div class="col-sm-6" id="support_email">
                <div class="form-group">
                    <label>Support Name</label>
                  {!! Form::text('from_name', old('from_name', $setting->from_name ? $setting->from_name : ''), ['id' => '   ', 'class' => 'form-control', 'dir' => 'ltr']) !!}
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-sm-6" id="copyright_en">
                <div class="form-group">
                    <label>Copy Right</label>
                    {!! Form::text('copyright_en', old('copyright_en', $WebmasterSetting->copyright_en ? $WebmasterSetting->copyright_en : ''), ['id' => 'copyright_en', 'class' => 'form-control', 'dir' => 'ltr']) !!}
                </div>
            </div>
            <div class="col-sm-6" id="site_title_en">
                <div class="form-group">
                    <label>Site Title</label>
                    {!! Form::text('site_title_en', old('site_title_en', @$WebmasterSetting->site_title_en ? $WebmasterSetting->site_title_en : ''), ['id' => 'site_title_en', 'class' => 'form-control', 'dir' => 'ltr']) !!}
                </div>
            </div> 
        </div> 
         <div class="row">
        <div class="col-sm-6" id="phone">
                <div class="form-group">
                    <label>Phone</label>
                    {!! Form::text('phone', old('phone', $setting->phone ? $setting->phone : ''), ['id' => '   ', 'class' => 'form-control', 'dir' => 'ltr']) !!}
                </div>
            </div>  
            </div>
    </div>
</div>
