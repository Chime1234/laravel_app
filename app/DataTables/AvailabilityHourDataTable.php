<?php
/*
 * File name: AvailabilityHourDataTable.php
 * Last modified: 2021.11.24 at 19:13:48
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\DataTables;

use App\Models\AvailabilityHour;
use App\Models\CustomField;
use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class AvailabilityHourDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static array $customFields = [];

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable(mixed $query): DataTableAbstract
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->editColumn('day', function ($availabilityHour) {
                return translateDay($availabilityHour['day']);
            })
            ->editColumn('data', function ($availabilityHour) {
                return getStripedHtmlColumn($availabilityHour, 'data');
            })
            ->editColumn('doctor.name', function ($availabilityHour) {
                return getLinksColumnByRouteName([$availabilityHour->doctor], 'doctors.edit', 'id', 'name');
            })
            ->addColumn('action', 'availability_hours.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = [
            [
                'data' => 'day',
                'title' => trans('lang.availability_hour_day'),

            ],
            [
                'data' => 'start_at',
                'title' => trans('lang.availability_hour_start_at'),

            ],
            [
                'data' => 'end_at',
                'title' => trans('lang.availability_hour_end_at'),

            ],
            [
                'data' => 'data',
                'title' => trans('lang.availability_hour_data'),

            ],
            [
                'data' => 'doctor.name',
                'name' => 'doctor.name',
                'title' => trans('lang.availability_hour_doctor_id'),

            ]
        ];

        $hasCustomField = in_array(AvailabilityHour::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', AvailabilityHour::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.availability_hour_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param AvailabilityHour $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AvailabilityHour $model): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->hasRole('clinic_owner')) {
            return $model->newQuery()->with("doctor")->join("doctors", "doctors.id", "=", "availability_hours.doctor_id")
                ->join("clinic_users", "clinic_users.clinic_id", "=", "doctors.clinic_id")
                ->where('clinic_users.user_id', auth()->id())
                ->groupBy('availability_hours.id')
                ->select('availability_hours.*');

        } else if (auth()->user()->hasRole('doctor')){
            return $model->newQuery()->with("doctor")->join('doctors', 'doctors.id', '=', 'availability_hours.doctor_id')
                ->where('doctors.user_id', auth()->id())
                ->groupBy('availability_hours.id')
                ->select('availability_hours.*');
        }
        else {
            return $model->newQuery()->with("doctor")->select("$model->table.*");
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html(): Builder
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf(): mixed
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'availability_hoursdatatable_' . time();
    }
}
