<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         //
         $lesson = Lesson::all();
         return response()->json($lesson);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //set validation
        $validator = Validator::make($request->all(), [
            'date_published'     => 'required|string',
            'title'  => 'required|string',
            'author' => 'required|string|max:255',
            'article' => 'required|string',
            'course_id' => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $document = new Lesson();
        $document->title = $request->title;
        $document->date_published = $request->date_published;
        $document->author = $request->author;
        $document->article = $request->article;
        $document->course_id = $request->course_id;



        $document->save();

        return response()->json($document, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $attachment, $id)
    {
        //
        $attachment = Lesson::find($id);

        if ( !$attachment){
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        return response()->json($attachment);

    }

    public function showbycourseid($id)
    {
        //
        $attachments = Lesson::where('course_id', $id)->get();

        if ( !$attachments){
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        return response()->json($attachments);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson, $id)
    {
        //
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Lesson Not Found'], 404);
        }

        // Set validation rules
        $validator = Validator::make($request->all(), [
            'date_published'     => 'required|string',
            'title'  => 'required|string',
            'author' => 'required|string|max:255',
            'article' => 'required|string',
            'course_id' => 'required'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update lesson properties
        $lesson->title = $request->title;
        $lesson->date_published = $request->date_published;
        $lesson->author = $request->author;
        $lesson->article = $request->article;
        $lesson->course_id = $request->course_id;

        // Save the updated lesson
        $lesson->save();

        return response()->json($lesson, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Lesson Not Found'], 404);
        }

        // Delete the lesson record from the database
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully'], 200);
    }
}
