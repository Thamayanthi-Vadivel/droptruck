<?php
  
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Indent;
use App\Models\Rate;
  
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function admin($id)
    {
        $indents = Indent::findOrFail($id);
        return view('layouts.sidebar', compact('indents'));
    } 

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function superAdmin($id)
    {
        $indents = Indent::findOrFail($id);
        return view('layouts.sidebar', compact('indents'));
    } 
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sales($id)
    {
        $indents = Indent::findOrFail($id);
        return view('layouts.sidebar', compact('indents'));
    }
    
    
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function suppliers($id)
    {
        $indents =Indent::findOrFail($id);
        return view('layouts.sidebar', compact('indents'));
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function accounts($id)
    {
        $indents =Indent::findOrFail($id);
        return view('layouts.sidebar', compact('indents'));
    }
}