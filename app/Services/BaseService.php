<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;
use Plank\Mediable\Mediable;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;
use Image;
use Str;

class BaseService
{
   protected BaseRepository $repository;

    public function all() {
        return $this->repository->all();
    }

    public function find($id){
        return $this->repository->find($id);
    }
    public function paginate(int $pages = 15, $selectedColumns, $relations = [], $conditions = [], $orderBy = null) {
        return $this->repository->paginate($pages, $selectedColumns, $relations, $conditions, $orderBy);
    }

    public function autoStore(Request $request){

        $fillables = $this->repository->fillables();

        $data = [];
        $values=$request->all();

        foreach ($fillables as $fillable)
        {
            if (isset($values[$fillable])) {
                $data[$fillable] = $values[$fillable];
            }
        }

        $model= $this->store($data);

        if ($request->has('image') && $request->get('image') && $this->repository->hasFeature(Mediable::class)){
            $this->saveImage($request->get('image'),$model);  
        }

        return $model;

    }
    public function edit($data, $id){

    }

    public function store($data) {
        return $this->repository->store($data);
    }

    public function update($data, $id) {
        return $this->repository->update($data, $id);
    }

    public function autoUpdate(Request $request, Model $model)
    {
            $fillables = $this->repository->fillables();

            $data = [];
            $values=$request->all();
            foreach ($fillables as $fillable) {
                if (isset($values[$fillable])) {
                    $data[$fillable] = $values[$fillable];
                }
            }

            $model->update($data);

            if ($request->has('image') && $this->repository->hasFeature(Mediable::class) && $this->is_base64($request->get('image'))){ 
                $this->saveImage($request->get('image'),$model,true);
            }

        return $model;

    }

    public function delete( Model $model): void
    {

        $this->repository->delete($model->id);
        $model->translations()->delete();

    }

    public function deleteImage( $id,Model $model ): void
    {
        $model = $model->with('media')->find($id);
        $model->media()->delete();
    }

    public function simpleDelete(Model $model) :void
    {
        $this->repository->delete($model->id);
    }

    public function toggleStatus($data) 
    {
        $this->repository->toggleStatus($data->id, $data->is_active);
    }

    public function saveImage($image,$model,$update=null) {
        $type = explode('/',explode(';',$image)[0]);
            if ($type[1] == 'svg+xml'){
                $media = $this->saveSVG($image);
            }
            else {
                $image = Image::make($image)->stream('png');
                $media = MediaUploader::fromSource($image)
                    ->toDestination('public', 'product/uploads')
                    ->useFilename(uniqid('uploads_', true))
                    ->upload();

            }
        $update ? $model->syncMedia($media, ['uploads']) : $model->attachMedia($media, ['uploads']);
    }

    public function is_base64($data)
    {
        if (preg_match("/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).base64,.*/", $data)){
            return true;
        }
        return false;
    }

    public function saveSVG($image_64)
    {
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        $image = str_replace($replace, '', $image_64); 

        $image = str_replace(' ', '+', $image); 

        $name = Str::random(10).'.'.'svg';
        Storage::disk('public')->put('/product/uploads/' . $name, base64_decode($image));

        $media = new Media();

        $media->disk = 'public';
        $media->directory = 'product/uploads';
        $media->filename = explode('.',$name)[0];
        $media->extension = 'svg';
        $media->mime_type = 'svg+xml';
        $media->aggregate_type = 'image';
        $media->save();

        return $media;  
    }
}
