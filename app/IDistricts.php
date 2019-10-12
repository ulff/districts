<?php
namespace App;

Interface IDistrict
{
    public function getById(Int $id): array;
    public function sortBy(String $name): array;
    public function createNew();
    public function delete();
    public function update();
    public function filtrBy(String $name, String $order);
    public function getAll();

}
