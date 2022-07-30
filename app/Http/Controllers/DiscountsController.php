<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Discount\DiscountResource;
use App\Models\Entities\Discount;
use App\Models\Enums\DiscountAmountType;
use App\Models\Enums\DiscountType;
use App\Models\Enums\HttpStatusCode;
use App\Models\General\BreadCrumb;
use App\Models\Griew\Griew;
use App\Repositories\Campaign\CampaignRepository;
use App\Repositories\Discount\DiscountRepository;
use App\Repositories\DiscountUsage\DiscountUsageRepository;
use App\Repositories\Location\LocationRepository;
use App\Repositories\Property\PropertyRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DiscountsController extends Controller
{
    protected $months = [
        "01" => 'فروردین',
        "02" => 'اردیبهشت',
        "03" => 'خرداد',
        "04" => 'تیر',
        "05" => 'مرداد',
        "06" => 'شهریور',
        "07" => 'مهر',
        "08" => 'آبان',
        "09" => 'آذر',
        "10" => 'دی',
        "11" => 'بهمن',
        "12" => 'اسفند'
    ];

    /**
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('admin_discounts_list');

        $pageTitle = 'کدهای تخفیف';

        $breadCrumb = new BreadCrumb();
        $breadCrumb->addItem($pageTitle, '/admin/discounts');

        return view('admin.discounts.index')->with([
            'breadCrumb' => $breadCrumb,
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws AuthorizationException
     */
    public function grid(Request $request)
    {
        $this->authorize('admin_discounts_list');

        $griew = new Griew($request);
        $discountsRepository = new discountRepository();
        $discountUsageRepository = new discountUsageRepository();
        $discounts = $discountsRepository->getAll($griew->getFrom(), $griew->getPagination()->getPerPage(), $total, $griew->getOrders(), $griew->getFilters());

        foreach ($discounts as $discount) {
            $reserveCount = $discountUsageRepository->getDefiniteReserveByDiscountId($discount->getId());
            $discount->setDefiniteReserveCount(count($reserveCount));
            if (!is_null($discount->getReserveStartAt()) && !is_null($discount->getReserveEndAt())) {
                $discount->setReserveStartAt(myDate()->toJalaliDateTimeString($discount->getReserveStartAt(), 'yyyy-MM-dd HH:mm'));
                $discount->setReserveEndAt(myDate()->toJalaliDateTimeString($discount->getReserveEndAt(), 'yyyy-MM-dd HH:mm'));
                $discount->setStartAt(myDate()->toJalaliDateTimeString($discount->getStartAt(), 'yyyy-MM-dd HH:mm'));
                $discount->setEndAt(myDate()->toJalaliDateTimeString($discount->getEndAt(), 'yyyy-MM-dd HH:mm'));
            }
        }

        $griew->setTotal($total);
        $griew->setData($discounts ? (new DiscountResource())->collectionToArray($discounts) : null);

        return json_encode($griew);
    }

    public function edit($id)
    {
        $this->authorize('admin_discounts_edit');

        $pageTitle = 'ویرایش کد تخفیف';

        $breadCrumb = new BreadCrumb();
        $breadCrumb->addItem('کدهای تخفیف', '/admin/discounts');
        $breadCrumb->addItem($pageTitle);

        $locationRepository = new LocationRepository();
        $discountRepository = new DiscountRepository();
        $propertyRepository = new PropertyRepository();
        $campaignRepository = new CampaignRepository();

        $discountAmountType = (new DiscountAmountType())->getList();
        $discountType = (new DiscountType())->getList();

        $discount = $discountRepository->getOneById($id);
        $cities = $locationRepository->getAllCities();
        $campaigns = $campaignRepository->getAllActive();
        if (is_null($discount)) abort(404);
        $property = $propertyRepository->getOneById($discount->getPropertiesId());
        $city = $locationRepository->getOneById($discount->getCitiesId());
        $campaign = $campaignRepository->getOneById($discount->getCampaignId());

        return view('admin.discounts.edit')->with([
            'discountAmountType' => array_reverse($discountAmountType),
            'discountType' => $discountType,
            'discount' => $discount,
            'cities' => $cities,
            'campaigns' => $campaigns,
            'property' => $property,
            'city' => $city,
            'monthNames' => $this->months,
            'campaign' => $campaign,
            'breadCrumb' => $breadCrumb,
            'pageTitle' => $pageTitle,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_discounts_edit');

        $discountRepository = new DiscountRepository();
        $discount = $discountRepository->getOneById($id);
        if (is_null($discount)) {
            abort(404);
        }
        $data = $request->all();
        $discount->setDisabled($data['disabled']);
        $discountRepository->update($discount);
        if ($request->has('update-and-stay')) {
            return redirect('admin/discounts/' . $discount->getId() . '/edit')->with('message', 'کد تخفیف با موفقیت ویرایش شد');
        }

        return redirect('admin/discounts')->with('message', 'کد تخفیف با موفقیت ویرایش شد');
    }

    public function statusToggle(Request $request)
    {
        $this->authorize('admin_discounts_edit');
        $data = $request->all();
        $result = (new DiscountRepository())->statusToggle($data);
        if ($result) {
            $response = ['status' => true, 'message' => 'تغییر وضعیت کد با موفقیت انجام شد.'];
        } else {
            $response = ['status' => false, 'message' => 'خطایی رخ داده است.'];
        }
        return response()->json($response);
    }

    public function create()
    {
        $this->authorize('admin_discounts_create');

        $pageTitle = 'ایجاد کد تخفیف';

        $breadCrumb = new BreadCrumb();
        $breadCrumb->addItem('کدهای تخفیف', '/admin/discounts');
        $breadCrumb->addItem($pageTitle);

        $locationRepository = new LocationRepository();
        $campaignRepository = new CampaignRepository();

        $discountAmountType = (new DiscountAmountType())->getList();
        $discountType = (new DiscountType())->getList();

        $cities = $locationRepository->getAllCities();
        $campaigns = $campaignRepository->getAllActive();

        return view('admin.discounts.create')->with([
            'discountAmountType' => array_reverse($discountAmountType),
            'discountType' => $discountType,
            'cities' => $cities,
            'campaigns' => $campaigns,
            'monthNames' => $this->months,
            'breadCrumb' => $breadCrumb,
            'pageTitle' => $pageTitle,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_discounts_create');
        $data = $request->all();
        if (!is_null($data['start_year']) && !is_null($data['start_month']) && !is_null($data['start_day']) && !is_null($data['start_time'])) {
            $start_at = $data['start_year'] . '-' . $data['start_month'] . '-' . $data['start_day'] . ' ' . $data['start_time'] . ':00:00';
            $request->request->add(['start_at' => myDate()->toGregorianDateTimeString($start_at)]);
        }
        if (!is_null($data['end_year']) && !is_null($data['end_month']) && !is_null($data['end_day']) && !is_null($data['end_time'])) {
            $end_at = $data['end_year'] . '-' . $data['end_month'] . '-' . $data['end_day'] . ' ' . $data['end_time'] . ':00:00';
            $request->request->add(['end_at' => myDate()->toGregorianDateTimeString($end_at)]);
        }
        if (!is_null($data['reserve_start_year']) && !is_null($data['reserve_start_month']) && !is_null($data['reserve_start_day'])) {
            $reserve_start_at = $data['reserve_start_year'] . '-' . $data['reserve_start_month'] . '-' . $data['reserve_start_day'] . ' ' . '00:00:00';
            $request->request->add(['reserve_start_at' => myDate()->toGregorianDateTimeString($reserve_start_at)]);
        }
        if (!is_null($data['reserve_end_year']) && !is_null($data['reserve_end_month']) && !is_null($data['reserve_end_day'])) {
            $reserve_end_at = $data['reserve_end_year'] . '-' . $data['reserve_end_month'] . '-' . $data['reserve_end_day'] . ' ' . '00:00:00';
            $request->request->add(['reserve_end_at' => myDate()->toGregorianDateTimeString($reserve_end_at)]);
        }
        if ($data['amount_type'] == 'percent') {
            $request->request->add(['max_percent' => $data['amount']]);
        }

        $request->validate([
            'name' => 'required',
            'start_at' => 'date|after:yesterday',
            'end_at' => 'date|after:start_at',
            'reserve_start_at' => 'date|after:yesterday',
            'reserve_end_at' => 'date|after:reserve_start_at',
            'reserve_min_price' => 'nullable|numeric|max:reserve_max_price',
            'reserve_min_night' => 'nullable|numeric|max:reserve_max_night',
            'amount' => 'required',
            'discount_code' => 'required|unique:new_discounts',
            'max_percent' => 'nullable|numeric|max:100',
            'amount_type' => 'required',
            'type' => 'required',
        ]);

        $discountRepository = new DiscountRepository();
        $discount = new Discount();
        $discount->setName($data['name']);
        $discount->setCampaignId($data['campaign_id']);
        $discount->setCitiesId($request->input('city_id') ? json_encode($request->input('city_id')) : null);
        $discount->setPropertiesId($request->input('property_id') ? json_encode($request->input('property_id')) : null);
        $discount->setAmount($data['amount']);
        $discount->setAmountType($data['amount_type']);
        $discount->setDiscountCode($data['discount_code']);
        $discount->setPurchaseCount($data['purchase_count']);
        $discount->setType($data['type']);
        $discount->setCount($data['count']);
        $discount->setUsableDuration($data['usable_duration']);
        $discount->setDisabled($data['disabled']);
        $discount->setHasChildCode($data['has_child_code']);
        $discount->setReserveMinPrice($data['reserve_min_price']);
        $discount->setReserveMaxPrice($data['reserve_max_price']);
        $discount->setReserveMinNight($data['reserve_min_night']);
        $discount->setReserveMaxNight($data['reserve_max_night']);
        $discount->setStartAt($request->input('start_at'));
        $discount->setEndAt($request->input('end_at'));
        $discount->setReserveStartAt($request->input('reserve_start_at'));
        $discount->setReserveEndAt($request->input('reserve_end_at'));
        $parentInfo = $discountRepository->create($discount);

        if ($parentInfo->getType() == DiscountType::UNIQUE) {
            $discountUsageRepository = new DiscountUsageRepository();
            $requiredCount = $parentInfo->getCount();
            $newDiscountsCode = [];
            for ($generateCount = 1; $generateCount <= $requiredCount; $generateCount++) {
                $newDiscountsCode[] = [
                    'discount_id' => $parentInfo->getId(),
                    'discount_code' => $parentInfo->getDiscountCode() . '-' . Str::random(4),
                    'expired_at' => $parentInfo->getUsableDuration() ? date('Y-m-d H:i:s', time() + $parentInfo->getUsableDuration()) : null,
                ];
            }
            $discountUsageRepository->insert($newDiscountsCode);
            $discount->setGenerateCount($parentInfo->getCount());
            $discountRepository->update($discount);
        }
        if ($request->has('update-and-stay')) {
            return redirect('admin/discounts/' . $discount->getId() . '/edit')->with('message', 'کد تخفیف با موفقیت ایجاد شد');
        }
        return redirect('admin/discounts')->with('message', 'کد تخفیف با موفقیت ایجاد شد');
    }

    public function remove($id)
    {
        $this->authorize('admin_discounts_edit');
        $discountRepository = new DiscountRepository();
        if ($discountRepository->remove($id)) {
            $response = response()->json(['status' => HttpStatusCode::OK, 'message' => 'کد تخفیف  با موفقیت حذف شد.']);
        } else {
            $response = response()->json(['status' => HttpStatusCode::NOT_ACCEPTABLE, 'message' => 'عملیات با خطا مواجه شد.']);
        }
        return $response;
    }
}
