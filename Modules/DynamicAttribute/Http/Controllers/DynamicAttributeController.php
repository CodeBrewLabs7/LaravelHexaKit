<?php

namespace Modules\DynamicAttribute\Http\Controllers;
use DB;
use Attribute;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DynamicAttribute\Entities\Attribute as EntitiesAttribute;
use Modules\DynamicAttribute\Entities\AttributeOptions;
use Modules\DynamicAttribute\Entities\AttributeTranslations;

class DynamicAttributeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('dynamicattribute::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('dynamicattribute::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request){

        try {
            $this->validate($request, [
              'name.0' => 'required|string|max:60',
              'file_type' => 'required',
            ],['name.0' => 'The default language name field is required.']);
            if($request->file_type=="Selecter"){
                $this->validate($request, [
                    'option_name.0.0' => 'required|string|max:60',
                  ],['option_name.0.0' => 'The default Option name field is required.']);
            }
            DB::beginTransaction();
            $attribute = new EntitiesAttribute();
            $attribute->file_type = $request->file_type;
            $attribute->is_required = $request->is_required;
            $attribute->save();
            $language_id = $request->language_id;
            foreach ($request->name as $k => $name) {
                if($name){
                    $attributeTranslation = new AttributeTranslations();
                    $attributeTranslation->name = $name;
                    $attributeTranslation->language_id = $language_id[$k];
                    $attributeTranslation->vendor_registration_document_id = $attribute->id;
                    $attributeTranslation->save();
                }
            }
            if($request->has('option_name')){
                foreach($request->option_name as $key =>$value){
                    if(isset($value[0]) && !empty($value[0])){
                        foreach($request->language_id as $lang_key =>$lang_value){
                            if(isset($value[$lang_key]) && !empty($value[$lang_key])){
                                $optionTranslation  = new AttributeOptions();
                                $optionTranslation->vendor_registration_select_option_id =$attribute->id ;
                                $optionTranslation->language_id = $lang_value;
                                $optionTranslation->name =$value[$lang_key] ;
                                $optionTranslation->save();
                            }
                        }
                    }


                }
            }

            DB::commit();
            return $this->successResponse($attribute, __('Attribute Added Successfully.'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse([], $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('dynamicattribute::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('dynamicattribute::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
