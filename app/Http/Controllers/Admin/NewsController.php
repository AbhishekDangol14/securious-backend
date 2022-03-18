<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreNewsCategoryRequest;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\GetNewsResource;
use App\Http\Resources\ShowNewsResource;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(NewsService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(new GetNewsResource($this->service->paginate(14, ['id', 'is_active','news_category_id'], ['translations'],[],'created_at')), 'News fetched successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNewsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request): JsonResponse
    {
        $news = $this->service->autoStore($request);

        $this->service->insertTranslations($request->get('friendlyTranslations'), $news);

        return $this->success(
            (array)ShowNewsResource::make($news),
            'News created successfully!',
            201
        );
    }

    public function edit(Request $request, $id): JsonResponse
    {
        $news = News::find($id);
        $data = [];
        $data['id'] = $news->id;
        $data['image'] = $news->firstMedia('uploads') ? $news->firstMedia('uploads')->getUrl() : '';
        $data['is_active'] = $news->is_active ? true : false;
        $data['news_category_id'] = $news->news_category_id;
        $data['friendlyTranslations'] = $news->translations->mapWithKeys(function($news)
        {
            return [
                $news->language . '.' . $news->attribute_name => [
                    'id' => $news['id'],
                    'name' => $news['attribute_name'],
                    'value' => $news['attribute_value'],
                ]
            ];
        });
        return $this->success($data,'News fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNewsRequest  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $updatedNews = $this->service->autoUpdate($request, $news);

        $this->service->updateTranslations($request->get('friendlyTranslations'));

        return $this->success(
            (array)ShowNewsResource::make($updatedNews), 'News updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $this->service->delete($news);

        return $this->success('News deleted successfully!');
    }

    public function deleteImage($id,News $news){
        $this->service->deleteImage($id,$news);
    }

    public function toggleStatus(Request $request) {
        $this->service->toggleStatus($request);
        return $this->success('Status Updated successfully');        
    }
}
