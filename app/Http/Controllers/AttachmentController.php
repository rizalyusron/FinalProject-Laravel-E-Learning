<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $attachment = Attachment::all();
        return response()->json($attachment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //set validation
        $validator = Validator::make($request->all(), [
            'file_name'     => 'required|string',
            'course_id'  => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $document = new Attachment();
        $document->title = $request->title;
        $document->description = $request->description;
        $document->file_name = $request->file_name;
        $document->course_id = $request->course_id;


         // Memeriksa apakah ada file pdf yang diupload
         if ($request->hasFile('pdf_path')) { // Note: Menggunakan hasFile langsung dari request
            $path = $request->file('pdf_path')->store('pdfs'); // Simpan file ke folder pdfs
            $document->pdf_path = $path; // Menyimpan path file ke database
            Log::info('File stored at path: ' . $path);
        }


        $document->save();

        return response()->json($document, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment, $id)
    {
        //
        $attachment = Attachment::find($id);

        if ( !$attachment){
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        return response()->json($attachment);

    }

    public function showbycourseid($id)
    {
        //
        $attachments = Attachment::where('course_id', $id)->get();

        if ( !$attachments){
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        return response()->json($attachments);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, Attachment $attachment)
    {
        // Find the attachment by ID
        $attachment = Attachment::find($id);

        // If attachment not found
        if (!$attachment) {
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        // Set validation rules
        $validator = Validator::make($request->all(), [
            'file_name'     => 'required|string',
            'course_id'  => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update attachment properties
        $attachment->title = $request->title;
        $attachment->description = $request->description;
        $attachment->file_name = $request->file_name;
        $attachment->course_id = $request->course_id;

        // Check if a new PDF file is uploaded
        if ($request->hasFile('pdf_path')) {
            // Delete the old file if exists
            if ($attachment->pdf_path && Storage::exists($attachment->pdf_path)) {
                Storage::delete($attachment->pdf_path);
            }

            // Store the new file
            $path = $request->file('pdf_path')->store('pdfs');
            $attachment->pdf_path = $path;
            Log::info('File updated at path: ' . $path);
        }

        // Save the updated attachment
        $attachment->save();

        return response()->json($attachment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment, $id)
    {
        //
        // Find the attachment by ID
        $attachment = Attachment::find($id);

        // If attachment not found
        if (!$attachment) {
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        // Delete the file from storage if exists
        if ($attachment->pdf_path && Storage::exists($attachment->pdf_path)) {
            Storage::delete($attachment->pdf_path);
        }

        // Delete the attachment record from the database
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully'], 200);
    }

    public function download($id)
    {
        // Cari attachment berdasarkan id
        $attachment = Attachment::find($id);

        // Jika attachment tidak ditemukan
        if (!$attachment) {
            return response()->json(['error' => 'Attachment Not Found'], 404);
        }

        // Path file di storage
        $path = $attachment->pdf_path;

        // Cek apakah file ada di storage
        if (!Storage::exists($path)) {
            return response()->json(['error' => 'File Not Found'], 404);
        }



        // Mengembalikan file sebagai respons unduhan
        return Storage::download($path, $attachment->title.'-'.$attachment->description.'-'.$attachment->file_name.'.pdf');
    }


}
