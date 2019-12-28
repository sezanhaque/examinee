<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Session;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('s');

        $sessions = Session::query();

        if ($search) {
            $sessions = $sessions->where('title', 'like', "%$search%");
        }

        $sessions = $sessions->get();
        
        $resource = JsonResource::collection($sessions);

        return $resource;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $session = Session::create($request->all());
        
        $resource = new JsonResource($session);

        return $resource;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Session  $Session
     * @return \Illuminate\Http\Response
     */
    public function show(Session $Session)
    {
        $resource = new JsonResource($Session);

        return $resource;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Session $session)
    {
        $session->title = $request->title;
        $session->description = $request->description;
        $session->save();
        $resource = new JsonResource($session);

        return $resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Session  $Session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return response()->json(['success' => true, 'message' => 'session has deleted successfully.']);
    }


    public function attachExam (Request $request, Session $session) {
        $session->exams()->attach($request->exam_id, ['course_id' => $session->course_id]);
        return response()->json(['success' => true, 'message' => 'Exam attach with session successfully.']);
    }

    public function attachLession (Request $request, Session $session) {
        $session->lessons()->attach($request->lession_id, ['course_id' => $session->course_id]);
        return response()->json(['success' => true, 'message' => 'Lesson attach with session successfully.']);
    }
}
