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
            'schedule_recordid' => $schedule,
            'schedule_services' => $row['service_id'],
            'schedule_days_of_week' => $row['weekday'],
            'schedule_opens_at' => $row['opens_at'],
            'schedule_closes_at' => $row['closes_at'],
            'schedule_description' => $row['original_text'],
            'schedule_locations' => $row['location_id'],
        ];

        if ($row['service_id']) {
            $service_schedule = new ServiceSchedule();
            $service_schedule->service_recordid = $row['service_id'] != 'NULL' ? $row['service_id'] : null;
            $service_schedule->schedule_recordid = $schedule;
            $service_schedule->save();
        }
        if ($row['location_id']) {
            $location_schedule = new LocationSchedule();
            $location_schedule->location_recordid = $row['location_id'] != 'NULL' ? $row['location_id'] : null;
            $location_schedule->schedule_recordid = $schedule;
            $location_schedule->save();
        }

        return new Schedule($array);
    }
}
