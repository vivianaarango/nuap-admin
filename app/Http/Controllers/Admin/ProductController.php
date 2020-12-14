<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\DbCommerceRepositoryInterface;
use App\Repositories\Contracts\DbDistributorRepositoryInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

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
     * UsersController constructor.
     * @param DbCommerceRepositoryInterface $dbCommerceRepository
     * @param DbDistributorRepositoryInterface $dbDistributorRepository
     */
    public function __construct(
        DbCommerceRepositoryInterface $dbCommerceRepository,
        DbDistributorRepositoryInterface $dbDistributorRepository
    ) {
        $this->dbCommerceRepository = $dbCommerceRepository;
        $this->dbDistributorRepository = $dbDistributorRepository;
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
        if (isset($user) && $user->role == User::ADMIN_ROLE) {
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

            if ($request->ajax()) {
                return [
                    'redirect' => url('admin/products-create'),
                    'message' => trans('brackets/admin-ui::admin.operation.succeeded')
                ];
            }

            return redirect('admin/products-create');

        } else {
            return redirect('/admin/user-session');
        }
    }
}
