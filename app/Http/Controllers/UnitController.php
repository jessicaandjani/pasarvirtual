<?php

namespace App\Http\Controllers;

use App\Unit;
use App\Converter;
use Illuminate\Http\Request;
use Response;

class UnitController extends Controller {
	
    public function unitPost() {
        return view('unit');
    }

	public function getAllUnit() {
    	//retrieve all unit from datbase
    	$units = Unit::all();
    	
    	return Response::json(array(
    		'units'=>$units->toArray()),
    		200
    	);
    }

    public function getUnit(Request $request, $id) {
        //retrieve unit based on id
        $unit = Unit::find($id);

        return Response::json(array(
            'error'=>false,
            'unit'=>$unit),
            200
        );
    }

    public function addUnit(Request $request) {
        //add unit to database
        $unit = new Unit();
        $unit->unit = $request->unit_name;
        $unit->unit_type = $request->unit_type;
        $convert_gram = $request->convert_gram;
        $unit->save();
        $unit_id = $unit->id;

        if($unit->unit_type == "common") {
            return redirect()->action('ConverterController@addConverter', ['unit_id' => $unit_id, 'gram' => $convert_gram ]);
        } else if($unit->unit_type == "not common") {
            return Response::json(array(
                'error'=>false,
                'message'=>"Unit berhasil ditambahkan"),
                200
            );
        }
    }

    public function updateUnit(Request $request, $id) {
        //update unit to database
        $unit = Unit::find($id);
        $unit->unit = $request->unit_name;
        $unitNow = $request->unit_type;
        $convert_gram = $request->convert_gram;
        //update based on unit type
        if ($unit->unit_type == $unitNow) {
            if ($unit->unit_type == "common") {                
                return redirect()->action('ConverterController@editConverter', ['unit_id' => $id, 'gram' => $convert_gram ]);
            }
        } if ($unit->unit_type != $unitNow ) {
            if ($unitNow == "common") {
                return redirect()->action('ConverterController@addConverter', ['unit_id' => $id, 'gram' => $convert_gram ]);
            } else if ($unitNow == "not common") {
                //delete unit from converter
                // return redirect()->action('ConverterController@addConverter', ['unit_id' => $unit_id, 'gram' => $convert_gram ]);
            }
        }
        $unit->unit_type == $unitNow;
        $unit->save();
    }
}
