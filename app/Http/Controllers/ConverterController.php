<?php

namespace App\Http\Controllers;

use App\Converter;
use Illuminate\Http\Request;
use Response;

class ConverterController extends Controller {

    public function getAllConverter(Request $request) {
        //retrieve all converter
        $converters = Converter::all();

        return Response::json(array(
            'error'=> false,
            'converters'=> $converters->toArray()),
            200
        );
    }

	public function getConverter(Request $request, $id) {
    	//retrieve unit in gram based on unit id
    	$converter = Converter::where('unit_id', $id)->first();

    	return Response::json(array(
    		'error'=>false,
    		'converter'=>$converter),
    		200
    	);
    }

    public function updateConverter(Request $request, $id, $gram) {
        //update unit to database
        $converter = Converter::find($id);
        $converter->unit_id = $request->unit_id;
        $converter->in_gram = $request->gram;
        $converter->save();

        return Response::json(array(
            'error'=>false,
            'message'=>"Unit dan converter berhasil diubah"),
            200
        );
    }

    public function addConverter(Request $request, $unit_id, $gram) {
        //add converter to database
        $converter = new Converter();
        $converter->unit_id = $unit_id;
        $converter->in_gram = $gram;
        $converter->save();

        return Response::json(array(
            'error'=>false,
            'message'=>"Unit dan converter berhasil ditambahkan"),
            200
        );
    }
}
