<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $courses = Course::all();
        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string',
            'description'  => 'required|string'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course,$id)
    {
        //
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Sorry, Course not found'], 404);
        }

        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Sorry, Course not found'], 404);
        }

        // Set validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string',
            'description'  => 'required|string'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update course properties
        $course->title = $request->title;
        $course->description = $request->description;

        // Save the updated course
        $course->save();

        return response()->json($course, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, $id)
    {
        //
        $lesson = Course::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Sorry,Courses Not Found'], 404);
        }

        // Delete the lesson record from the database
        $lesson->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
