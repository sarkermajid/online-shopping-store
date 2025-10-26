<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryInformationController;
use App\Http\Controllers\FrontendBlogController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TermsAndConditionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/',function(){
//     return view('frontend.home.index');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/message', [ContactController::class, 'message'])->name('contact.message');
Route::get('/blogs', [FrontendBlogController::class, 'index'])->name('blogs');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/category/products/{id}', [ShopController::class, 'categoryProduct'])->name('category.product.view');
Route::get('/brand/products/{id}', [ShopController::class, 'brandProduct'])->name('brand.product.view');
Route::get('/product/single/view/{id}', [ShopController::class, 'singleProduct'])->name('product.single.view');
Route::get('blog/single/view/{id}', [FrontendBlogController::class, 'singleBlog'])->name('blog.single.view');
Route::get('/category/blog/view/{id}', [FrontendBlogController::class, 'categoryBlog'])->name('category.blog.view');

// wishlist route

Route::get('/product/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/add-to-wishlist', [WishlistController::class, 'addToWishList'])->name('wishlist.add');
Route::post('/wishlist/delete', [WishlistController::class, 'wishlistDelete'])->name('wishlist.delete');
Route::get('/wishlist/count', [WishlistController::class, 'wishlistCount'])->name('wishlist.count');

// cart route

Route::post('/add-to-cart', [CartController::class, 'index'])->name('addToCart');
Route::post('/direct-add-to-cart', [CartController::class, 'directAddtoCart'])->name('directAddToCart');
Route::get('/cart-view', [CartController::class, 'cartView'])->name('cart');
Route::post('/cart-delete', [CartController::class, 'cartDelete'])->name('cart.delete');
Route::post('/cart-update-inc', [CartController::class, 'cartUpdateInc'])->name('cart.update.inc');
Route::post('/cart-update-dec', [CartController::class, 'cartUpdateDec'])->name('cart.update.dec');
Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');

// Pages routes
Route::get('/about-us', [AboutUsController::class, 'view'])->name('aboutUs');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'view'])->name('privacyPolicy');
Route::get('/terms-and-condition', [TermsAndConditionController::class, 'view'])->name('termsAndCondition');
Route::get('/delivery-information', [DeliveryInformationController::class, 'view'])->name('deliveryInformation');

//  search routes
Route::get('/search', [HomeController::class, 'productListAjax'])->name('product.list');
Route::post('/search-product', [HomeController::class, 'productSearch'])->name('product.search');

