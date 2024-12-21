<?php

namespace Modules\BlogContent\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ServiceContainer;
use App\Services\CommonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\BlogContent\Models\BlogContent as ModelsBlogContent;
use Modules\BlogContent\Repositories\ModelRepository;
use Modules\Blog\Repositories\ModelRepository as BlogRepository;

class BlogContentController extends Controller
{
    public function __construct(
        public ServiceContainer $services,
        public CommonService $commonService,
        public ModelRepository $repository,
        public BlogRepository $blog
    ) {
    }

    public function index(Request $request)
    {
        $blogs = $this->blog->getAll();
        $result = $this->services->generalService->handleIndex($request, $this->repository, 80, 'blog_id');

        return view('blogcontent::index', array_merge($result, [
            'blogs' => $blogs,
            'activeItemsCount' => $this->repository->all_active()->count(),
        ]));
    }


    public function create()
    {
        $blogs = $this->blog->all_active();
        $languages = $this->services->langRepository->all_active();
        return view('blogcontent::create', compact('languages', 'blogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->executeSafely(function () use ($request) {
            $this->services->crudService->create(new ModelsBlogContent(), $request, 'blogcontent');
            return redirect()->route('blogcontent.index')->with('status', 'Kontent uğurla əlavə edildi');
        }, 'blogcontent.index');
    }

    public function show($id)
    {
        return view('blogcontent::show');
    }

    public function edit($id)
    {
        return $this->executeSafely(function () use ($id) {
            $blogs = $this->blog->all_active();
            $blogcontent = $this->repository->find($id);
            $languages = $this->services->langRepository->all_active();
            return view('blogcontent::edit', compact('languages', 'blogcontent', 'blogs'));
        }, 'blogcontent.index');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        return $this->executeSafely(function () use ($request, $id) {
            $partner = $this->repository->find($id);
            $this->services->crudService->update($partner, $request, 'blogcontent');
            return redirect()->route('blogcontent.index')->with('status', 'Kontent uğurla yeniləndi');
        }, 'blogcontent.index');
    }

    public function destroy($id)
    {
        // Burada destroy metodunu implement edə bilərsiniz
    }

    public function changeStatusTrue($id)
    {
        return $this->commonService->changeStatus($id, $this->repository, $this->services->statusService, new ModelsBlogContent(), true, 'blogcontent.index');
    }

    public function changeStatusFalse($id)
    {
        return $this->commonService->changeStatus($id, $this->repository, $this->services->statusService, new ModelsBlogContent(), false, 'blogcontent.index');
    }

    public function delete_selected_items(Request $request)
    {
        return $this->commonService->deleteSelectedItems($this->repository, $request, $this->services->removeService, 'blogcontent.index');
    }

    public function deleteFile($id)
    {
        return $this->commonService->deleteFile($id, $this->services->imageService, 'blogcontent.index');
    }
}
