<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\EnrollmentProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function show(Request $request)
    {
        $edition = $request->input('edition');
        $user = $request->user();

        if ($user && $user->isParticipant()) {
            $isParticipant = $user->isParticipant();
            $enrollment = $user->usertype->enrollments()->where('edition_id', $edition->id)->first();

            // This makes one less query to the DB ☝️🤓
            $isEnrolled = $enrollment !== null;
            $points = $enrollment?->points;
        }

        if ($edition === null) {
            return response('No edition found', 500);
        }

        $products = $edition->products()->orderBy('price')->get()->each(function (Product $product) use ($user) {
            $product->canBeBought =
                $product->stock <= 0
                    ? false
                    : $user?->can('buy', $product) ?? true;
        });

        if (Gate::allows('admin') || Gate::allows('staff', [$edition])) {
            $products->load([
                'enrollments' => [
                    'participant' => ['user'],
                ],
            ]);
        }

        return Inertia::render('Shop', [
            'products' => $products,
            'points' => $points ?? null,
            'isEnrolled' => $isEnrolled ?? null,
            'isParticipant' => $isParticipant ?? null,
        ]);
    }

    public function buyProduct(Request $request, Product $product)
    {
        $edition = $request->input('edition');

        if ($edition === null) {
            return response('No edition found', 500);
        }

        /** @var User|null */
        $user = $request->user();

        if ($user === null) {
            return redirect()->back()->dangerBanner('Tens que ter sessão iniciada para poderes comprar este produto');
        }

        if ($user->cannot('buy', $product)) {
            return redirect()->back()->dangerBanner('Não podes comprar este produto');
        }

        $user->usertype->enrollments()->where('edition_id', $edition->id)->first()->products()->attach($product);
        $product->decrement('stock', 1);

        return redirect()->back()->banner('Produto comprado');
    }

    public function redeemProduct(Request $request, Product $product, Enrollment $enrollment)
    {
        Gate::authorize('redeem', [$product, $enrollment]);

        EnrollmentProduct::where('enrollment_id', $enrollment->id)->where('product_id', $product->id)->update(['redeemed' => true]);

        return redirect()->back()->banner('Produto entregue');
    }
}
