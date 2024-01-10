<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Traits\ApiResponser;
use Modules\Auth\Traits\ChatTrait;

class ChatApiController extends Controller
{
    use ChatTrait, ValidatesRequests,ApiResponser;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function startChat(Request $request)
    {
        
    }



   
}
