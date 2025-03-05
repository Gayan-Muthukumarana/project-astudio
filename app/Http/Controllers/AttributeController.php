<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Attribute::query();

        foreach (Attribute::getAllowedFilters() as $filter) {
            if ($request->has($filter)) {
                $query->where($filter, 'like', '%' . $request->input($filter) . '%');
            }
        }

        $attributes = $query->get();

        if ($attributes->count() == 0) {
            $data['responseMessage'] = 'No data found!';
            $data['responseName'] = 'data';
            $data['data'] = [];

            return $this->successResponse($data, 200);
        }

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $attributes;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $attribute = Attribute::find($id);

        $data['responseMessage'] = 'Data retrieved!';
        $data['responseName'] = 'data';
        $data['data'] = $attribute;

        return $this->successResponse($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,date,number,select',
        ]);

        $attribute = Attribute::updateOrCreate(
            ['name' => $validated['name']],
            ['type' => $validated['type']]
        );

        $data['responseMessage'] = 'Attribute created successfully!!';
        $data['responseName'] = 'data';
        $data['data'] = $attribute;

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
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'type' => 'required|in:text,date,number,select',
        ]);

        $attribute = Attribute::find($id);

        $attribute->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        $data['responseMessage'] = 'Attribute updated successfully!';
        $data['responseName'] = 'data';
        $data['data'] = $attribute;

        return $this->successResponse($data, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $attribute = Attribute::find($id);
        $attribute->delete();

        $data['responseMessage'] = 'Attribute deleted successfully!';
        $data['responseName'] = 'Deleted Attribute ID';
        $data['data'] = $id;

        return $this->successResponse($data, 200);
    }
}
