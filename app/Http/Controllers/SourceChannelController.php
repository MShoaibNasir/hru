<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SourceChannel;
use DB;
use Auth;

class SourceChannelController extends Controller
{
    public function create(Request $requets)
    {
        return view('dashboard.grm.source_channel.create');
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $source_channel = SourceChannel::create($data);
            addLogs('added a new source channel titled "'. $request->name.'"', Auth::user()->id);
            
            return redirect()->route('source_channel.index')->with([ 'success' => 'You Create  source channel Successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }
    public function delete($id)
    {
        $source_channel = SourceChannel::findOrfail($id);
        addLogs('delete source channel titled "'. $source_channel->name.'"', Auth::user()->id);
        $source_channel->delete();
        return redirect()->back()->with('success', 'You Delete source channel Successfully');
    }

    public function index()
    {
        $source_channels = SourceChannel::all();
        return view('dashboard.grm.source_channel.index', compact('source_channels'));
    }
    public function edit($id)
    {
        $source_channel = SourceChannel::findOrfail($id);
        return view('dashboard.grm.source_channel.edit', compact('source_channel'));
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $data = $request->all();
            $source_channel = SourceChannel::findOrfail($id);
            addLogs('updated source channel titled "'. $source_channel->name.'"', Auth::user()->id);
            $source_channel->fill($data)->save();
            return redirect()->route('source_channel.index')->with(['success' => 'You update  source channel successfully!']);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }


    }

    public function status(Request $request, $id)
    {
        $source_channel = SourceChannel::findOrfail($id);
        if ($source_channel->status == '0') {
            $source_channel->status = '1';
            addLogs('activate source channel titled "' . $source_channel->name . '"', Auth::user()->id);
            $source_channel->save();
            return redirect()->back()->with('success','You Activate source channel Successfully!');
        } else {
            $source_channel->status = '0';
            $source_channel->save();
            addLogs('deactivate source channel titled "' . $source_channel->name . '"', Auth::user()->id);
            return redirect()->back()->with('success','You deactivate source channel Successfully!');
        }
    }
}