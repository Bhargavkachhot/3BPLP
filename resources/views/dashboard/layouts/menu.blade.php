<?php
// Current Full URL
$fullPagePath = Request::url();
// Char Count of Backend folder Plus 1
{{--  dd(env('BACKEND_PATH'));  --}}
$envAdminCharCount = strlen(env('BACKEND_PATH')) + 1;
// URL after Root Path EX: admin/home
$urlAfterRoot = substr($fullPagePath, strpos($fullPagePath, env('BACKEND_PATH')) + $envAdminCharCount);
{{-- $mnu_title_var = "title_" . @Helper::currentLanguage()->code;
$mnu_title_var2 = "title_" . env('DEFAULT_LANGUAGE'); --}}
?>

<div id="aside" class="app-aside modal fade folded md nav-expand">
    <div class="left navside dark dk" layout="column">
        <div class="navbar navbar-md no-radius">
            <!-- brand -->
            <a class="navbar-brand text-center logo_css" href="{{ route('adminHome') }}">
                <img src="{{ asset('assets/frontend/logo/logo.svg') }}" alt="Control">
                <!-- <span class="hidden-folded inline">USSIE-TEXI</span> -->
            </a>
            <!-- / brand -->
        </div>
        <div flex class="hide-scroll">
            <nav class="scroll nav-active-primary">

                <ul class="nav" ui-nav>
                    <!-- <li class="nav-header hidden-folded">
                        <small class="text-muted">{{ __('backend.main') }}</small>
                    </li> -->

                    <li
                        class="{{ \Request::route()->getName() == 'adminHome' || \Request::route()->getName() == 'dashboardfilter'? 'active': ' ' }}">
                        <a href="{{ route('adminHome') }}" onclick="location.href='{{ route('adminHome') }}'">
                            <span class="nav-icon">
                                <i class="fa fa-pie-chart" aria-hidden="true"></i> 
                            </span>
                            <span class="nav-text">{{ __('backend.dashboard') }}</span>
                        </a>
                    </li>

                       
                    <?php 
                    
                    $currentFolder1 = 'cms'; // Put folder name here
                    $PathCurrentFolder1 = substr($urlAfterRoot, 0, strlen($currentFolder1));
                    
                    $currentFolder2 = 'emailtemplate'; // Put folder name here
                    $PathCurrentFolder2 = substr($urlAfterRoot, 0, strlen($currentFolder2));
                    
                    $currentFolder3 = 'webmaster'; // Put folder name here
                    $PathCurrentFolder3 = substr($urlAfterRoot, 0, strlen($currentFolder3));  

                    $currentFolder4 = 'roles'; // Put folder name here
                    $PathCurrentFolder4 = substr($urlAfterRoot, 0, strlen($currentFolder4)); 

                    $currentFolder5 = 'users'; // Put folder name here
                    $PathCurrentFolder5 = substr($urlAfterRoot, 0, strlen($currentFolder5));

                    $currentFolder6 = 'primary-categories'; // Put folder name here
                    $PathCurrentFolder6 = substr($urlAfterRoot, 0, strlen($currentFolder6));

                    $currentFolder7 = 'subcategories'; // Put folder name here
                    $PathCurrentFolder7 = substr($urlAfterRoot, 0, strlen($currentFolder7));

                    $currentFolder8 = 'product-categories'; // Put folder name here
                    $PathCurrentFolder8 = substr($urlAfterRoot, 0, strlen($currentFolder8));

                    $currentFolder9 = 'example-products'; // Put folder name here
                    $PathCurrentFolder9 = substr($urlAfterRoot, 0, strlen($currentFolder9));

                    $currentFolder10 = 'product-attributes'; // Put folder name here
                    $PathCurrentFolder10 = substr($urlAfterRoot, 0, strlen($currentFolder10));

                    $currentFolder11 = 'primary-packaging'; // Put folder name here
                    $PathCurrentFolder11 = substr($urlAfterRoot, 0, strlen($currentFolder11));

                    $currentFolder12 = 'packaging-attributes'; // Put folder name here
                    $PathCurrentFolder12 = substr($urlAfterRoot, 0, strlen($currentFolder12));

                    $currentFolder13 = 'sku-packaging'; // Put folder name here
                    $PathCurrentFolder13 = substr($urlAfterRoot, 0, strlen($currentFolder13));

                    $currentFolder14 = 'countries-of-origin'; // Put folder name here
                    $PathCurrentFolder14 = substr($urlAfterRoot, 0, strlen($currentFolder14));

                    $currentFolder15 = 'countries-of-destination'; // Put folder name here
                    $PathCurrentFolder15 = substr($urlAfterRoot, 0, strlen($currentFolder15));

                    $currentFolder16 = 'capacities'; // Put folder name here
                    $PathCurrentFolder16 = substr($urlAfterRoot, 0, strlen($currentFolder16)); 

                    $currentFolder17 = 'certificates'; // Put folder name here
                    $PathCurrentFolder17 = substr($urlAfterRoot, 0, strlen($currentFolder17));

                     $currentFolder18 = 'faq'; // Put folder name here
                    $PathCurrentFolder18 = substr($urlAfterRoot, 0, strlen($currentFolder18));

                    ?>

                    

                     

                    <li {{ $PathCurrentFolder6 == $currentFolder6 || $PathCurrentFolder7 == $currentFolder7 || $PathCurrentFolder8 == $currentFolder8 || $PathCurrentFolder9 == $currentFolder9 || $PathCurrentFolder10 == $currentFolder10 || $PathCurrentFolder11 == $currentFolder11 || $PathCurrentFolder12 == $currentFolder12 || $PathCurrentFolder13 == $currentFolder13 || $PathCurrentFolder14 == $currentFolder14 || $PathCurrentFolder15 == $currentFolder15 || $PathCurrentFolder16 == $currentFolder16  || $PathCurrentFolder17 == $currentFolder17  ? 'class=active': '' }}>
                        
                            <?php
                                $allowed_plp_product_category_permissions = @Helper::GetRolePermission(Auth::id(),1); 
                            ?>
                            @if(isset($allowed_plp_product_category_permissions) && $allowed_plp_product_category_permissions->read == 1)
                            
                        <a>
                            <span class="nav-caret">
                                <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon"> <svg viewBox="64 64 896 896" data-icon="database" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-600 72h560v208H232V136zm560 480H232V408h560v208zm0 272H232V680h560v208zM304 240a40 40 0 1 0 80 0 40 40 0 1 0-80 0zm0 272a40 40 0 1 0 80 0 40 40 0 1 0-80 0zm0 272a40 40 0 1 0 80 0 40 40 0 1 0-80 0z"></path></svg> 
                            </span>
                            <span class="nav-text">PLP Master Data</span>
                        </a> 
                        <ul class="nav-sub">  
                            
                            
                            <li {{ $PathCurrentFolder6 == $currentFolder6 || $PathCurrentFolder7 == $currentFolder7 || $PathCurrentFolder8 == $currentFolder8 || $PathCurrentFolder9 == $currentFolder9 ?  'class=active': '' }}>
                        <a>
                            <span class="nav-caret">
                                <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="book" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M832 64H192c-17.7 0-32 14.3-32 32v832c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V96c0-17.7-14.3-32-32-32zm-260 72h96v209.9L621.5 312 572 347.4V136zm220 752H232V136h280v296.9c0 3.3 1 6.6 3 9.3a15.9 15.9 0 0 0 22.3 3.7l83.8-59.9 81.4 59.4c2.7 2 6 3.1 9.4 3.1 8.8 0 16-7.2 16-16V136h64v752z"></path></svg>
                            </span>
                            <span class="nav-text">PLP Categories</span>
                        </a>
                        <ul class="nav-sub">  
                            
                            <li {{ $PathCurrentFolder6 == $currentFolder6   ? 'class=active': '' }}>
                                <a href="{{ route('primary.categories') }}" class="sub-link sub-link-nested ">
                                    <span class="nav-text child-link" >Primary Categories</span>
                                </a>
                            </li>  
                            
                            <li {{ $PathCurrentFolder7 == $currentFolder7   ? 'class=active': '' }}>
                                <a href="{{route('subcategories')}}" class="sub-link sub-link-nested" >
                                    <span class="nav-text child-link">Subcategories</span>
                                </a>
                            </li> 
                            <li {{ $PathCurrentFolder8 == $currentFolder8   ? 'class=active': '' }}>
                                <a href="{{route('product.categories')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link" >Product Categories</span>
                                </a>
                            </li> 
                            <li {{ $PathCurrentFolder9 == $currentFolder9   ? 'class=active': '' }}>
                                <a href="{{route('example.products')}}" class="sub-link sub-link-nested" >
                                    <span class="nav-text child-link">Example Products</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                    <li {{ $PathCurrentFolder10 == $currentFolder10 || $PathCurrentFolder11 == $currentFolder11 || $PathCurrentFolder12 == $currentFolder12 || $PathCurrentFolder13 == $currentFolder13 || $PathCurrentFolder14 == $currentFolder14 || $PathCurrentFolder15 == $currentFolder15 || $PathCurrentFolder16 == $currentFolder16  || $PathCurrentFolder17 == $currentFolder17  ? 'class=active': '' }}>

                        <?php
                                $allowed_plp_product_category_permissions = @Helper::GetRolePermission(Auth::id(),4); 
                            ?>
                            @if(isset($allowed_plp_product_category_permissions) && $allowed_plp_product_category_permissions->read == 1)

                        <a>
                            <span class="nav-caret">
                                <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="tags" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M483.2 790.3L861.4 412c1.7-1.7 2.5-4 2.3-6.3l-25.5-301.4c-.7-7.8-6.8-13.9-14.6-14.6L522.2 64.3c-2.3-.2-4.7.6-6.3 2.3L137.7 444.8a8.03 8.03 0 0 0 0 11.3l334.2 334.2c3.1 3.2 8.2 3.2 11.3 0zm62.6-651.7l224.6 19 19 224.6L477.5 694 233.9 450.5l311.9-311.9zm60.16 186.23a48 48 0 1 0 67.88-67.89 48 48 0 1 0-67.88 67.89zM889.7 539.8l-39.6-39.5a8.03 8.03 0 0 0-11.3 0l-362 361.3-237.6-237a8.03 8.03 0 0 0-11.3 0l-39.6 39.5a8.03 8.03 0 0 0 0 11.3l243.2 242.8 39.6 39.5c3.1 3.1 8.2 3.1 11.3 0l407.3-406.6c3.1-3.1 3.1-8.2 0-11.3z"></path></svg>
                            </span>
                            <span class="nav-text">PLP Attributes</span>
                        </a>
                        <ul class="nav-sub">  
                            <li {{ $PathCurrentFolder10 == $currentFolder10   ? 'class=active': '' }}>
                                <a href="{{route('product.attributes')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Product Attributes</span>
                                </a>
                            </li> 
                            <li {{ $PathCurrentFolder11 == $currentFolder11   ? 'class=active': '' }}>
                                <a href="{{route('primary.packaging')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Primary Packaging</span>
                                </a>
                            </li>
                            <li {{ $PathCurrentFolder12 == $currentFolder12   ? 'class=active': '' }}>
                                <a href="{{route('primary.packaging.attributes')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Primary Packaging Attributes</span>
                                </a>
                            </li> 
                           <li {{$PathCurrentFolder13 == $currentFolder13 ? 'class=active': '' }}>
                                <a href="{{route('sku.packaging')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">SKU Packaging</span>
                                </a>
                            </li>
                            <li {{$PathCurrentFolder14 == $currentFolder14 ? 'class=active': '' }}>
                                <a href="{{route('countries.of.origin')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Countries Of Origin</span>
                                </a>
                            </li> 
                            <li {{$PathCurrentFolder15 == $currentFolder15 ? 'class=active': '' }}>
                                <a href="{{route('countries.of.destination')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Countries Of Destination</span>
                                </a>
                            </li>  
                            <li {{$PathCurrentFolder16 == $currentFolder16 ? 'class=active': '' }}>
                                <a href="{{route('capacities')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Capacities</span>
                                </a>
                            </li> 
                            <li {{$PathCurrentFolder17 == $currentFolder17 ? 'class=active': '' }}>
                                <a href="{{route('certificates')}}" class="sub-link sub-link-nested">
                                    <span class="nav-text child-link">Certificates</span>
                                </a>
                            </li> 
                        </ul>
                        @endif
                    </li>


                        </ul>
                    </li>


                    <!-- <?php
                        $allowed_faq_permissions = @Helper::GetRolePermission(Auth::id(),6); 
                    ?>
                    @if(isset($allowed_faq_permissions) && $allowed_faq_permissions->read == 1)
                    <li {{ $PathCurrentFolder18 == $currentFolder18    ? 'class=active': '' }}>
                        <a href="{{ route('faq') }}">
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="question" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M764 280.9c-14-30.6-33.9-58.1-59.3-81.6C653.1 151.4 584.6 125 512 125s-141.1 26.4-192.7 74.2c-25.4 23.6-45.3 51-59.3 81.7-14.6 32-22 65.9-22 100.9v27c0 6.2 5 11.2 11.2 11.2h54c6.2 0 11.2-5 11.2-11.2v-27c0-99.5 88.6-180.4 197.6-180.4s197.6 80.9 197.6 180.4c0 40.8-14.5 79.2-42 111.2-27.2 31.7-65.6 54.4-108.1 64-24.3 5.5-46.2 19.2-61.7 38.8a110.85 110.85 0 0 0-23.9 68.6v31.4c0 6.2 5 11.2 11.2 11.2h54c6.2 0 11.2-5 11.2-11.2v-31.4c0-15.7 10.9-29.5 26-32.9 58.4-13.2 111.4-44.7 149.3-88.7 19.1-22.3 34-47.1 44.3-74 10.7-27.9 16.1-57.2 16.1-87 0-35-7.4-69-22-100.9zM512 787c-30.9 0-56 25.1-56 56s25.1 56 56 56 56-25.1 56-56-25.1-56-56-56z"></path></svg>
                            </span>
                            <span class="nav-text">Frequently Asked Questions</span>
                        </a>
                    </li>
                    @endif 

                    <?php
                        $allowed_cms_permissions = @Helper::GetRolePermission(Auth::id(),5); 
                    ?>
                    @if(isset($allowed_cms_permissions) && $allowed_cms_permissions->read == 1)
                    <li {{ $PathCurrentFolder1 == $currentFolder1   ? 'class=active': '' }}>
                        <a href="{{ route('cms') }}" onclick="location.href='{{ route('cms') }}'">
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="global" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M854.4 800.9c.2-.3.5-.6.7-.9C920.6 722.1 960 621.7 960 512s-39.4-210.1-104.8-288c-.2-.3-.5-.5-.7-.8-1.1-1.3-2.1-2.5-3.2-3.7-.4-.5-.8-.9-1.2-1.4l-4.1-4.7-.1-.1c-1.5-1.7-3.1-3.4-4.6-5.1l-.1-.1c-3.2-3.4-6.4-6.8-9.7-10.1l-.1-.1-4.8-4.8-.3-.3c-1.5-1.5-3-2.9-4.5-4.3-.5-.5-1-1-1.6-1.5-1-1-2-1.9-3-2.8-.3-.3-.7-.6-1-1C736.4 109.2 629.5 64 512 64s-224.4 45.2-304.3 119.2c-.3.3-.7.6-1 1-1 .9-2 1.9-3 2.9-.5.5-1 1-1.6 1.5-1.5 1.4-3 2.9-4.5 4.3l-.3.3-4.8 4.8-.1.1c-3.3 3.3-6.5 6.7-9.7 10.1l-.1.1c-1.6 1.7-3.1 3.4-4.6 5.1l-.1.1c-1.4 1.5-2.8 3.1-4.1 4.7-.4.5-.8.9-1.2 1.4-1.1 1.2-2.1 2.5-3.2 3.7-.2.3-.5.5-.7.8C103.4 301.9 64 402.3 64 512s39.4 210.1 104.8 288c.2.3.5.6.7.9l3.1 3.7c.4.5.8.9 1.2 1.4l4.1 4.7c0 .1.1.1.1.2 1.5 1.7 3 3.4 4.6 5l.1.1c3.2 3.4 6.4 6.8 9.6 10.1l.1.1c1.6 1.6 3.1 3.2 4.7 4.7l.3.3c3.3 3.3 6.7 6.5 10.1 9.6 80.1 74 187 119.2 304.5 119.2s224.4-45.2 304.3-119.2a300 300 0 0 0 10-9.6l.3-.3c1.6-1.6 3.2-3.1 4.7-4.7l.1-.1c3.3-3.3 6.5-6.7 9.6-10.1l.1-.1c1.5-1.7 3.1-3.3 4.6-5 0-.1.1-.1.1-.2 1.4-1.5 2.8-3.1 4.1-4.7.4-.5.8-.9 1.2-1.4a99 99 0 0 0 3.3-3.7zm4.1-142.6c-13.8 32.6-32 62.8-54.2 90.2a444.07 444.07 0 0 0-81.5-55.9c11.6-46.9 18.8-98.4 20.7-152.6H887c-3 40.9-12.6 80.6-28.5 118.3zM887 484H743.5c-1.9-54.2-9.1-105.7-20.7-152.6 29.3-15.6 56.6-34.4 81.5-55.9A373.86 373.86 0 0 1 887 484zM658.3 165.5c39.7 16.8 75.8 40 107.6 69.2a394.72 394.72 0 0 1-59.4 41.8c-15.7-45-35.8-84.1-59.2-115.4 3.7 1.4 7.4 2.9 11 4.4zm-90.6 700.6c-9.2 7.2-18.4 12.7-27.7 16.4V697a389.1 389.1 0 0 1 115.7 26.2c-8.3 24.6-17.9 47.3-29 67.8-17.4 32.4-37.8 58.3-59 75.1zm59-633.1c11 20.6 20.7 43.3 29 67.8A389.1 389.1 0 0 1 540 327V141.6c9.2 3.7 18.5 9.1 27.7 16.4 21.2 16.7 41.6 42.6 59 75zM540 640.9V540h147.5c-1.6 44.2-7.1 87.1-16.3 127.8l-.3 1.2A445.02 445.02 0 0 0 540 640.9zm0-156.9V383.1c45.8-2.8 89.8-12.5 130.9-28.1l.3 1.2c9.2 40.7 14.7 83.5 16.3 127.8H540zm-56 56v100.9c-45.8 2.8-89.8 12.5-130.9 28.1l-.3-1.2c-9.2-40.7-14.7-83.5-16.3-127.8H484zm-147.5-56c1.6-44.2 7.1-87.1 16.3-127.8l.3-1.2c41.1 15.6 85 25.3 130.9 28.1V484H336.5zM484 697v185.4c-9.2-3.7-18.5-9.1-27.7-16.4-21.2-16.7-41.7-42.7-59.1-75.1-11-20.6-20.7-43.3-29-67.8 37.2-14.6 75.9-23.3 115.8-26.1zm0-370a389.1 389.1 0 0 1-115.7-26.2c8.3-24.6 17.9-47.3 29-67.8 17.4-32.4 37.8-58.4 59.1-75.1 9.2-7.2 18.4-12.7 27.7-16.4V327zM365.7 165.5c3.7-1.5 7.3-3 11-4.4-23.4 31.3-43.5 70.4-59.2 115.4-21-12-40.9-26-59.4-41.8 31.8-29.2 67.9-52.4 107.6-69.2zM165.5 365.7c13.8-32.6 32-62.8 54.2-90.2 24.9 21.5 52.2 40.3 81.5 55.9-11.6 46.9-18.8 98.4-20.7 152.6H137c3-40.9 12.6-80.6 28.5-118.3zM137 540h143.5c1.9 54.2 9.1 105.7 20.7 152.6a444.07 444.07 0 0 0-81.5 55.9A373.86 373.86 0 0 1 137 540zm228.7 318.5c-39.7-16.8-75.8-40-107.6-69.2 18.5-15.8 38.4-29.7 59.4-41.8 15.7 45 35.8 84.1 59.2 115.4-3.7-1.4-7.4-2.9-11-4.4zm292.6 0c-3.7 1.5-7.3 3-11 4.4 23.4-31.3 43.5-70.4 59.2-115.4 21 12 40.9 26 59.4 41.8a373.81 373.81 0 0 1-107.6 69.2z"></path></svg>
                            </span>
                            <span class="nav-text">CMS</span>
                        </a>
                    </li>
                    @endif -->

                    <?php
                        $allowed_generalSiteSettings_permissions = @Helper::GetRolePermission(Auth::id(),3); 
                    ?>
                    @if(isset($allowed_generalSiteSettings_permissions) && $allowed_generalSiteSettings_permissions->read == 1)

                    <li
                        {{$PathCurrentFolder2 == $currentFolder2 ||$PathCurrentFolder3 == $currentFolder3   ? 'class=active': '' }}>
                        <a>
                            <span class="nav-caret">
                                <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="setting" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M924.8 625.7l-65.5-56c3.1-19 4.7-38.4 4.7-57.8s-1.6-38.8-4.7-57.8l65.5-56a32.03 32.03 0 0 0 9.3-35.2l-.9-2.6a443.74 443.74 0 0 0-79.7-137.9l-1.8-2.1a32.12 32.12 0 0 0-35.1-9.5l-81.3 28.9c-30-24.6-63.5-44-99.7-57.6l-15.7-85a32.05 32.05 0 0 0-25.8-25.7l-2.7-.5c-52.1-9.4-106.9-9.4-159 0l-2.7.5a32.05 32.05 0 0 0-25.8 25.7l-15.8 85.4a351.86 351.86 0 0 0-99 57.4l-81.9-29.1a32 32 0 0 0-35.1 9.5l-1.8 2.1a446.02 446.02 0 0 0-79.7 137.9l-.9 2.6c-4.5 12.5-.8 26.5 9.3 35.2l66.3 56.6c-3.1 18.8-4.6 38-4.6 57.1 0 19.2 1.5 38.4 4.6 57.1L99 625.5a32.03 32.03 0 0 0-9.3 35.2l.9 2.6c18.1 50.4 44.9 96.9 79.7 137.9l1.8 2.1a32.12 32.12 0 0 0 35.1 9.5l81.9-29.1c29.8 24.5 63.1 43.9 99 57.4l15.8 85.4a32.05 32.05 0 0 0 25.8 25.7l2.7.5a449.4 449.4 0 0 0 159 0l2.7-.5a32.05 32.05 0 0 0 25.8-25.7l15.7-85a350 350 0 0 0 99.7-57.6l81.3 28.9a32 32 0 0 0 35.1-9.5l1.8-2.1c34.8-41.1 61.6-87.5 79.7-137.9l.9-2.6c4.5-12.3.8-26.3-9.3-35zM788.3 465.9c2.5 15.1 3.8 30.6 3.8 46.1s-1.3 31-3.8 46.1l-6.6 40.1 74.7 63.9a370.03 370.03 0 0 1-42.6 73.6L721 702.8l-31.4 25.8c-23.9 19.6-50.5 35-79.3 45.8l-38.1 14.3-17.9 97a377.5 377.5 0 0 1-85 0l-17.9-97.2-37.8-14.5c-28.5-10.8-55-26.2-78.7-45.7l-31.4-25.9-93.4 33.2c-17-22.9-31.2-47.6-42.6-73.6l75.5-64.5-6.5-40c-2.4-14.9-3.7-30.3-3.7-45.5 0-15.3 1.2-30.6 3.7-45.5l6.5-40-75.5-64.5c11.3-26.1 25.6-50.7 42.6-73.6l93.4 33.2 31.4-25.9c23.7-19.5 50.2-34.9 78.7-45.7l37.9-14.3 17.9-97.2c28.1-3.2 56.8-3.2 85 0l17.9 97 38.1 14.3c28.7 10.8 55.4 26.2 79.3 45.8l31.4 25.8 92.8-32.9c17 22.9 31.2 47.6 42.6 73.6L781.8 426l6.5 39.9zM512 326c-97.2 0-176 78.8-176 176s78.8 176 176 176 176-78.8 176-176-78.8-176-176-176zm79.2 255.2A111.6 111.6 0 0 1 512 614c-29.9 0-58-11.7-79.2-32.8A111.6 111.6 0 0 1 400 502c0-29.9 11.7-58 32.8-79.2C454 401.6 482.1 390 512 390c29.9 0 58 11.6 79.2 32.8A111.6 111.6 0 0 1 624 502c0 29.9-11.7 58-32.8 79.2z"></path></svg>
                            </span>
                            <span class="nav-text">{{ __('backend.generalSiteSettings') }}</span>
                        </a>
                        <ul class="nav-sub">
                            

                             
                            <?php
                            $currentFolder = 'emailtemplate'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('emailtemplate') }}" class="sub-link">
                                    <span class="nav-text">Email Template</span>
                                </a>
                            </li>

                            

                            <?php
                            $currentFolder = 'webmaster'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{route('webmasterSettings')}}" class="sub-link">
                                    <span class="nav-text">General Settings</span>
                                </a>
                            </li>

                        </ul>
                    </li> 
                    @endif
                    <?php
                        $allowed_access_control_permissions = @Helper::GetRolePermission(Auth::id(),2); 
                    ?>
                    @if(isset($allowed_access_control_permissions) && $allowed_access_control_permissions->read == 1)
                    <li
                        {{ $currentFolder4 == $PathCurrentFolder4 ||$PathCurrentFolder5 == $currentFolder5  ? 'class=active': '' }}>
                        <a>
                            <span class="nav-caret">
                                <i class="fa fa-caret-down"></i>
                            </span>
                            <span class="nav-icon">
                               <svg viewBox="64 64 896 896" data-icon="file-protect" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class=""><path d="M644.7 669.2a7.92 7.92 0 0 0-6.5-3.3H594c-6.5 0-10.3 7.4-6.5 12.7l73.8 102.1c3.2 4.4 9.7 4.4 12.9 0l114.2-158c3.8-5.3 0-12.7-6.5-12.7h-44.3c-2.6 0-5 1.2-6.5 3.3l-63.5 87.8-22.9-31.9zM688 306v-48c0-4.4-3.6-8-8-8H296c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h384c4.4 0 8-3.6 8-8zm-392 88c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H296zm184 458H208V148h560v296c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V108c0-17.7-14.3-32-32-32H168c-17.7 0-32 14.3-32 32v784c0 17.7 14.3 32 32 32h312c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm402.6-320.8l-192-66.7c-.9-.3-1.7-.4-2.6-.4s-1.8.1-2.6.4l-192 66.7a7.96 7.96 0 0 0-5.4 7.5v251.1c0 2.5 1.1 4.8 3.1 6.3l192 150.2c1.4 1.1 3.2 1.7 4.9 1.7s3.5-.6 4.9-1.7l192-150.2c1.9-1.5 3.1-3.8 3.1-6.3V538.7c0-3.4-2.2-6.4-5.4-7.5zM826 763.7L688 871.6 550 763.7V577l138-48 138 48v186.7z"></path></svg>
                            </span>
                            <span class="nav-text">Access Control </span>
                        </a>
                        <ul class="nav-sub"> 
                             
                            <?php
                            $currentFolder = 'roles'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{ route('roles') }}" class="sub-link">
                                    <span class="nav-text">Roles</span>
                                </a>
                            </li> 
                            <?php
                            $currentFolder = 'users'; // Put folder name here
                            $PathCurrentFolder = substr($urlAfterRoot, 0, strlen($currentFolder));
                            ?>
                            <li {{ $PathCurrentFolder == $currentFolder ? 'class=active' : '' }}>
                                <a href="{{route('users')}}" class="sub-link">
                                    <span class="nav-text">Users</span>
                                </a>
                            </li> 
                        </ul>
                    </li> 
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
