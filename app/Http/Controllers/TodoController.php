<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Todo;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class TodoController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,User $user)
    {
        if (!Gate::allows('todo-action', $user)) {
            return response()->json(['success' => false,'data' => '403 Forbidden'],Response::HTTP_FORBIDDEN);
        }
        // $todos = $user->todos->toArray();
        $todos = Todo::get()->toArray();
        return $this->respondWithSuccess($todos);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user)
    {
        if (!Gate::allows('create',$user)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError("Validation Failed");
        }
        $this->validate($request, [
            'content' => 'required',
            'completion_time' => 'date_format:Y-m-d H:i:s',
        ]);

        try {
            $todo = new Todo();
            $todo->content = $request->content;
            $todo->completion_time = $request->completion_time;

            if ($todo->save()) {
                return $this->respondWithSuccess($todo);
            }
        } catch (\Exception $e) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,User $user,Todo $todo)
    {

        if (!Gate::allows('view', $todo,$user)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError("Validation Failed");
        }
        return $this->respondWithSuccess($todo);


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user,Todo $todo)
    {
        //dd($request->all());
        if (!Gate::allows('update', $todo,$user)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError("Validation Failed");
        }
        $this->validate($request, [
            'content' => 'required',
            'completion_time' => 'date_format:Y-m-d H:i:s',
        ]);

        try {
            $todo->content = $request->content;
            $todo->completion_time = $request->completion_time;

            if ($todo->save()) {
                return $this->respondWithSuccess($todo);
            }
        } catch (\Exception $e) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,User $user,Todo $todo)
    {
        if (!Gate::allows('delete', $todo,$user)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError("Validation Failed");

        }
        if($todo->delete()){
             return response()->json(['success' => true,'data' => "Deleted successfully"]);
        }
    }


    public function makeCompleted(Request $request,User $user,Todo $todo)
    {
        if (!Gate::allows('complete', $todo,$user)) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError("Validation Failed");
        }

        try {
            $todo->completion_time = ($todo->completion_time == null? date('Y-d-m H:i:s'):null);

            if ($todo->save()) {
                return $this->respondWithSuccess($todo);
            }
        } catch (\Exception $e) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWithError($e->getMessage());
        }

    }

}