Route::middleware(['auth'])->group(function () {
    // user routes
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::get('/user/edit/{id}', [UserProfileController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}', [UserProfileController::class, 'update'])->name('user.update');
    Route::get('/user/orders', [UserProfileController::class, 'orders'])->name('user.orders');
    Route::get('/user/orders/view/{id}', [UserProfileController::class, 'orderView'])->name('user.orders.view');

    // checkout routes

    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

    // Promo Code apply routes
    Route::get('/apply-promo-code', [CheckoutController::class, 'applyPromoCode'])->name('applyPromoCode');

    // Order place routes

    Route::post('/place_order', [CheckoutController::class, 'placeOrder'])->name('placeOrder');
});

Auth::routes();

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });

    // Categories Routes

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/add', 'index')->name('category.add');
        Route::post('/category/store', 'store')->name('category.store');
        Route::get('/category/manage', 'manage')->name('category.manage');
        Route::get('/category/view/{id}', 'view')->name('category.view');
        Route::get('/category/status', 'status')->name('category.status');
        Route::get('/category/edit/{id}', 'edit')->name('category.edit');
        Route::post('/category/update/{id}', 'update')->name('category.update');
        Route::post('/category/delete/', 'delete')->name('category.delete');
    });

    // Brands Routes

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brand/add', 'index')->name('brand.add');
        Route::post('/brand/store', 'store')->name('brand.store');
        Route::get('/brand/manage', 'manage')->name('brand.manage');
        Route::get('/brand/view/{id}', 'view')->name('brand.view');
        Route::get('/brand/status', 'status')->name('brand.status');
        Route::get('/brand/edit/{id}', 'edit')->name('brand.edit');
        Route::post('/brand/update/{id}', 'update')->name('brand.update');
        Route::post('/brand/delete/', 'delete')->name('brand.delete');
    });

    // Products routes

    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/add', 'index')->name('product.add');
        Route::post('/product/store', 'store')->name('product.store');
        Route::get('/product/manage', 'manage')->name('product.manage');
        Route::get('/product/view/{id}', 'view')->name('product.view');
        Route::get('/product/status', 'status')->name('product.status');
        Route::get('/product/edit/{id}', 'edit')->name('product.edit');
        Route::post('/product/update/{id}', 'update')->name('product.update');
        Route::post('/product/delete', 'delete')->name('product.delete');
        Route::post('/product/{id}/bargain', 'bargain')->name('product.bargain');
    });

    // Blog Categories routes

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog/category/add', 'blogCategoryIndex')->name('blog.category.add');
        Route::post('/blog/category/store', 'blogCategoryStore')->name('blog.category.store');
        Route::get('/blog/category/manage', 'blogCategoryManage')->name('blog.category.manage');
        Route::get('/blog/category/view/{id}', 'blogCategoryView')->name('blog.category.view');
        Route::get('/blog/category/status', 'blogCategoryStatus')->name('blog.category.status');
        Route::get('/blog/category/edit/{id}', 'blogCategoryEdit')->name('blog.category.edit');
        Route::post('/blog/category/update/{id}', 'blogCategoryUpdate')->name('blog.category.update');
        Route::post('/blog/category/delete', 'blogCategoryDelete')->name('blog.category.delete');
    });

    // Blogs Routes

    Route::controller(BlogController::class)->group(function () {
        Route::get('/blog/add', 'index')->name('blog.add');
        Route::post('/blog/store', 'store')->name('blog.store');
        Route::get('/blog/manage', 'manage')->name('blog.manage');
        Route::get('/blog/view/{id}', 'view')->name('blog.view');
        Route::get('/blog/status', 'status')->name('blog.status');
        Route::get('/blog/edit/{id}', 'edit')->name('blog.edit');
        Route::post('/blog/update/{id}', 'update')->name('blog.update');
        Route::post('/blog/delete', 'delete')->name('blog.delete');
    });

    // Users Routes for admin

    Route::controller(UserController::class)->group(function () {
        Route::get('/user/admin/manage', 'manage')->name('user.admin.manage');
        Route::post('/user/admin/delete', 'delete')->name('user.admin.delete');
        Route::get('/user/admin/view/{id}', 'view')->name('user.admin.view');
        Route::get('/user/admin/edit/{id}', 'edit')->name('user.admin.edit');
        Route::post('/user/admin/update/{id}', 'update')->name('user.admin.update');
    });

    // Contact message routes

    Route::controller(ContactController::class)->group(function () {
        Route::get('/admin/contact/message', 'contactMessage')->name('admin.contact.message');
        Route::get('/admin/contact/message/view/{id}', 'contactMessageView')->name('admin.contact.message.view');
        Route::post('/admin/contact/message/delete', 'contactMessageDelete')->name('admin.contact.message.delete');
    });

    // Banners routes
    Route::controller(BannerController::class)->group(function () {
        Route::get('/banner/add', 'index')->name('banner.add');
        Route::post('/banner/update/{id?}', 'update')->name('banner.update');
    });

    // Orders routes

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders/all', 'allOrders')->name('orders.all');
        Route::get('/order/view/{id}', 'view')->name('order.view');
        Route::get('/orders/pending', 'pending')->name('orders.pending');
        Route::get('/orders/ontheway', 'ontheway')->name('orders.ontheway');
        Route::get('/orders/completed', 'completed')->name('orders.completed');
        Route::get('/orders/cancel', 'cancelOrders')->name('orders.cancel');
        Route::get('/orders/status/pending', 'pendingStatusChange')->name('orders.status.pending');
        Route::get('/orders/status/ontheway', 'onthewayStatusChange')->name('orders.status.ontheway');
        Route::get('/orders/status/completed', 'completedStatusChange')->name('orders.status.completed');
        Route::get('/orders/status/cancel', 'cancelStatusChange')->name('orders.status.cancel');
        Route::get('/invoice/{id}', 'generateInvoice')->name('order.invoice');
    });

    // Promo Codes routes

    Route::controller(PromoCodeController::class)->group(function () {
        Route::get('/promo/create', 'index')->name('promo.add');
        Route::post('/promo/store', 'store')->name('promo.store');
        Route::get('/promo/manage', 'manage')->name('promo.manage');
        Route::get('/promo/view/{id}', 'view')->name('promo.view');
        Route::get('/promo/edit/{id}', 'edit')->name('promo.edit');
        Route::post('/promo/update/{id}', 'update')->name('promo.update');
        Route::get('/promo/status', 'status')->name('promo.status');
        Route::post('/promo/delete', 'delete')->name('promo.delete');
    });

    // GeneralSettings routes
    Route::get('/generalSettings', [GeneralSettingsController::class, 'generalSettings'])->name('generalSettings');
    Route::post('/generalSettings/update', [GeneralSettingsController::class, 'generalSettingsUpdate'])->name('generalSettings.update');

    // About us routes
    Route::get('/about-us/create', [AboutUsController::class, 'index'])->name('about-us');
    Route::post('/about-us/update/{id?}', [AboutUsController::class, 'store'])->name('about-us.update');
    // Privacy policy route
    Route::get('/privacy-policy/create', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');
    Route::post('/privacy-policy/update/{id?}', [PrivacyPolicyController::class, 'store'])->name('privacy-policy.update');
    // Terms and Conditions routes
    Route::get('/terms-and-conditions/create', [TermsAndConditionController::class, 'index'])->name('terms-and-condition');
    Route::post('/terms-and-conditions/update/{id?}', [TermsAndConditionController::class, 'store'])->name('terms-and-condition.update');
    // Delivery Information routes
    Route::get('/delivery-information/create', [DeliveryInformationController::class, 'index'])->name('delivery-information');
    Route::post('/delivery-information/update/{id?}', [DeliveryInformationController::class, 'store'])->name('delivery-information.update');
});
