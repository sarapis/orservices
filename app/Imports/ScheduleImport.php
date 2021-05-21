<?php

namespace App\Imports;

use App\Model\LocationSchedule;
use App\Model\Schedule;
use App\Model\ServiceSchedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScheduleImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $schedule = Schedule::max('schedule_recordid') + 1;
        $array = [
            'schedule_recordid' => $row['schedule_recordid'],
            'schedule_services' => $row['service_id'],
            'schedule_locations' => $row['location_id'],
            'service_at_location' => $row['service_at_location_id'],
            'valid_from' => $row['valid_from'],
            'valid_to' => $row['valid_to'],
            'schedule_start_date' => $row['dtstart'],
            'schedule_end_date' => $row['until'],
            'wkst' => $row['wkst'],
            'freq' => $row['freq'],
            'interval' => $row['interval'],
            'byday' => $row['byday'],
            'byweekno' => $row['byweekno'],
            'bymonthday' => $row['bymonthday'],
            'byyearday' => $row['byyearday'],
            'description' => $row['description'],
        ];

        if ($row['service_id']) {
            $service_schedule = new ServiceSchedule();
            $service_schedule->service_recordid = $row['service_id'] != 'NULL' ? $row['service_id'] : null;
            $service_schedule->schedule_recordid = $row['schedule_recordid'];
            $service_schedule->save();
        }
        if ($row['location_id']) {
            $location_schedule = new LocationSchedule();
            $location_schedule->location_recordid = $row['location_id'] != 'NULL' ? $row['location_id'] : null;
            $location_schedule->schedule_recordid = $row['schedule_recordid'];
            $location_schedule->save();
        }

        return new Schedule($array);
    }
}
