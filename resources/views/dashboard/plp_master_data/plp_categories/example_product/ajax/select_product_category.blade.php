@if(count($product_categories) > 0)
   @foreach($product_categories as $product_category)
      <option value="{{$product_category->id}}">{{$product_category->artical_number}} {{$product_category->product_category}}</option>
   @endforeach
@else  
<option disabled selected>No product categories found.</option>    
@endif 