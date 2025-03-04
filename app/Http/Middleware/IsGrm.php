<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Auth;

class IsGrm
{
    
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return redirect()->route('admin.sign');
        }
        
        if(Auth::user()->role == 56){
            return $next($request);
        }elseif(Auth::user()->role == 57){
            return $next($request);
        }elseif(Auth::user()->role == 1){
            return $next($request);
        }elseif(Auth::user()->role == 39){
            return $next($request);
        }
        
        return redirect()->route('admin.dashboard')->with([ 'error' => 'You do not have permission to access for this grm page.!']);
    }
    
    
	
	
}