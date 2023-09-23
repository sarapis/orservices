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
        // dd($row);
        $schedules = null;
        if (empty($row['name'])) {
            if (isset($row['service_id']) && ($row['opens'] && isset($row['closes']) || isset($row['weekday']))) {
                $schedules = Schedule::where('services', $row['service_id'])->where('opens', $row['opens'])->where('closes', $row['closes'])->orWhereRaw('FIND_IN_SET(?, weekday)', [$row['weekday']])->first();
            } else if (isset($row['location_id']) && ($row['opens'] && isset($row['closes']) || isset($row['weekday']))) {
                if ((isset($row['opens']) && isset($row['closes'])) && $row['opens'] && $row['closes']) {
                    $schedules = Schedule::where('locations', $row['location_id'])->where('opens', $row['opens'])->where('closes', $row['closes'])->orWhereRaw('FIND_IN_SET(?, weekday)', [$row['weekday']])->first();
                } else {
                    $schedules = Schedule::where('locations', $row['location_id'])->whereNotNull('schedule_closed')->orWhereRaw('FIND_IN_SET(?, weekday)', [$row['weekday']])->first();
                }
            }
        }
        if ($schedules) {
            // $schedules->opens = $row['opens'];
            // $schedules->closes = $row['closes'];
            if ($row['opens'] != 'Closed' && $row['opens'] != 'Holiday') {
                $schedules->opens = isset($row['opens']) && $row['opens'] ? $row['opens'] : $schedules->opens;
                $schedules->closes = isset($row['opens']) && $row['opens'] ? $row['opens'] : $schedules->closes;
            }
            $schedules->services = $row['service_id'];
            $schedules->locations = $row['location_id'];
            $schedules->weekday = $schedules->weekday ? (str_contains($schedules->weekday, $row['weekday']) ? $schedules->weekday : ($schedules->weekday . ',' . $row['weekday'])) : $row['weekday'];
            $schedules->schedule_holiday = $schedules->schedule_holiday ? (str_contains($schedules->schedule_holiday, $row['schedule_holiday']) ? $schedules->schedule_holiday : ($schedules->schedule_holiday . ',' . $row['schedule_holiday'])) : $row['schedule_holiday'];
            $schedules->schedule_closed = $schedules->schedule_closed ? (str_contains($schedules->schedule_closed, $row['schedule_closed']) ? $schedules->schedule_closed : ($schedules->schedule_closed . ',' . $row['schedule_closed'])) : $row['schedule_closed'];
            $schedules->open_24_hours = $schedules->open_24_hours ? (str_contains($schedules->open_24_hours, $row['open_24_hours']) ? $schedules->open_24_hours : ($schedules->open_24_hours . ',' . $row['open_24_hours'])) : $row['open_24_hours'];

            $schedules->save();
            return null;
        } else {
            $array = [
                'schedule_recordid' => $row['id'],
                'name' => $row['name'],
                'services' => $row['service_id'],
                'locations' => $row['location_id'],
                'service_at_location' => $row['service_at_location_id'],
                'weekday' => $row['weekday'],
                'valid_from' => $row['valid_from'],
                'valid_to' => $row['valid_to'],
                'dtstart' => $row['dtstart'],
                'opens' => $row['opens'] != 'Closed' && $row['opens'] != 'Holiday' ? $row['opens'] : null,
                'until' => $row['until'],
                'closes' => $row['closes'],
                'wkst' => $row['wkst'],
                'freq' => $row['freq'],
                'interval' => $row['interval'],
                'byday' => $row['byday'],
                'byweekno' => $row['byweekno'],
                'bymonthday' => $row['bymonthday'],
                'byyearday' => $row['byyearday'],
                'description' => $row['description'],
                'schedule_holiday' => isset($row['schedule_holiday']) ? $row['schedule_holiday'] : null,
                'schedule_closed' => isset($row['schedule_closed']) ? $row['schedule_closed'] : null,
                'open_24_hours' => isset($row['open_24_hours']) ? $row['open_24_hours'] : null,
                'opens_at' => isset($row['opens_at']) ? $row['opens_at'] : null,
                'closes_at' => isset($row['closes_at']) ? $row['closes_at'] : null,
            ];

            if ($row['service_id']) {
                $service_ids = explode(',', $row['service_id']);
                foreach ($service_ids as $key => $value) {
                    $service_schedule = new ServiceSchedule();
                    $service_schedule->service_recordid = $value;
                    $service_schedule->schedule_recordid = $row['id'];
                    $service_schedule->save();
                }
            }
            if ($row['location_id']) {
                $location_ids = explode(',', $row['location_id']);
                foreach ($location_ids as $key => $value) {
                    $location_schedule = new LocationSchedule();
                    $location_schedule->location_recordid = $value;
                    $location_schedule->schedule_recordid = $row['id'];
                    $location_schedule->save();
                }
            }
            return new Schedule($array);
        }
    }
    public function chunkSize(): int
    {
        return 1;
    }
}
