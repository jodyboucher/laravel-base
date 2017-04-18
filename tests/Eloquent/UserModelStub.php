<?php

namespace JodyBoucher\Laravel\Test\Eloquent;

use Illuminate\Database\Eloquent\Model;
use JodyBoucher\Laravel\Eloquent\FilteringTrait;
use JodyBoucher\Laravel\Eloquent\SortingTrait;

class UserModelStub extends Model
{
    use FilteringTrait, SortingTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
}
