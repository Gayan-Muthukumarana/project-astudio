<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Project::query()->applyFilters($request, Project::getAllowedFilters());

        if ($request->has('filters.eav')) {
            foreach ($request->input('filters.eav') as $attribute => $value) {
                $query->whereHas('attributes', function ($q) use ($attribute, $value) {
                    $q->where('name', $attribute)->where('value', 'LIKE', "%$value%");
                });
            }
        }

        if ($query->get()->count() == 0) {
            $data['responseMessage'] = 'No data found!';
            $data['responseName'] = 'data';
            $data['data'] = [];

            return $this->successResponse($data, 200);
        }

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $query->get();

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::with('attributes')->findOrFail($id);

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $project;

        return $this->successResponse($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|int',
            'attributes' => 'array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        $project = Project::create([
            'name' => $validatedFields['name'],
            'status' => $validatedFields['status'],
        ]);

        foreach ($validatedFields['attributes'] as $attributeData) {
            AttributeValue::create([
                'attribute_id' => $attributeData['id'],
                'entity_id' => $project->id,
                'value' => $attributeData['value'],
            ]);
        }

        $data['responseMessage'] = 'Project created successfully!!';
        $data['responseName'] = 'data';
        $data['data'] = $project;

        return $this->successResponse($data, 201);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedFields = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'attributes' => 'array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        $project = Project::findOrFail($id);
        $project->update([
            'name' => $validatedFields['name'],
            'status' => $validatedFields['status'],
        ]);

        foreach ($validatedFields['attributes'] as $attributeData) {
            AttributeValue::updateOrCreate(
                [
                    'attribute_id' => $attributeData['id'],
                    'entity_id' => $project->id
                ],
                [
                    'value' => $attributeData['value']
                ]
            );
        }

        $data['responseMessage'] = 'Project updated successfully!';
        $data['responseName'] = 'data';
        $data['data'] = $project;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Project::destroy($id);

        $data['responseMessage'] = 'Project deleted successfully!';
        $data['responseName'] = 'Deleted Project ID';
        $data['data'] = $id;

        return $this->successResponse($data, 200);
    }

    /**
     * Assign users(multiple or single user) to a project.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignUsersToProject(Request $request, $id)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::findOrFail($id);

        if (!$project) {
            $data['responseMessage'] = 'No project found for the given Project ID!';
            $data['responseName'] = 'data';
            $data['data'] = [];

            return $this->errorResponse($data, 422);
        }

        $project->users()->sync($validated['user_ids']);

        $data['responseMessage'] = 'User(s) assigned to the project successfully!!';
        $data['responseName'] = 'data';
        $data['data'] = $project->load('users');

        return $this->successResponse($data, 200);
    }
}
