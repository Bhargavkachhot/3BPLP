<?php
namespace App\Imports;
use App\Models\ProductAttribute; 
use App\Models\ProductAttributeCategory;
use App\Models\ProductCategory;
use App\Models\ProductAttributeSubcategory; 
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation; 
use Helper;
use Redirect;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
class ImportProductAttribute implements ToCollection, WithStartRow,WithValidation

{
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '1' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:255',
            '2' => 'required|unique:product_attributes,position,NULL,id,status,0|unique:product_attributes,position,NULL,id,status,1',  
        ]; 
    }

    public function customValidationMessages()
    {
        return [ 
            '1.required'    => 'Product attributes is required.', 
            '1.max'         => 'The maximun length of the product attribute must not exceed :max',
            '1.regex'       => 'Incorrect format of product attribute',
            '2.required'    => 'Position is required.',
            '2.unique'      => 'The position has already been used',  
        ];
  }


    public function collection(Collection $rows)
    {  

        $product_category_str = [];
        $subcategory_str = [];
        $unique_position = [];
        foreach ($rows as $row) 
        {  
            $product_category_array = preg_split ("/\,/", $row[5]); 
            foreach ($product_category_array as $key => $value) { 
                $product_category_str[] = ltrim($value);
            } 
            $subcategory_array = preg_split ("/\,/", $row[4]);
            foreach ($subcategory_array as $key => $value) { 
                $subcategory_str[] = ltrim($value);
            }    
            $unique_position_result = Helper::CheckUniquePosition($row[2]);
            if($unique_position_result == 1){
                $unique_position[] = 1;
            }  
        }  
        $product_category = Helper::CheckProductCategoryIsExists($product_category_str);
        $subcategory = Helper::CheckSubategoryIsExists($subcategory_str);          
        if($subcategory['all_matched'] == true && $product_category['all_matched'] == true){

            foreach ($rows as $key => $row) 
            {   

                $subcategory_id = Helper::CheckSubategoryId($subcategory_str); 
                $product_category_id = Helper::CheckProductCategoryId($product_category_str); 
                if(count($product_category_id) > 0 && count($subcategory_id) > 0){
                        $create_productAttribute = ProductAttribute::create([
                           'product_attribute' => $row[1],
                           'position' => $row[2],
                           'status' => 1,
                        ]);
                    foreach ($subcategory_id as $key => $value) {
                       $create_subcategory = ProductAttributeSubcategory::create([
                           'product_attribute_id' => $create_productAttribute->id,
                           'product_subcategory_id' => $value, 
                        ]);
                    }

                    foreach ($product_category_id as $key => $value) {
                        $create_product_category = ProductAttributeCategory::create([
                           'product_attribute_id' => $create_productAttribute->id,
                           'product_category_id' => $value, 
                        ]);  
                    } 
                }
                 
            }  
            return redirect()->route('product.attributes')->with('success', 'Data imported successfully.'); 
        }else{ 
            if(isset($subcategory['not_matched'])){
                $error_message_subcategory = 'This Subategoreis are not exist in system : '.implode(', ', $subcategory['not_matched']);
            }else{
                $error_message_subcategory = '';
            }
            if(isset($product_category['not_matched'])){
                $error_message_product_category = 'This product Categiories are not exist in system : '.implode(', ', $product_category['not_matched']);
            }else{
                $error_message_product_category = '';
            }  
            return redirect()->back()->with('error1',$error_message_subcategory)->with('error2',$error_message_product_category);  

        }    
        
 
   }

}