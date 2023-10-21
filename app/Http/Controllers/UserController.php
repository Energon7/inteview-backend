<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserListRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserFullInfoResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserListRequest $request)
    {
        $sortBy = $request->string('sortBy')->toString();
        $sortDesc = $request->boolean('sortDesc');
        $search = $request->get('search');
        $users = User::query()
            ->when($sortBy, fn(Builder $q) => $q->orderBy(
                    column: $sortBy,
                    direction: $sortDesc ? 'desc' : 'asc'
                )
            )
            ->when($search, fn(Builder $q) => $q->where(
                fn(Builder $q) =>
                    $q->where('name', 'ilike', "%$search%")
                      ->orWhere('email', 'ilike', "%$search%")
               )
            )
            ->paginate($request->integer('itemsPerPage',10));

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
        ]);

        return UserFullInfoResource::make($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return UserFullInfoResource::make($user);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'email', 'address', 'phone']));

        return UserFullInfoResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);

        return response()->json();
    }
}
