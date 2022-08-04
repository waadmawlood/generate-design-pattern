<?php
namespace Waad\RepoMedia\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Waad\RepoMedia\Models\Media;
use App\Http\Requests\Pagination;
use Symfony\Component\HttpFoundation\Response;
use Waad\RepoMedia\Helpers\Utilities;
use Waad\RepoMedia\Repositories\MediaRepository;

class MediaController extends Controller
{
    private $MediaRepository;
    public function __construct()
    {
        //$this->middleware('role:admin', ['only' => ['getAll', 'store', 'update', 'change', 'show', 'destroy', 'delete', 'restore']]);
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
        return $this->MediaRepository->index($request->take , null , null , $request->trash);
    }


    /**
     * Display the specified resource if own me.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function getMe(Pagination $request)
    {
        $find = [
            'column' => 'user_id',
            'condition' => '=',
            'value' => auth()->user()->id
        ];
        return $this->MediaRepository->index($request->take, $find);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->MediaRepository->show($id);
    }

    /**
     * Update change the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request, $id)
    {
        if($request->has('status')){
            $data['status'] = $request->status;
        }
        else{
            $data['status'] = ! $this->MediaRepository->show($id)->status;
        }
        $this->MediaRepository->update($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Media change status successfully. id ' . $id,
        ], Response::HTTP_OK);
    }

    /**
     * Soft Delete the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Media::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Media Soft Deleted successfully. id ' . $id,
        ], Response::HTTP_OK);
    }

    /**
     * Force Delete the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media =  Media::withTrashed()->findOrFail($id);
        Utilities::deleteFile($media);
        $media->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'Media Force Deleted successfully. id ' . $id,
        ], Response::HTTP_OK);
    }

    /**
     * Restore the specified resource from Trashed.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Media::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            'success' => true,
            'message' => 'Media Restored successfully. id ' . $id,
        ], Response::HTTP_OK);
    }

}
