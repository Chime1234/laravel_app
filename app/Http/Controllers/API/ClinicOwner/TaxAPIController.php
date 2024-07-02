<?php
/*
 * File name: TaxAPIController.php
 * Last modified: 2024.04.11 at 06:51:55
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Http\Controllers\API\ClinicOwner;


use App\Http\Controllers\Controller;
use App\Repositories\TaxRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ClinicController
 * @package App\Http\Controllers\API
 */
class TaxAPIController extends Controller
{
    /** @var  taxRepository */
    private TaxRepository $taxRepository;

    public function __construct(TaxRepository $taxRepo)
    {
        $this->taxRepository = $taxRepo;
        parent::__construct();
    }

    /**
     * Display a listing of the Clinic.
     * GET|HEAD /taxes
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->taxRepository->pushCriteria(new RequestCriteria($request));
            $this->taxRepository->pushCriteria(new LimitOffsetCriteria($request));
            $taxes = $this->taxRepository->all();
            $this->filterCollection($request, $taxes);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($taxes->toArray(), 'Taxes retrieved successfully');
    }

}