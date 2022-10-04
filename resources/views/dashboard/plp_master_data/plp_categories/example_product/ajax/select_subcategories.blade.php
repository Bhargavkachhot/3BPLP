@if(count($subcategories) > 0)
   @foreach($subcategories as $subcategory)
      <option value="{{$subcategory->id}}">{{$subcategory->artical_number}} {{$subcategory->subcategory}}</option>
   @endforeach
@else  
<option disabled selected>No subcategories found.</option>    
@endif 
