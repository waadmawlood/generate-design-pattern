<?php

namespace Waad\RepoMedia\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pagination;
use Symfony\Component\HttpFoundation\Response;
use Waad\RepoMedia\Helpers\Utilities;
use Waad\RepoMedia\Models\Media;
use Waad\RepoMedia\Repositories\MediaRepository;

class MediaController extends Controller
{
    private $MediaRepository;

    public function __construct()
    {
        $this->middleware('role:Admin', ['only' => ['index', 'getAll', 'store', 'update', 'change', 'show', 'destroy', 'delete', 'restore']]);
        $this->middleware('auth:api', ['only' => ['getMe']]);
        $this->MediaRepository = new MediaRepository(new Media());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        return $this->MediaRepository->index($request->take);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Pagination $request)
    {
        return $this->MediaRepository->index($request->take, null, null, $request->trash);
    }

    /**
     * Display the specified resource if own me.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMe(Pagination $request)
    {
        $find = [
            'column' => 'user_id',
            'condition' => '=',
            'value' => auth()->user()->id,
        ];

        return $this->MediaRepository->index($request->take, $find);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Media $medium)
    {
        return $this->MediaRepository->show($medium);
    }

    /**
     * Update change the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Media $medium)
    {
        $data['status'] = !$medium->status;
        $this->MediaRepository->update($medium, $data);

        return response()->json([
            'success' => true,
            'message' => 'Media change status successfully. id '.$medium->id,
        ], Response::HTTP_OK);
    }

    /**
     * Soft Delete the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Media $medium)
    {
        $medium->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media Soft Deleted successfully. id '.$medium->id,
        ], Response::HTTP_OK);
    }

    /**
     * Force Delete the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $medium)
    {
        Utilities::deleteFile($medium);
        $medium->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Media Force Deleted successfully. id '.$medium->id,
        ], Response::HTTP_OK);
    }

    /**
     * Restore the specified resource from Trashed.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Media $medium)
    {
        $medium->restore();

        return response()->json([
            'success' => true,
            'message' => 'Media Restored successfully. id '.$medium->id,
        ], Response::HTTP_OK);
    }
}
