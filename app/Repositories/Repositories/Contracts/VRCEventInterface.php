<?php
namespace App\Repositories\Contracts;
use Illuminate\Http\Request;

interface VRCEventInterface
{
    public function index();
    public function create();
    public function store(Request $request);
    public function edit(Request $request,$id);
    public function update(Request $request,$id);
    public function status($id);
}
 ?>