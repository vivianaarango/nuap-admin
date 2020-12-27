<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Requests\Admin\Product\IndexDiscount;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\IndexProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use App\Repositories\Contracts\DbProductRepositoryInterface;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ProductController
 * @package App\Http\Controllers\Admin
 */
class ProductController extends Controller
{
    /**
     * @var DbCommerceRepositoryInterface
     */
    private $dbCommerceRepository;

    /**
     * @var DbDistributorRepositoryInterface
     */
    private $dbDistributorRepository;

    /**
     * @var DbProductRepositoryInterface
     */
    private $dbProductRepository;

    /**
     * @var string
     */
    private $stock;

    /**
     * @var string
     */
    private $stockValue;

    /**
     * ProductController constructor.
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     * @param DbProductRepositoryInterface $dbProductRepository
     */
    public function __construct(
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository,
        DbProductRepositoryInterface $dbProductRepository
    ) {
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
        $this->dbProductRepository = $dbProductRepository;
    }

    /**
     * @param IndexProduct $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function list(IndexProduct $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Product::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'products.*',
                        'categories.name as category_id'
                    )->join('categories', 'categories.id', '=', 'products.category_id')
                    ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'name', 'description', 'image', 'category_id', 'brand', 'status', 'stock', 'purchase_price', 'sale_price'],
                    ['id', 'name', 'description', 'image', 'category_id', 'brand', 'status', 'stock', 'purchase_price', 'sale_price']
                );

            foreach ($data as $item) {
                $item->purchase_price = $this->formatCurrency($item->purchase_price) . ' $';
                $item->sale_price = $this->formatCurrency($item->sale_price) . ' $';
            }

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.products.index', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function create()
    {
        $user = Session::get('user');
        $commerces = $this->dbCommerceRepository->findValidCommercesToAddProducts();
        $distributors = $this->dbDistributorRepository->findValidDistributorsToAddProducts();

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            return view('admin.products.create', [
                'activation' => $user->role,
                'categories' => Category::all(),
                'commerces' => $commerces,
                'distributors' => $distributors
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @return Factory|Application|RedirectResponse|View
     */
    public function createDistributor()
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            return view('admin.products.create-distributor', [
                'activation' => $user->role,
                'categories' => Category::all(),
                'user_id' => $user->id
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $data = [];
        $data['status'] = Product::STATUS_INACTIVE;
        $data['position'] = 0;
        $data['user_id'] = $request['user_id'];
        $data['category_id'] = $request['category_id'];
        $data['name'] = $request['name'];
        $data['sku'] = $request['sku'];
        $data['brand'] = $request['brand'];
        $data['description'] = $request['description'];
        $data['stock'] = $request['stock'];
        $data['weight'] = $request['weight'];
        $data['length'] = $request['length'];
        $data['width'] = $request['width'];
        $data['height'] = $request['height'];
        $data['purchase_price'] = $request['purchase_price'];
        $data['sale_price'] = $request['sale_price'];
        $data['special_price'] = $request['special_price'];
        $data['is_featured'] = $request['is_featured'];
        $data['has_special_price'] = $request['has_special_price'];

        if ($data['is_featured'] === 'on') {
            $data['is_featured'] = true;
        } else {
            $data['is_featured'] = false;
        }

        if ($data['has_special_price'] === 'on') {
            $data['has_special_price'] = true;
        } else {
            $data['has_special_price'] = false;
        }

        $user = Session::get('user');
        if (isset($user) && ($user->role == User::ADMIN_ROLE || $user->role == User::DISTRIBUTOR_ROLE)) {
            $productImages = 'product-images/user/'.$request->user_id;

            if (!is_dir($productImages)) {
                mkdir($productImages, 0777, true);
            }
            $images = $_FILES['image'];
            if ($images['name'] != '') {
                $ext = pathinfo($images['name'], PATHINFO_EXTENSION);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $urlImage = "{$productImages}/".substr(str_shuffle($permitted_chars), 0, 16).".{$ext}";
                $destinationRoute = $urlImage;
                move_uploaded_file($images['tmp_name'], $destinationRoute);
                $data['image'] = $urlImage;
            }

            Product::create($data);

            if ($user->role === User::DISTRIBUTOR_ROLE){
                if ($request->ajax()) {
                    return [
                        'redirect' => url('admin/product-distributor-list'),
                        'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                    ];
                }

                return redirect('admin/product-distributor-list');
            }
            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/product-list'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }

            return redirect('admin/product-list');

        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Product $product
     * @return Response|Factory|Application|View
     */
    public function changeStatus(Product $product)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && $userAdmin->role == User::ADMIN_ROLE) {
            if ($product->status == Product::STATUS_ACTIVE) {
                $this->dbProductRepository->changeStatus($product->id, Product::STATUS_INACTIVE);
            } else {
                $this->dbProductRepository->changeStatus($product->id, Product::STATUS_ACTIVE);
            }

            return response(['redirect' => url('admin/product-list')]);

        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Product $product
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function delete(Product $product)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && ($adminUser->role == User::ADMIN_ROLE || $adminUser->role == User::DISTRIBUTOR_ROLE)) {
            $this->dbProductRepository->delete($product->id);
        } else {
            return redirect('admin/user-session');
        }
    }

    /**
     * @param Product $product
     * @return Response|Factory|Application|View
     */
    public function edit(Product $product)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && ($userAdmin->role == User::ADMIN_ROLE || $userAdmin->role == User::DISTRIBUTOR_ROLE)) {
            $product = $this->dbProductRepository->findByID($product->id);
            $data['product_id'] = $product->id;
            $data['user_id'] = $product->user_id;
            $data['category_id'] = $product->category_id;
            $data['name'] = $product->name;
            $data['sku'] = $product->sku;
            $data['brand'] = $product->brand;
            $data['description'] = $product->description;
            $data['is_featured'] = $product->is_featured;
            $data['stock'] = $product->stock;
            $data['weight'] = $product->weight;
            $data['length'] = $product->length;
            $data['width'] = $product->width;
            $data['height'] = $product->height;
            $data['purchase_price'] = $product->purchase_price;
            $data['sale_price'] = $product->sale_price;
            $data['special_price'] = $product->special_price;
            $data['has_special_price'] = $product->has_special_price;

            return view('admin.products.edit', [
                'product' => $data,
                'activation' => $userAdmin->role,
                'url' => $product->resource_url,
                'name' => $product->name,
                'categories' => Category::all()
            ]);

        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return array|Application|RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        $adminUser = Session::get('user');

        if (isset($adminUser) && ($adminUser->role == User::ADMIN_ROLE || $adminUser->role == User::DISTRIBUTOR_ROLE)) {
            $productImages = 'product-images/user/'.$request['user_id'];

            if (!is_dir($productImages)) {
                mkdir($productImages, 0777, true);
            }
            $image = null;
            $images = $_FILES['image'];
            if ($images['name'] != '') {
                $ext = pathinfo($images['name'], PATHINFO_EXTENSION);
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $urlImage = "{$productImages}/".substr(str_shuffle($permitted_chars), 0, 16).".{$ext}";
                $destinationRoute = $urlImage;
                move_uploaded_file($images['tmp_name'], $destinationRoute);
                $image = $urlImage;
            }

            if ($adminUser->role === User::DISTRIBUTOR_ROLE){
                $status = Product::STATUS_INACTIVE;
            } else {
                $status = $this->dbProductRepository->findByID($request['product_id'])->status;
            }


            $this->dbProductRepository->update(
                $request['product_id'],
                $request['category_id'],
                $request['name'],
                $request['sku'],
                $request['brand'],
                $request['description'],
                $request['stock'],
                $request['weight'],
                $request['length'],
                $request['width'],
                $request['height'],
                $request['purchase_price'],
                $request['sale_price'],
                $request['special_price'],
                $status,
                $image
            );

            if ($adminUser->role === User::DISTRIBUTOR_ROLE){
                return redirect('admin/product-distributor-list');
            }

            return redirect('admin/product-distributor-list');
        }
        return redirect('admin/user-session');
    }

    /**
     * @param Product $product
     * @return Response|Factory|Application|View
     */
    public function view(Product $product)
    {
        $userAdmin = Session::get('user');

        if (isset($userAdmin) && ($userAdmin->role == User::ADMIN_ROLE || $userAdmin->role == User::DISTRIBUTOR_ROLE)) {
            $product = $this->dbProductRepository->findByID($product->id);
            $product->purchase_price = $this->formatCurrency($product->purchase_price) . ' $';
            $product->sale_price = $this->formatCurrency($product->sale_price) . ' $';

            return view('admin.products.view', [
                'product' => $product,
                'activation' => $userAdmin->role,
                'categories' => Category::all(),
            ]);
        }

        return redirect('/admin/user-session');
    }

    /**
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport, 'exports.xlsx');
    }

    /**
     * @param IndexDiscount $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function editDiscount(IndexDiscount $request)
    {
        $user = Session::get('user');

        if (isset($user) && $user->role == User::ADMIN_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Product::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'products.*',
                        'categories.name as category_id'
                    )->join('categories', 'categories.id', '=', 'products.category_id')
                        ->where('status', Product::STATUS_ACTIVE)
                        ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'name', 'category_id', 'brand', 'special_price'],
                    ['id', 'name', 'category_id', 'brand', 'special_price']
                );

            if ($request->ajax()) {
                if ($request->has('bulk')) {
                    return [
                        'bulkItems' => $data->pluck('id')
                    ];
                }
                return ['data' => $data];
            }

            return view('admin.products.discount', [
                'data' => $data,
                'activation' => $user->role,
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Application|RedirectResponse|Response
     */
    public function updateDiscount(Request $request)
    {
        $newDiscount = $request->data['commission'];
        $idsProducts = $request->data['ids'];

        foreach ($idsProducts as $item) {
            $product = $this->dbProductRepository->findByID($item);
            $product->special_price = $newDiscount;
            $product->save();
        }

        if ($request->ajax()) {
            return response(['message' => trans('Se ha actualizado el descuento correctamente')]);
        }

        return redirect()->back();
    }

    /**
     * @param IndexProduct $request
     * @return array|Factory|Application|RedirectResponse|Redirector|View
     */
    public function listDistributor(IndexProduct $request)
    {
        $user = Session::get('user');
        $this->stock = '<>';
        $this->stockValue = '-1';

        if (isset($request['status_stock']) && $request['status_stock'] === 'Disponible') {
            $this->stock = '>';
            $this->stockValue = '0';
        }

        if (isset($request['status_stock']) && $request['status_stock'] === 'No Disponible') {
            $this->stock = '=';
            $this->stockValue = '0';
        }
        //dd($request['status_stock']);
        if (isset($user) && $user->role == User::DISTRIBUTOR_ROLE) {
            /* @noinspection PhpUndefinedMethodInspection  */
            $data = AdminListing::create(Product::class)
                ->modifyQuery(function($query) {
                    $query->select(
                        'products.*',
                        'categories.name as category_id'
                    )->where('user_id', Session::get('user')->id)
                    ->where('stock', $this->stock, $this->stockValue)
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->orderBy('id', 'desc');
                })->processRequestAndGet(
                    $request,
                    ['id', 'name', 'description', 'image', 'category_id', 'brand', 'status', 'stock', 'purchase_price', 'sale_price'],
                    ['id', 'name', 'description', 'image', 'category_id', 'brand', 'status', 'stock', 'purchase_price', 'sale_price']
                );

            foreach ($data as $item) {
                $item->purchase_price = $this->formatCurrency($item->purchase_price) . ' $';
                $item->sale_price = $this->formatCurrency($item->sale_price) . ' $';
            }

            if ($request->ajax()) {
                return ['data' => $data, 'activation' => $user->role];
            }

            return view('admin.products.index-distributor', [
                'data' => $data,
                'activation' => $user->role
            ]);
        } else {
            return redirect('/admin/user-session');
        }
    }

    /**
     * @param $floatcurr
     * @param string $curr
     * @return string
     */
    public function formatCurrency($floatcurr, $curr = "COP"): string
    {
        $currencies['COP'] = array(0,',','.');
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}
