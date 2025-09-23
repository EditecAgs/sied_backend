<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function getStudents()
    {
        $students = Student::with(['institution:id,name', 'specialty:id,name', 'career:id,name'])->get();

        return response()->json($students, Response::HTTP_OK);
    }

    public function getStudentById($id)
    {
        $student = Student::with(['institution:id,name', 'specialty:id,name', 'career:id,name'])->findOrFail($id);

        return response()->json($student, Response::HTTP_OK);
    }

    public function createStudent(StudentRequest $request)
    {
        $data = $request->validated();
        $student = Student::create($data);

        return response()->json($student, Response::HTTP_CREATED);
    }

    public function updateStudent(StudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $data = $request->validated();
        $student->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
