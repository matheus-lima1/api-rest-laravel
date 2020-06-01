<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\RealState;
use App\Repository\RealStateRepository;

class RealStateSearchController extends Controller
{

    private $realState;
    public function __construct(RealState $realState){
        $this->realState = $realState;
    }

    public function index(Request $request)
    {
        //$realState = $this->realState->paginate(10);

        $repository = new RealStateRepository($this->realState);

        if($request->has('coditions')) {
		    $repository->selectCoditions($request->get('coditions'));
	    }

	    if($request->has('fields')) {
		    $repository->selectFilter($request->get('fields'));
	    }

        return response()->json([
            'data' => $repository->getResult()->paginate(10)
        ],200);
    }

    public function show($id)
    {
        //
    }

}
