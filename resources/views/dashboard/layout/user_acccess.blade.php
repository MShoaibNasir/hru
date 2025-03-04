@php
        $current_user = Auth::user();
      
        if ($current_user) {
            $allow_access = DB::table('users')
                ->join('roles', 'users.id', '=', 'users.role')
                ->where('users.id', '=', $current_user->id)
                ->first();
        }
    @endphp