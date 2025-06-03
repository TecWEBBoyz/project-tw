<?php

namespace PTW\Repositories;

use PTW\Models\Booking;
class BookingRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct("booking");
        $this->element_class = new Booking();
    }

    public function GetByUser(string $userId) : array|null {
        return $this->GetElementsByColumn("user_id", $userId);
    }

}