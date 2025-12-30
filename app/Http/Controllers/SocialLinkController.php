<?php
namespace App\Http\Controllers;

use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::all();
        return view('admin.social_links.index', compact('socialLinks'));
    }

    public function create()
    {
        return view('admin.social_links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'link_address' => 'required|url|max:255',
            'link_icon' => 'required|string|max:255',
        ]);

        SocialLink::create($request->all());
        return redirect()->route('social_links.index')->with('success', 'Social Link created successfully.');
    }

    public function edit($id)
    {
        $socialLink = SocialLink::findOrFail($id);
        return view('admin.social_links.edit', compact('socialLink'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'link_address' => 'required|url|max:255',
            'link_icon' => 'required|string|max:255',
        ]);

        $socialLink = SocialLink::findOrFail($id);
        $socialLink->update($request->all());

        return redirect()->route('social_links.index')->with('success', 'Social Link updated successfully.');
    }

    public function destroy($id)
    {
        $socialLink = SocialLink::findOrFail($id);
        $socialLink->delete();

        return redirect()->route('social_links.index')->with('success', 'Social Link deleted successfully.');
    }
}
