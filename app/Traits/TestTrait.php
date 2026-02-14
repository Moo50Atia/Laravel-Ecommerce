<?php

namespace App\Traits;

trait TestTrait
{
    public int $x = 1;
}


class Service
{
    public int $x = 1;

    public function changeX()
    {
        $this->x = 2;
    }
}

class SubService extends Service
{
    public function changeX()
    {
        $this->x = 3;
    }
}

class SubService1 extends Service
{
    public function changeX()
    {
        $this->x = 4;
    }
}

$subService1 = new SubService1();
$subService1->changeX();
echo $subService1->x;
echo "<br>";
$service = new Service();
// $service->changeX();
echo $service->x;
echo "<br>";

$subService = new SubService();
// $subService->changeX();
echo $subService->x;
echo "<br>";
