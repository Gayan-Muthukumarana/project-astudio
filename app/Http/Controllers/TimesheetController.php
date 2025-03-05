<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $timesheets = Timesheet::query()->applyFilters($request, Timesheet::getAllowedFilters())->get();

        if ($timesheets->count() == 0) {
            $data['responseMessage'] = 'No data found!';
            $data['responseName'] = 'data';
            $data['data'] = [];

            return $this->successResponse($data, 200);
        }

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $timesheets;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $timesheet = Timesheet::findOrFail($id);

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $timesheet;

        return $this->successResponse($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $timesheet = Timesheet::create([
            'task_name' => $validated['task_name'],
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'user_id' => $validated['user_id'],
            'project_id' => $validated['project_id'],
        ]);

        $data['responseMessage'] = 'Timesheet created successfully!!';
        $data['responseName'] = 'data';
        $data['data'] = $timesheet;

        return $this->successResponse($data, 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'task_name' => 'string|max:255',
            'date' => 'date',
            'hours' => 'numeric|min:0',
            'user_id' => 'exists:users,id',
            'project_id' => 'exists:projects,id',
        ]);

        $timesheet = Timesheet::findOrFail($id);
        $timesheet->update($validated);

        $data['responseMessage'] = 'Timesheet updated successfully!';
        $data['responseName'] = 'data';
        $data['data'] = $timesheet;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();

        $data['responseMessage'] = 'Timesheet deleted successfully!';
        $data['responseName'] = 'Deleted Timesheet ID';
        $data['data'] = $id;

        return $this->successResponse($data, 200);
    }
}
