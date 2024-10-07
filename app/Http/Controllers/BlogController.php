<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{

    public function createBlog(Request $request)
    {
        // 1. Validate Data
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50'
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'validation failed, Unprocessable Entity',
                'error' => $exception->errors()
            ], 400);
        }

        try {

            $blogs = Blog::create($validatedData);

            return response()->json([
                'message' => 'Blog created successfully!',
                'data' => $blogs
            ], 201);

        } catch (\Exception $exception) {

            return response()->json([
                'message' => 'Failed to create Blog, try again',
                'error' => $exception->getMessage()
            ], 500);
        }


    }


    public function updateBlog(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog with the provided Id is not found'
            ], 404);
        }

        try {
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
                'category' => 'sometimes|required|string|max:100',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50'
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $exception->errors()
            ], 400);
        }

        try {
            $blog->update($validatedData);

            return response()->json([
                'message' => 'Blog updated successfully',
                'data' => $blog
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Failed to update Blog, try again',
                'errors' => $exception->getMessage()
            ], 500);
        }
    }

    public function deleteBlog($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found with the provided id!'
            ], 404);
        }

        try {

            $blog->delete();

            return response()->json([
                'message' => 'Blog deleted successfully!'
            ], 204);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Failed to delete the blog!',
                'errors' => $exception->getMessage()
            ], 500);
        }
    }

    public function findOneBlog($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found with the provided id'
            ], 404);
        }

        return response()->json([
            'message' => 'Blog found successfully!',
            'data' => $blog
        ], 200);
    }

    public function findAll(Request $request)
    {
        $searchTerm = $request->query('term');
        $query = Blog::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('content', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('category', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $blogs = $query->get();

        return response()->json([
            'message' => 'Blogs retrieved successfully',
            'data' => $blogs
        ], 200);
    }

}
