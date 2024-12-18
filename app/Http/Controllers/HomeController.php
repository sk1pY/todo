<?php

namespace App\Http\Controllers;
use App\Models\Subscribe;
use Illuminate\Support\Facades\Storage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index($id)
    {
        $user = User::where('id', $id)->first();
       // dd($user->image);
        $tasks = Task::where('user_id', $id)->get();
        $subAuthors = Subscribe::where('subscriber_id', Auth::id())->pluck('author_id')->toArray();
        $countSubAuthors = Subscribe::where('author_id', $id)->count();


        return view('home', compact('user', 'tasks','subAuthors','countSubAuthors'));

    }

    public function update_task(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
     //   dd($validatedData);
        if ($request->hasFile('image')) {
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        } else {
            unset($validatedData['image']);
        }
            $task->update($validatedData + ['user_id' => Auth::id()]);
            return redirect('/home/'.Auth::id());
    }

    public function update_profile(Request $request, $id)
    {
        $user = User::findOrFail($id);
       // dd($user);
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }

                $imagePath = $request->file('image')->store('images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                unset($validatedData['image']);
            }
        $user->update($validatedData);

        return redirect('/home/'.Auth::id());

    }

    public function destroy($id)
    {
        Task::find($id)->delete();
        // Task::truncate(); удаляет все записи с таблицы и обнуляет автоинкремен в 0
        return redirect('/home/'.Auth::id());
    }
}
