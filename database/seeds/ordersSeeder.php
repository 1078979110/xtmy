<?php

use Illuminate\Database\Seeder;
use App\Order;

class ordersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Order::class,150)->create();
    }
}
