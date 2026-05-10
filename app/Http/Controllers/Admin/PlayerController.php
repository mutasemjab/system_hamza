<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with('subscription');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        $players = $query->latest()->paginate(15);
        return view('admin.players.index', compact('players'));
    }

    public function create()
    {
        return view('admin.players.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'phone'      => 'nullable|string|max:20',
            'weight'     => 'nullable|numeric|min:0|max:300',
            'height'     => 'nullable|numeric|min:0|max:300',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes'      => 'nullable|string',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        Player::create($data);

        return redirect()->route('players.index')
            ->with('success', 'تم إضافة اللاعب بنجاح');
    }

    public function edit(Player $player)
    {
        return view('admin.players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'phone'      => 'nullable|string|max:20',
            'weight'     => 'nullable|numeric|min:0|max:300',
            'height'     => 'nullable|numeric|min:0|max:300',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes'      => 'nullable|string',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        $player->update($data);

        return redirect()->route('players.index')
            ->with('success', 'تم تحديث بيانات اللاعب بنجاح');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')
            ->with('success', 'تم حذف اللاعب بنجاح');
    }
}
