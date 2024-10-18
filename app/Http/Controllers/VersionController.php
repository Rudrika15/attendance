<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{

    public function index()
    {
        $version = Version::all();
        return view('version.index',compact('version'));
    }

    public function create()
    {
        return view('version.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'version' => 'required',
        ]);

        $version = new Version();
        $version->version = $request->version;
        $version->majorUpdate = $request->major;
        $version->save();
        return redirect()->route('version.index');
    }

    public function edit(Request $request, $id)
    {
        $version = Version::find($id);
        return view('version.edit',compact('version'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'version' => 'required',
        ]);
        $id = $request->versionId;
        $version = Version::find($id);
        $version->version = $request->version;
        $version->majorUpdate = $request->major;
        $version->save();
        return redirect()->route('version.index');
    } 

    public function delete($id)
    {
        $version = Version::find($id);
        $version->delete();
        return redirect()->route('version.index');
    }
}
