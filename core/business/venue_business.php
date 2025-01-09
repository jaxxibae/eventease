<?php

require_once __DIR__ . '/../data/venue_dao.php';
require_once __DIR__ . '/../utils/encryption.php';

class VenueBusiness
{
    private $venueDAO;

    public function __construct()
    {
        $this->venueDAO = new VenueDAO();
    }

    public function get_venues(): array
    {
        return $this->venueDAO->get_venues();
    }

    public function create_venue($venue_name, $venue_city, $venue_country, $venue_capacity): array
    {
        $venue_location = $venue_city . ', ' . $venue_country;
        return $this->venueDAO->create_venue($venue_name, $venue_location, $venue_capacity);
    }

    public function get_venue_by_id($id): array
    {
        return $this->venueDAO->get_venue_by_id($id);
    }

    public function edit_venue($id, $venue_name, $venue_city, $venue_country, $venue_capacity): array
    {
        $venue_location = $venue_city . ', ' . $venue_country;
        return $this->venueDAO->update_venue($id, $venue_name, $venue_location, $venue_capacity);
    }

    public function delete_venue_by_id($id): array
    {
        return $this->venueDAO->delete_venue_by_id($id);
    }
}